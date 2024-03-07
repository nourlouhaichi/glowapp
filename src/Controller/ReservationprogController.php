<?php


namespace App\Controller;
use App\Repository\RatingRepository;
use App\Entity\CommentProg;
use App\Form\CommentFormType;
use App\Form\RatingType;
use App\Entity\Rating;
use App\Entity\Programme;
use App\Entity\Reservationprog;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\False_;
use ProxyManager\Exception\ExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ReservationprogController extends AbstractController
{
    /**
     * @Route("/reservationprog", name="reservationprog")
     */
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationprogController',
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
    $reservationprog = new Reservationprog();
    $averageRating = $ratingRepository->calculateAverageRating($programme);
    $comment = new CommentProg();
    $commentForm = $this->createForm(CommentFormType::class, $comment);
    $error = null; // Initialize error message variable

    if ($req->isMethod("post")) {
        $nbredeticketDemandé = (int)($req->get('nbrplace'));
        
        if ($nbredeticketDemandé <= $programme->getPlaceDispo()) {
            $reservationprog->setIdprog($programme);
            $reservationprog->setNbrPlace($nbredeticketDemandé);
            $reservationprog->setApprouve(0);
            $programme->setPlaceDispo($programme->getPlaceDispo() - $nbredeticketDemandé);

            try {
                $entityManager->persist($reservationprog);
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
     * @Route("/listreservationprog/{id}", name="afficherReservation")
     */
    public function listReservationByProg($id, EntityManagerInterface $entityManager)
    {
        $prog = $entityManager->getRepository(Programme::class)->find($id);
        $listReservationprog = $entityManager->getRepository(Reservationprog::class)->findBy(array('idprog' => $prog));
        $listProgsBack = $entityManager->getRepository(Programme::class)->findAll();

        return $this->render('reservation/listReservation.html.twig', [
            'reservations' => $listReservationprog,
            'progs' => $listProgsBack,
        ]);
    }






    /**
     * @Route("/listreser", name="listReservationBack")
     */
    public function listReservationprog(EntityManagerInterface $entityManager)
    {
        $listReservationprog = $entityManager->getRepository(Reservationprog::class)->findAll();

        return $this->render('reservation/listReservationBack.html.twig', array('reservations' => $listReservationprog));
    }





    /**
     * @param int $id
     * @Route("/approuverReservation/{id}", name="approuverReservation")
     */
    public function approuverReservation(int $id, EntityManagerInterface $entityManager)
    {
        $reservationprog = $entityManager->getRepository(Reservationprog::class)->find($id);

        if (!$reservationprog) {
            throw $this->createNotFoundException('Reservation not found for id ' . $id);
        }

        $reservationprog->setApprouve(true);

        $entityManager->flush();

        return $this->redirectToRoute('listReservationBack', ['id' => $id]);
    }
}

