<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Route ("/", name="home")
     */
    public function accueil() : Response
    {
        return $this->render('home/home.html.twig',[
            'controller_name' => 'HomeController',
        ]);
    }
}