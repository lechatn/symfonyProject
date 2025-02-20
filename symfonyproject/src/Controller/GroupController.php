<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\GroupeCreationType;
use Doctrine\Common\Lexer\Token;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\Mail;

class GroupController extends AbstractController
{
    #[Route('/group', name: 'formgroup')]
    public function index(Request $request, ManagerRegistry $managerRegistry, TokenStorageInterface $tokenStorage): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupeCreationType::class, $group);

        $token = $tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('No token found in storage.');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not an instance of User.');
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $group->setCreatorId($user);
            $group->setScore(0);

            $user->setIdGroup($group);

            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($group);
            $entityManager->flush();
        }

        $groupMembers = $managerRegistry->getRepository(User::class)->findBy(['idGroup' => $user->getIdGroup()]);

        $groupScore = $user->getIdGroup() !== null ? $user->getIdGroup()->getScore() : null;

        $groups = $managerRegistry->getRepository(Group::class)->findAll();

        $groupName = $user->getIdGroup() !== null ? $user->getIdGroup()->getName() : null;

        $allUsers = $managerRegistry->getRepository(User::class)->findAll();

        $idGroup = $user->getIdGroup();

        return $this->render('group/group.html.twig', [
            'formGroup' => $form->createView(),
            'isInGroup' => $user->getIdGroup() !== null,
            'groupMembers' => $groupMembers,
            'groupScore' => $groupScore,
            'groups' => $groups,
            'groupName' => $groupName,
            'allUsers' => $allUsers,
            'idGroup' => $idGroup,
        ]);
    }

    public function group(TokenStorageInterface $tokenStorage, ManagerRegistry $managerRegistry): Response
    {
        $token = $tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('No token found in storage.');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not an instance of User.');
        }

        $allUsers = $managerRegistry->getRepository(User::class)->findAll();

        $idGroup = $user->getIdGroup();


        return $this->render('group/group.html.twig',[
            'isInGroup' => $user->getIdGroup() !== null,
            'allUsers' => $allUsers,
            'idGroup' => $idGroup,
        ]);
    }

    #[Route('/group/leave', name: 'leave_group')]
    public function leaveGroup(TokenStorageInterface $tokenStorage, ManagerRegistry $managerRegistry): Response
    {
        $token = $tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('No token found in storage.');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not an instance of User.');
        }

        $user->setIdGroup(null);

        $entityManager = $managerRegistry->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('group');
    }

    #[Route('/group/join/{id}', name: 'join_group')]
    public function joinGroup(TokenStorageInterface $tokenStorage, ManagerRegistry $managerRegistry, $id): Response
    {
        $token = $tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('No token found in storage.');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not an instance of User.');
        }

        $group = $managerRegistry->getRepository(Group::class)->find($id);

        $user->setIdGroup($group);

        $entityManager = $managerRegistry->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('group');
    }

    #[Route('/group/invite', name: 'invite_user')]
    public function inviteUser(Request $request, TokenStorageInterface $tokenStorage, ManagerRegistry $managerRegistry): Response
    {
        $token = $tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('No token found in storage.');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not an instance of User.');
        }

        $email = $request->request->get('email');
        $idGroup = $request->request->get('idGroup');
        $groupName = $managerRegistry->getRepository(Group::class)->find($idGroup)->getName();
        $mail = new Mail();

        $mail->setType('invitation');
        $mail->setDescription('You have been invited to join the group ' . $groupName);
        $mail->setUserMail($managerRegistry->getRepository(User::class)->findOneBy(['email' => $email]));
        $mail->setIdGroup($idGroup);
        $mail->setIdSender($user);

        $entityManager = $managerRegistry->getManager();
        $entityManager->persist($mail);
        $entityManager->flush();      



        return $this->redirectToRoute('group');
    }

}