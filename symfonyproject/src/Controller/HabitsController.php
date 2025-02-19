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
        $habitsForm = $this->createForm(HabitFormType::class, $habits);

        $habitsForm->handleRequest($request);   

        $habitTracking = new HabitTracking();

        if ($habitsForm->isSubmitted() && $habitsForm->isValid())
        {
            $entityManager = $managerRegistry->getManager();
            $habit = $habitsForm->getData();

            $habitTracking = new HabitTracking();
            $habitTracking->setIdHabit($habit);
            $habitTracking->setIdUser($this->getUser());
            $habitTracking->setStatus(false);
            $habitTracking->setDate(new \DateTime('now'));

            $entityManager->persist($habit);
            $entityManager->persist($habitTracking);
            $entityManager->flush();
        }

        $token = $tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('No token found in storage.');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not an instance of User.');
        }

        $userHabitTrackings = $managerRegistry->getRepository(HabitTracking::class)->findBy(['idUser' => $user]);

        // Extraire les objets Habits associés
        $userHabits = [];
        foreach ($userHabitTrackings as $tracking) {
            $userHabits[] = $tracking->getIdHabit(); // Récupère l'objet Habits
        }
        
        
        return $this->render('habits/habits.html.twig', [
            'formHabits' => $habitsForm->createView(),
            'dataUser' => $userHabits,
            'userTrackings' => $userHabitTrackings
        ]);
    }

    #[Route('/complete-task/{id}', name: 'complete_task', methods: ['POST'])]
    public function completeTask(int $id, ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getManager();
        $habitTracking = $entityManager->getRepository(HabitTracking::class)->find($id);

        if ($habitTracking) {
            $habitTracking->setStatus(true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('formhabits');
    }

    public function habits()
    {
        return $this->render('habits/habits.html.twig',[]);
    }
}
