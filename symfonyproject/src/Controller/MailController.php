<?php 

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Mail;
use App\Entity\User;



class MailController extends AbstractController
{
    #[Route('/mail', name: 'mail')]
    public function mail(MailerInterface $mailer, TokenStorageInterface $tokenStorage, ManagerRegistry $managerRegistry): Response
    {
        $token = $tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('No token found in storage.');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not an instance of User.');
        }

        $personnalMails = $managerRegistry->getRepository(Mail::class)->findBy(['UserMail' => $user]);

        return $this->render('mail/index.html.twig', [
            'personnalMails' => $personnalMails,
        ]);
    }
}