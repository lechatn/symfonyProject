<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Group;
use App\Entity\HabitTracking;
use App\Entity\User;
use App\Entity\Habits;
use Symfony\Component\HttpFoundation\Request;
use App\Form\GroupeCreationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\Mail;
use App\Entity\ScoreHistory;

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

        $idGroup = $user->getIdGroup() ? $user->getIdGroup()->getId() : null;

        $groupHistory = $managerRegistry->getRepository(ScoreHistory::class)->findBy(['idGroup' => $user->getIdGroup()]);
        $groupHistory = array_reverse($groupHistory);

        $userMail = $user->getEmail();

        return $this->render('group/group.html.twig', [
            'formGroup' => $form->createView(),
            'isInGroup' => $user->getIdGroup() !== null,
            'groupMembers' => $groupMembers,
            'groupScore' => $groupScore,
            'groups' => $groups,
            'groupName' => $groupName,
            'allUsers' => $allUsers,
            'idGroup' => $idGroup,
            'groupHistory' => $groupHistory,
            'userMail' => $userMail,
            'currentUser' => $user,
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

        $entityManager = $managerRegistry->getManager();

        
        $groupHabitTrackings = $managerRegistry->getRepository(HabitTracking::class)->findBy(['idUser' => $user, 'idGroup' => $user->getIdGroup()]);
        foreach ($groupHabitTrackings as $habitTracking) {
            $entityManager->remove($habitTracking);
        }

        $group = $user->getIdGroup();
        if ($group->getCreatorId() === $user) {
            $groupMembers = $managerRegistry->getRepository(User::class)->findBy(['idGroup' => $group]);
            foreach ($groupMembers as $member) {
                $member->setIdGroup(null);
                $entityManager->persist($member);
            }
            $entityManager->remove($group);
        }


        $user->setIdGroup(null);


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

        // Assigner les tâches de groupe à l'utilisateur qui rejoint le groupe
        $groupHabitsTracking = $managerRegistry->getRepository(HabitTracking::class)->findBy(['idGroup' => $group]);
        foreach ($groupHabitsTracking as $habit) {
            $habitTracking = new HabitTracking();
            $habitTracking->setIdHabit($habit->getIdHabit());
            $habitTracking->setIdUser($user);
            $habitTracking->setIdGroup($group);
            $habitTracking->setStatus(false);
            $habitTracking->setDate(new \DateTime('now'));
            $entityManager->persist($habitTracking);
        }

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

        $email = $request->request->get('useremail');
        $idGroup = $request->request->get('groupId');
        $userRepository = $managerRegistry->getRepository(User::class);
        $invitedUser = $userRepository->findOneBy(['email' => $email]);
        
        if (!$invitedUser) {
            throw $this->createNotFoundException('User with this email was not found.');
        }

        $group = $managerRegistry->getRepository(Group::class)->find($idGroup);

        if (!$group) {
            throw $this->createNotFoundException('Group not found');
        }

        $groupName = $group->getName();

        $mail = new Mail();

        $mail->setType('Invit to join a group!');
        $mail->setDescription('You have been invited to join the group : ' . $groupName);
        $mail->setUserMail($invitedUser);
        $mail->setIdGroup($group);
        $mail->setIdSender($user);

        $entityManager = $managerRegistry->getManager();
        $entityManager->persist($mail);
        $entityManager->flush();      



        return $this->redirectToRoute('group');
    }

    #[Route ('/group/decline/{mail}', name: 'decline_group')]
    public function declineInvitation(TokenStorageInterface $tokenStorage, ManagerRegistry $managerRegistry, Request $request, $mail): Response
    {
        $token = $tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('No token found in storage.');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not an instance of User.');
        }

        $entityManager = $managerRegistry->getManager();
        $mailEntity = $managerRegistry->getRepository(Mail::class)->find($mail);

        if (!$mailEntity) {
            throw $this->createNotFoundException('Mail not found.');
        }

        $entityManager->remove($mailEntity);
        $entityManager->flush();

        return $this->redirectToRoute('mail');
    }

}