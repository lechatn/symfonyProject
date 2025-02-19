<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\GroupeCreationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

        return $this->render('group/group.html.twig', [
            'formGroup' => $form->createView(),
        ]);
    }

    public function group()
    {
        return $this->render('group/group.html.twig',[]);
    }
}