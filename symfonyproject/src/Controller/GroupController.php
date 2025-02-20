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

        $groupHistory = $managerRegistry->getRepository(ScoreHistory::class)->findBy(['idGroup' => $user->getIdGroup()]);
        $groupHistory = array_reverse($groupHistory);

        return $this->render('group/group.html.twig', [
            'formGroup' => $form->createView(),
            'isInGroup' => $user->getIdGroup() !== null,
            'groupMembers' => $groupMembers,
            'groupScore' => $groupScore,
            'groups' => $groups,
            'groupName' => $groupName,
            'groupHistory' => $groupHistory,
        ]);
    }

    public function group(TokenStorageInterface $tokenStorage): Response
    {
        $token = $tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('No token found in storage.');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not an instance of User.');
        }


        return $this->render('group/group.html.twig',[
            'isInGroup' => $user->getIdGroup() !== null,
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

        // Supprimer les tâches de groupe associées à l'utilisateur
        $groupHabitTrackings = $managerRegistry->getRepository(HabitTracking::class)->findBy(['idUser' => $user, 'idGroup' => $user->getIdGroup()]);
        foreach ($groupHabitTrackings as $habitTracking) {
            $entityManager->remove($habitTracking);
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
}