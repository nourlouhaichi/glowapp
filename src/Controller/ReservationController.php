<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Reservation;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/event/reservation/{id}', name: 'app_reservation', methods: ['GET'])]
    public function index(Event $event, EntityManagerInterface $entityManager): Response
    {
        $eventNbp = $event->getNbp();
        $event->setNbP($eventNbp - 1);
        $entityManager->persist($event);
        $entityManager->flush();
        $reservation = new Reservation();
        $reservation->setEmail('youssef@gmail.com');
        $reservation->setFirstName('youssef');
        $reservation->setLastName('gharbi');
        $reservation->setPhone('1232566');
        $createAt = new DateTimeImmutable();
        $reservation->setCreateAt($createAt);
        $reservation->setEvent($event);
        $entityManager->persist($reservation);
        $entityManager->flush();
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
}
