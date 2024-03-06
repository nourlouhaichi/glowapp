<?php

namespace App\Controller;

use App\Entity\Categorypro;
use App\Form\CategoryproType;
use App\Repository\CategoryproRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorypro')]
class CategoryproController extends AbstractController
{
    #[Route('/', name: 'app_categorypro_index', methods: ['GET'])]
    public function index(CategoryproRepository $categoryproRepository): Response
    {

        return $this->render('categorypro/index.html.twig', [
            'categorypros' => $categoryproRepository->findAll(),
        ]);
    }

    #[Route('/category/{categoryId}', name: 'show_programs_by_category', methods: ['GET'])]
    public function showProgramsByCategory(Request $request, int $categoryId): Response {
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

    #[Route('/new', name: 'app_categorypro_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorypro = new Categorypro();
        $form = $this->createForm(CategoryproType::class, $categorypro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorypro);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorypro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorypro/new.html.twig', [
            'categorypro' => $categorypro,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorypro_show', methods: ['GET'])]
    public function show(Categorypro $categorypro): Response
    {
        return $this->render('categorypro/show.html.twig', [
            'categorypro' => $categorypro,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorypro_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorypro $categorypro, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryproType::class, $categorypro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorypro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorypro/edit.html.twig', [
            'categorypro' => $categorypro,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorypro_delete', methods: ['POST'])]
    public function delete(Request $request, Categorypro $categorypro, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorypro->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorypro);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorypro_index', [], Response::HTTP_SEE_OTHER);
    }
}
