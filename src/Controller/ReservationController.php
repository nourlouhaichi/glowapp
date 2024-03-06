<?php

namespace App\Controller;
use App\Repository\RatingRepository;
use App\Entity\CommentProg;
use App\Form\CommentFormType;
use App\Form\RatingType;
use App\Entity\Rating;
use App\Entity\Programme;
use App\Entity\Reservation;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\False_;
use ProxyManager\Exception\ExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }









    // /**
    //  * @Route("/reserverProg/{id}", name="reserverProg")
    //  */
    
    // public function reserverProg(Request $req, $id, EntityManagerInterface $entityManager, RatingRepository $ratingRepository)
    // {
    //     $programme = $entityManager->getRepository(Programme::class)->find($id);
    //     $reservation = new Reservation();
    //     $averageRating = $ratingRepository->calculateAverageRating($programme);
    //     $comment = new CommentProg();
    //     $commentForm = $this->createForm(CommentFormType::class, $comment);

    //     if ($req->isMethod("post")) {
    //         $reservation->setIdprog($programme);
    //         $nbredeticketDemandé = (int)($req->get('nbrplace'));
    //         $reservation->setApprouve(0);


    //         if ($nbredeticketDemandé <= $programme->getPlaceDispo()) {
    //             $reservation->setNbrPlace($nbredeticketDemandé);
    //             $programme->setPlaceDispo(($programme->getPlaceDispo()) - (int)($req->get('nbrplace')));
    //         } else {
    //             return $this->redirectToRoute('reserverProg', array('id' => $id));
    //         }
    //         try {
    //             $entityManager->persist($reservation);
    //             $entityManager->flush();
    //             return $this->redirectToRoute('app_programme_show', ['id' => $id]);
    //         } catch (ExceptionInterface $e) {
    //         }
    //     }

    //     return $this->render('front/programme/show.html.twig', [
    //     'programme' => $programme,
    //     'averageRating' => $averageRating,
    //     'commentForm' => $commentForm->createView(),
    // ]);
    //}

    /**
 * @Route("/reserverProg/{id}", name="reserverProg")
 */
public function reserverProg(Request $req, $id, EntityManagerInterface $entityManager, RatingRepository $ratingRepository)
{
    $programme = $entityManager->getRepository(Programme::class)->find($id);
    $reservation = new Reservation();
    $averageRating = $ratingRepository->calculateAverageRating($programme);
    $comment = new CommentProg();
    $commentForm = $this->createForm(CommentFormType::class, $comment);
    $error = null; // Initialize error message variable

    if ($req->isMethod("post")) {
        $nbredeticketDemandé = (int)($req->get('nbrplace'));
        
        if ($nbredeticketDemandé <= $programme->getPlaceDispo()) {
            $reservation->setIdprog($programme);
            $reservation->setNbrPlace($nbredeticketDemandé);
            $reservation->setApprouve(0);
            $programme->setPlaceDispo($programme->getPlaceDispo() - $nbredeticketDemandé);

            try {
                $entityManager->persist($reservation);
                $entityManager->flush();
                return $this->redirectToRoute('app_programme_show', ['id' => $id]);
            } catch (ExceptionInterface $e) {
                // Handle exception, possibly set an error message
            }
        } else {
            // Set an error message when nbrplace is greater than placeDispo
            $error = "The number of requested tickets exceeds the available spots.";
        }
    }

    return $this->render('front/programme/show.html.twig', [
        'programme' => $programme,
        'averageRating' => $averageRating,
        'commentForm' => $commentForm->createView(),
        'error' => $error, // Pass the error message to your template
    ]);
}









    /**
     * @Route("/listreservation/{id}", name="afficherReservation")
     */
    public function listReservationByProg($id, EntityManagerInterface $entityManager)
    {
        $prog = $entityManager->getRepository(Programme::class)->find($id);
        $listReservation = $entityManager->getRepository(Reservation::class)->findBy(array('idprog' => $prog));
        $listProgsBack = $entityManager->getRepository(Programme::class)->findAll();

        return $this->render('reservation/listReservation.html.twig', [
            'reservations' => $listReservation,
            'progs' => $listProgsBack,
        ]);
    }







    /**
     * @Route("/listreser", name="listReservationBack")
     */
    public function listReservation(EntityManagerInterface $entityManager)
    {
        $listReservation = $entityManager->getRepository(Reservation::class)->findAll();

        return $this->render('reservation/listReservationBack.html.twig', array('reservations' => $listReservation));
    }






    /**
     * @param int $id
     * @Route("/approuverReservation/{id}", name="approuverReservation")
     */
    public function approuverReservation(int $id, EntityManagerInterface $entityManager)
    {
        $reservation = $entityManager->getRepository(Reservation::class)->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException('Reservation not found for id ' . $id);
        }

        $reservation->setApprouve(true);

        $entityManager->flush();

        return $this->redirectToRoute('listReservationBack', ['id' => $id]);
    }
}
