<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeAdminController extends AbstractController
{
    #[Route('/admin', name: 'app_home_admin')]
    public function index(): Response
    {
        return $this->render('back/home_admin/index.html.twig', [
            'controller_name' => 'HomeAdminController',
        ]);
    }
    // #[Route('/Objectif_admin', name: 'app_objectif_admin')]
    // public function objectif_admin(): Response
    // {
    //     return $this->render('back/objectif_admin/index.html.twig', [
    //         'controller_name' => 'HomeAdminController',
    //     ]);
    // }
}
