<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('front/home/index.html.twig', ['events' => $eventRepository->findAll(),
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/404error', name: 'error')]
    public function error(): Response
    {
        return $this->render('front/home/404error.html.twig');
    }
}
