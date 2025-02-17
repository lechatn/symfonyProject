<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\HabitFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Habits;
use Doctrine\Persistence\ManagerRegistry;

class HabitsController extends AbstractController
{
    #[Route('/formhabits', name: 'formhabits')]
    public function index(Request $request, ManagerRegistry $managerRegistry)
    {
        $habits = new Habits();
        $habitsForm = $this->createForm(HabitFormType::class, $habits);

        $habitsForm->handleRequest($request);   

        if ($habitsForm->isSubmitted() && $habitsForm->isValid())
        {
            $entityManager = $managerRegistry->getManager();
            $habit = $habitsForm->getData();

            $entityManager->persist($habit);
            $entityManager->flush();
        }

        return $this->render('habits/index.html.twig', [
            'formHabits' => $habitsForm->createView()
        ]);
    }
}
