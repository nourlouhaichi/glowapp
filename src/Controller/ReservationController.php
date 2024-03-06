<?php

namespace App\Controller;

use ApiPlatform\Core\OpenApi\Options;
use App\Entity\Event;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
/*use BaconQrCode\Encoder\QrCode;*/
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\HttpFoundation\Request;

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

    #[Route('/print/{id}', name: 'app_Reservation_print')]
    public function printReservations(ReservationRepository $reservationRepository,$id)
    {

        $reservation = $reservationRepository->findOneById($id);
        $qrCode = $reservation->getQrCode();

        $data = [
            'imageSrc'  => $qrCode,
            'name'         => 'John Doe',
            'address'      => 'USA',
            'mobileNumber' => '000000000',
            'email'        => 'john.doe@email.com'
        ];
        $html =  $this->renderView('front/reservation/print.html.twig', ['data' => $data]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );

    }




    
    

    
}
