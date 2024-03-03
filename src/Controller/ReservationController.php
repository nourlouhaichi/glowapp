<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Reservation;
/*use BaconQrCode\Encoder\QrCode;*/
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;
use Endroid\QrCode\Writer\PngWriter;

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
        $reservation->setEmail('sam@gmail.com');
        $reservation->setFirstName('sam');
        $reservation->setLastName('jones');
        $reservation->setPhone('1232566');
        $createAt = new DateTimeImmutable();
        $reservation->setCreateAt($createAt);
        $reservation->setEvent($event);
        $qrCode = new QrCode('sara  '.$event->getTitle());
        $qrCode->setSize(300);
        $writer = new PngWriter();
        $result = $writer->write($qrCode) ;
        /*$reponse = new QrCodeResponse;*/
        $reservation->setQrCode($result->getDataUri());
        $entityManager->persist($reservation);
        $entityManager->flush();
       
        return $this->render('front/reservation/index.html.twig', [
            'Reservation' => $reservation,
        ]);
    }



    
    

    
}
