<?php

namespace App\Controller;

use Doctrine\Common\Lexer\Token;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\User;

class ProfileController extends AbstractController
{
    public function profile(TokenStorageInterface $tokenStorage): Response
    {
        $token = $tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('No token found in storage.');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not an instance of User.');
        }

        $profilepicture = $user->getProfilePicture();
        $pseudo = $user->getPseudo();
        $email = $user->getEmail();
        $name = $user->getName();
        $firstname = $user->getFirstname();
        $score = $user->getScore();

        return $this->render('user/profile.html.twig',[
            'pseudo' => $pseudo,
            'email' => $email,
            'name' => $name,
            'firstname' => $firstname,
            'score' => $score,
        ]);
    }
}