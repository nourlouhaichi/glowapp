<?php

namespace App\Controller;

use App\Entity\Objectif;
use App\Form\ObjectifType;
use App\Repository\ObjectifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Objectif_admin')]
class Objectif_adminController extends AbstractController
{
    #[Route('/', name: 'app_Objectif_admin_index', methods: ['GET'])]
    public function index(ObjectifRepository $objectifRepository): Response
    {
        return $this->render('back/Objectif_admin/index.html.twig', [
            'objectifs' => $objectifRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_Objectif_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objectif = new Objectif();
        $form = $this->createForm(ObjectifType::class, $objectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($objectif);
            $entityManager->flush();

            return $this->redirectToRoute('app_objectif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/objectif_admin/new.html.twig', [
            'objectif' => $objectif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_Objectif_admin_show', methods: ['GET'])]
    public function show(Objectif $objectif): Response
    {
        return $this->render('back/objectif_admin/show.html.twig', [
            'objectif' => $objectif,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_Objectif_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Objectif $objectif, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ObjectifType::class, $objectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_Objectif_admin_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('back/objectif_admin/edit.html.twig', [
            'objectif' => $objectif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_Objectif_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Objectif $objectif, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$objectif->getId(), $request->request->get('_token'))) {
            $entityManager->remove($objectif);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_Objectif_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
