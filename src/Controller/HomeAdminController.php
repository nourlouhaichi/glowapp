<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeAdminController extends AbstractController
{
    #[Route('/home/admin', name: 'app_home_admin')]
    public function index(): Response
    {
        return $this->render('back/home_admin/index.html.twig', [
            'controller_name' => 'HomeAdminController',
        ]);
    }

    #[Route('/home/admin/profile', name: 'profile_admin')]
    public function showAdmin()
    {
        return $this->render("back/home_admin/showadmin.html.twig");
    }
}
