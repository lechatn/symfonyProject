<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Group;

class GroupController extends AbstractController
{
    public function group()
    {
        return $this->render('group/group.html.twig',[]);
    }
}