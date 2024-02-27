<?php

namespace App\Controller;
use App\Form\SearchType;
use App\Entity\Programme;
use App\Form\ProgrammeType;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/programme_admin')]
class Programme_adminController extends AbstractController
{
    #[Route('/', name: 'app_programme_admin_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ProgrammeRepository $programmeRepository): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
    
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $criteria = $form->getData();
            $programmes = $programmeRepository->findBySearchCriteria($criteria);
        } else {
            // If the form is not submitted, fetch all programs
            $programmes = $programmeRepository->findAll();
        }
    
        return $this->render('back/programme/index.html.twig', [
            'search_form' => $form->createView(),
            'programmes' => $programmes,
        ]);
    }

    #[Route('/new', name: 'app_programme_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $programme = new Programme();
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($programme);
            $entityManager->flush();

            return $this->redirectToRoute('app_programme_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/programme/new.html.twig', [
            'programme' => $programme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_programme_admin_show', methods: ['GET'])]
    public function show(Programme $programme): Response
    {
        return $this->render('back/programme/show.html.twig', [
            'programme' => $programme,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_programme_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_programme_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/programme/edit.html.twig', [
            'programme' => $programme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_programme_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$programme->getId(), $request->request->get('_token'))) {
            $entityManager->remove($programme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_programme_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
