<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Publication;
use App\Form\PublicationType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommentRepository;

class PublicationsController extends AbstractController
{
    #[Route('/publications', name: 'app_publications')]
    public function index(PublicationRepository $PublicationRepository): Response
    {
        $publication=$PublicationRepository->findAll();
        return $this->render('front/publications/index.html.twig', [
            'publications'=>$publication
        ]);
    }

        #[Route('/adminpublications', name: 'backapp_publications')]
        public function indexback(PublicationRepository $PublicationRepository): Response
        {
            $publication=$PublicationRepository->findAll();
            return $this->render('back/publications/index.html.twig', [
                'publications'=>$publication
            ]);
        }
    
    #[Route('/new', name: 'app_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publication->setDatecrP(new DateTime());
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('app_publications', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/publications/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publication_show', methods: ['GET', 'POST'])]
    public function show(Publication $publication,Request $request,EntityManagerInterface $em): Response
    {
        $comment=new Comment();
        $form= $this->createFormBuilder($comment)->add("contenue")->add("submit",SubmitType::class)->getForm();
        $comment->setDatecr(new DateTime());
        $comment->setPublication($publication);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($comment);
            $em->flush();
        }
        return $this->render('front/publications/show.html.twig', [
            'publications' => $publication,
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_publications', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/publications/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/adminedit', name: 'backapp_publication_edit', methods: ['GET', 'POST'])]
    public function backedit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('backapp_publications', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/publications/backedit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publications', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/delete', name: 'backapp_publication_delete', methods: ['POST'])]
    public function backdelete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backapp_publications', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/delete_comment/{commentId}', name: 'app_publication_delete_comment', methods: ['POST'])]
    public function deleteComment(Request $request, Publication $publication, CommentRepository $commentRepository, EntityManagerInterface $entityManager, $commentId): Response
    {
        // Find the comment to delete
        $comment = $commentRepository->find($commentId);

        // Check if the comment belongs to the publication
        if ($comment && $comment->getPublication() === $publication) {
            // Remove the comment
            $entityManager->remove($comment);
            $entityManager->flush();

            // Optionally, you can add a success flash message here
        } else {
            // Optionally, you can add a failure flash message here if the comment does not belong to the publication
        }

        // Redirect back to the publication page or wherever appropriate
        return $this->redirectToRoute('app_publication_show', ['id' => $publication->getId()]);
    }
}
    
