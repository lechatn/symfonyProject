<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Habits;
use App\Entity\HabitTracking;
use App\Form\HabitFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\User;

class HabitsController extends AbstractController
{
    #[Route('/habits', name: 'formhabits')]
    public function index(Request $request, ManagerRegistry $managerRegistry, TokenStorageInterface $tokenStorage): Response
    {
        $habits = new Habits();
        
        $token = $tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('No token found in storage.');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not an instance of User.');
        }

        $isGroupCreator = $user->getIdGroup() ? $user->getIdGroup()->getCreatorId() === $user : false;

        $habitsForm = $this->createForm(HabitFormType::class, $habits, [
            'is_group_creator' => $isGroupCreator,
        ]);

        $habitsForm->handleRequest($request);   

        if ($habitsForm->isSubmitted() && $habitsForm->isValid())
        {
            $entityManager = $managerRegistry->getManager();
            $habit = $habitsForm->getData();

            $isGrouptask = $habitsForm->get('isGroupTask')->getData();

            if ($isGrouptask) {
                $group = $user->getIdGroup();
                $allUsers = $group->getUsers();
                foreach ($allUsers as $groupUser) {
                    $habitTracking = new HabitTracking();
                    $habitTracking->setIdHabit($habit);
                    $habitTracking->setIdUser($groupUser);
                    $habitTracking->setIdGroup($group);
                    $habitTracking->setStatus(false);
                    $habitTracking->setDate(new \DateTime('now'));
                    $entityManager->persist($habitTracking);
                }
            } else {
                $habitTracking = new HabitTracking();
                $habitTracking->setIdHabit($habit);
                $habitTracking->setIdUser($user);
                $habitTracking->setStatus(false);
                $habitTracking->setDate(new \DateTime('now'));
                $entityManager->persist($habitTracking);
            }

            $entityManager->persist($habit);
            $entityManager->flush();
        }

        $userHabitTrackings = $managerRegistry->getRepository(HabitTracking::class)->findBy(['idUser' => $user]);

        $userHabits = [];
        foreach ($userHabitTrackings as $tracking) {
            $userHabits[] = $tracking->getIdHabit();
        }

        return $this->render('habits/habits.html.twig', [
            'formHabits' => $habitsForm->createView(),
            'dataUser' => $userHabits,
            'userTrackings' => $userHabitTrackings,
            'userPoints' => $user->getScore(),
        ]);
    }

    #[Route('/complete-task/{id}', name: 'complete_task', methods: ['POST'])]
    public function completeTask(int $id, Request $request, ManagerRegistry $managerRegistry, TokenStorageInterface $tokenStorage): Response
    {
        $entityManager = $managerRegistry->getManager();
        $habitTracking = $entityManager->getRepository(HabitTracking::class)->find($id);

        if ($habitTracking) {
            $habitTracking->setStatus(true);
            $points = $request->request->get('points', 0);
            $user = $habitTracking->getIdUser();
            $user->setScore($user->getScore() + $points);
            $entityManager->flush();
        }

        return $this->redirectToRoute('formhabits');
    }

    public function habits()
    {
        return $this->render('habits/habits.html.twig',[]);
    }
}
