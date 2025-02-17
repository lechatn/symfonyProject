<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\HabitFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Habits;

class HabitsController extends AbstractController
{
    #[Route('/formhabits', name: 'formhabits')]
    public function index(Request $request)
    {
        $habits = new Habits();
        $habitsForm = $this->createForm(HabitFormType::class, $habits);

        $habitsForm->handleRequest($request);   

        if ($habitsForm->isSubmitted() && $habitsForm->isValid())
        {
            dump($request->request->all());
        }

        return $this->render('habits/index.html.twig', [
            'formHabits' => $habitsForm->createView()
        ]);
    }
}
