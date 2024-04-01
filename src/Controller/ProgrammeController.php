<?php

namespace App\Controller;
use App\Repository\RatingRepository;
use App\Entity\CommentProg;
use App\Form\CommentFormType;
use App\Entity\Programme;
use App\Entity\Reservation;
use App\Entity\Rating;
use App\Entity\Categorypro;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\ProgrammeType;
use App\Form\RatingType;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
#[Route('/programme')]
class ProgrammeController extends AbstractController
{


    #[Route('/new', name: 'app_programme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $programme = new Programme();
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if($image){
                $newFilename = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('EventImage_directory'),
                    $newFilename
                );
                $programme->setImage($newFilename);
            }
            $programme->setUserprog($this->getUser());
            $entityManager->persist($programme);
            $entityManager->flush();

            return $this->redirectToRoute('app_programme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/programme/new.html.twig', [
            'programme' => $programme,
            'form' => $form,
        ]);
    }




    #[Route('/', name: 'app_programme_index', methods: ['GET'])]
    public function index(ProgrammeRepository $programmeRepository, PaginatorInterface $paginator, Request $request): Response
    {
    
        $categories = $this->getDoctrine()->getRepository(Categorypro::class)->findAll();
    
        $query = $programmeRepository->findAll(); // Assuming you have a method in your repository to get the query
        
        // Paginate the query results
        $programmes = $paginator->paginate(
            $query, // Query to paginate
            $request->query->getInt('page', 1), // Current page number
            3 // Items per page
        );
    
        return $this->render('front/programme/index.html.twig', [
            'programmes' => $programmes,
            'categories' => $categories,
            'pagination_template' => 'pagination.html.twig',
        ]);
    }
    




    // #[Route('/', name: 'app_programme_index', methods: ['GET'])]
    // public function index(ProgrammeRepository $programmeRepository): Response
    // {
    //     return $this->render('front/programme/index.html.twig', [
    //         'programmes' => $programmeRepository->findAll(),
    //     ]);
    // }


//     #[Route('/category/{categoryId}/programs', name: 'get_programs_by_category', methods: ['GET'])]
// public function getProgramsByCategory(int $categoryId): JsonResponse
// {
//     $category = $this->getDoctrine()->getRepository(Categorypro::class)->find($categoryId);
//     $programs = $category->getPrograms();

//     // Convert programs to an array or format it as needed
//     $programsArray = [];
//     foreach ($programs as $program) {
//         $programsArray[] = [
//             'id' => $program->getId(),
//             'title' => $program->getTitrePro(),
//             // Add other program properties as needed
//         ];
//     }

//     return new JsonResponse($programsArray);
// }





#[Route('/programme/{id}', name: 'app_programme_show', methods: ['GET', 'POST'])]
public function show(Request $request, Programme $programme, RatingRepository $ratingRepository, EntityManagerInterface $entityManager): Response
{
    if (!$programme) {
        throw $this->createNotFoundException('The programme does not exist');
    }

    $averageRating = $ratingRepository->calculateAverageRating($programme);
    $comment = new CommentProg();
    $commentForm = $this->createForm(CommentFormType::class, $comment);
    $commentForm->handleRequest($request);
    $error = null;

    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
        $comment->setProgComment($programme);
        $comment->setCreatedAt(new \DateTime());
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_programme_show', ['id' => $programme->getId()]);
    }

    return $this->render('front/programme/show.html.twig', [
        'programme' => $programme,
        'averageRating' => $averageRating,
        'commentForm' => $commentForm->createView(),
        'error' => $error,
    ]);
}





    
  
#[Route('/category/{categoryId}', name: 'show_programs_by_category')]
public function showProgramsByCategory(Request $request, int $categoryId): Response
{
    if ($request->isXmlHttpRequest()) {
        $category = $this->getDoctrine()->getRepository(Categorypro::class)->find($categoryId);
        $programs = $category->getPrograms();

        $programsArray = []; // Initialize an array to hold program data

        // Convert your programs into a simple array that can be JSON-encoded
        foreach ($programs as $program) {
            $programsArray[] = [
                'id' => $program->getId(),
                'title' => $program->getTitrePro(),
                'plan' => $program->getPlanPro(),
                'date' => $program->getDatePro()->format('Y-m-d'),
                'image' => $program->getImage(),
                // ... Add other fields as necessary
            ];
        }

        return new JsonResponse($programsArray); // Return a JSON response
    }

    // ... (Existing code for non-AJAX request)
}





   


    #[Route('/{id}/rate', name: 'app_programme_rate', methods: ['POST'])]
public function rate(Request $request, EntityManagerInterface $entityManager, ProgrammeRepository $programmeRepository, RatingRepository $ratingRepository, int $id): Response
{
    try {
        $data = json_decode($request->getContent(), true);
        $ratingValue = $data['rating'];
        $programme = $programmeRepository->find($id);

        if (!$programme) {
            return $this->json(['error' => 'Programme not found'], Response::HTTP_NOT_FOUND);
        }

        // Check if CSRF token is valid.
        if (!$this->isCsrfTokenValid('rate_programme', $data['_token'])) {
            return $this->json(['error' => 'Invalid CSRF token'], Response::HTTP_FORBIDDEN);
        }

        // Create a new Rating object, set its value and programme, and persist it
        $rating = new Rating();
        $rating->setRatingValue($ratingValue);
        $rating->setPrograme($programme);
        $entityManager->persist($rating);
        $entityManager->flush();

        // Recalculate the average rating
        $averageRating = $ratingRepository->calculateAverageRating($programme);

        // Respond with the new average rating
        return $this->json(['averageRating' => $averageRating]);
    } catch (\Exception $e) {
        // Log the exception to the Symfony log
        $logger = $this->get('logger');
        $logger->error(sprintf("An error occurred when trying to rate a programme: %s", $e->getMessage()));

        // Return a generic error message to the client
        return $this->json(['error' => 'An unexpected error occurred.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}






    #[Route('/{id}/edit', name: 'app_programme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if($image){
                $newFilename = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('EventImage_directory'),
                    $newFilename
                );
                $programme->setImage($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_programme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/programme/edit.html.twig', [
            'programme' => $programme,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_programme_delete', methods: ['POST'])]
    public function delete(Request $request, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$programme->getId(), $request->request->get('_token'))) {
            $entityManager->remove($programme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_programme_index', [], Response::HTTP_SEE_OTHER);
    }



   
/**
 * @Route("/search/programmes", name="search_programmes")
 */
public function searchProgrammes(Request $request, ProgrammeRepository $programmeRepository): JsonResponse
{
    $keyword = $request->query->get('keyword');
    $programmes = $programmeRepository->findByKeyword($keyword);

    // Map the programmes to the structure expected by your JavaScript function
    $programmesArray = array_map(function ($programme) {
        return [
            'id' => $programme->getId(),
            'image' => $programme->getImage(), // Make sure this is the correct field name and it contains just the filename
            'titrePro' => $programme->getTitrePro(),
            'planPro' => $programme->getPlanPro(),
            'datePro' => $programme->getDatePro() ? $programme->getDatePro()->format('Y-m-d') : null,
        ];
    }, $programmes);

    return $this->json($programmesArray);
}

    
}
