<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Event;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;


class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('front/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/event/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event,CategoryRepository $categoryRepository,EventRepository $eventRepository): Response
    {
      
        return $this->render('front/event/show.html.twig', [
            'event' => $event,
            'category' => $categoryRepository->findAll(),
            'events' => $eventRepository->findBy(array(),array('id'=>'DESC'),2),

        ]);
    }
  
 
    
}
