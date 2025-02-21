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
use App\Entity\ScoreHistory;

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

            if ($habitTracking->getIdGroup()) {
                $group = $habitTracking->getIdGroup();
                $group->setScore($group->getScore() + $points);
                $entityManager->persist($group);

                $scoreHistory = new ScoreHistory();
                $scoreHistory->setPoints($points);
                $scoreHistory->setDate(new \DateTime('now'));
                $scoreHistory->setIdGroup($group);
                $scoreHistory->setDescription('Task completed by ' . $user->getPseudo() . ' for ' . $points . ' points'); 
                $entityManager->persist($scoreHistory);
            }

            $entityManager->flush();
        }

        return $this->redirectToRoute('formhabits');
    }

    public function habits(ManagerRegistry $managerRegistry, TokenStorageInterface $tokenStorage): Response
    {
        $token = $tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('No token found in storage.');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not an instance of User.');
        }

        $userHabitTrackings = $managerRegistry->getRepository(HabitTracking::class)->findBy(['idUser' => $user]);
        
        $entityManager = $managerRegistry->getManager();
        $now = new \DateTime('now');

        foreach ($userHabitTrackings as $tracking) {
            $habit = $tracking->getIdHabit();
            if ($habit->getFrequency() === 'daily') {
                $createdDate = $tracking->getDate();
                $interval = $createdDate->diff($now);
                if ($interval->days > 1 && $tracking->getStatus() == 0) {
                    if($habit->getDifficulty() == 'easy') {
                        $user->setScore($user->getScore() - 5);
                    } elseif($habit->getDifficulty() == 'medium') {
                        $user->setScore($user->getScore() - 3);
                    } elseif($habit->getDifficulty() == 'hard') {
                        $user->setScore($user->getScore() - 2);
                    } else {
                        $user->setScore($user->getScore() - 8);
                    }
                    if($user->getScore() < 0) {
                        $user->setScore(0);
                    }
                    $entityManager->persist($user);

                    if ($tracking->getIdGroup()) {
                        $group = $tracking->getIdGroup();
                        if($habit->getDifficulty() == 'easy') {
                            $group->setScore($group->getScore() - 5);
                        } elseif($habit->getDifficulty() == 'medium') {
                            $group->setScore($group->getScore() - 3);
                        } elseif($habit->getDifficulty() == 'hard') {
                            $group->setScore($group->getScore() - 2);
                        } else {
                            $group->setScore($group->getScore() - 8);
                        }
                        if($group->getScore() < 0) {
                            $group->setScore(0);
                        }
                        $entityManager->persist($group);
                    }
                } elseif ($interval->days > 1 && $tracking->getStatus() == 1) {
                    $tracking->setStatus(0);
                    $entityManager->persist($tracking);
                }
            }
            if ($habit->getFrequency() === 'weekly') {
                $createdDate = $tracking->getDate();
                $interval = $createdDate->diff($now);
                if ($interval->days > 7 && $tracking->getStatus() == 0) {
                    if($habit->getDifficulty() == 'easy') {
                        $user->setScore($user->getScore() - 5);
                    } elseif($habit->getDifficulty() == 'medium') {
                        $user->setScore($user->getScore() - 3);
                    } elseif($habit->getDifficulty() == 'hard') {
                        $user->setScore($user->getScore() - 2);
                    } else {
                        $user->setScore($user->getScore() - 8);
                    }
                    if($user->getScore() < 0) {
                        $user->setScore(0);
                    }
                    $entityManager->persist($user);

                    if ($tracking->getIdGroup()) {
                        $group = $tracking->getIdGroup();
                        if($habit->getDifficulty() == 'easy') {
                            $group->setScore($group->getScore() - 5);
                        } elseif($habit->getDifficulty() == 'medium') {
                            $group->setScore($group->getScore() - 3);
                        } elseif($habit->getDifficulty() == 'hard') {
                            $group->setScore($group->getScore() - 2);
                        } else {
                            $group->setScore($group->getScore() - 8);
                        }
                        if($group->getScore() < 0) {
                            $group->setScore(0);
                        }
                        $entityManager->persist($group);
                    }
                } elseif ($interval->days > 7 && $tracking->getStatus() == 1) {
                    $tracking->setStatus(0);
                    $entityManager->persist($tracking);
                }
            }
        }

        $entityManager->flush();

        return $this->render('habits/habits.html.twig',[]);
    }
}
