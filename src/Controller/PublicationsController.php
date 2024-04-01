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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Images;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
#[Route('/publication')]
class PublicationsController extends AbstractController
{
    #[Route('/publications', name: 'app_publications')]
    public function index(PublicationRepository $PublicationRepository,Request $request,PaginatorInterface $paginator): Response
    {
        // 
        $publication=$PublicationRepository->findAll();
        $publication = $paginator->paginate($publication, $request->query->getInt('page', 1),9);
        
        return $this->render('front/publications/index.html.twig', [
            'publications'=>$publication
            
        ]);
    }
    #[Route('/Admin', name: 'Admin_page')]
    public function indexadmin(PublicationRepository $PublicationRepository): Response
    {
        $publication=$PublicationRepository->findAll();
        return $this->render('back/home_admin/index.html.twig', [
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
    public function new(Request $request, EntityManagerInterface $entityManager ,FlashBagInterface $flashBag): Response
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication)->add('images', FileType::class,['label'=>false,'multiple'=>true,'mapped'=>false,'required'=>false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $flashBag->add("success","publication added !");
            $images=$form->get('images')->getData();
            foreach($images as $image){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $img = new images();
                $img->setName($fichier);
                $publication->addImage($img);
            }
            $publication->setDatecrP(new DateTime());
            $publication->setUserpub($this->getUser());
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('app_publications', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/publications/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/adminnew', name: 'backapp_publication_new', methods: ['GET', 'POST'])]
    public function backnew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication)->add('images', FileType::class,['label'=>false,'multiple'=>true,'mapped'=>false,'required'=>false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images=$form->get('images')->getData();
            foreach($images as $image){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $img = new images();
                $img->setName($fichier);
                $publication->addImage($img);
            }
            $publication->setDatecrP(new DateTime());
            $publication->setUserpub($this->getUser());
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('backapp_publications', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/publications/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publication_show', methods: ['GET', 'POST'])]
    public function show(Publication $publication,Request $request,EntityManagerInterface $em,FlashBagInterface $flashBag): Response
    {
        $comment=new Comment();
        $form= $this->createFormBuilder($comment)->add("contenue")->add("Add",SubmitType::class)->getForm();
        $comment->setDatecr(new DateTime());
        $comment->setPublication($publication);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $flashBag->add("success","comment added ! ");
            $em->persist($comment);
            $em->flush();
        }
        return $this->render('front/publications/show.html.twig', [
            'publications' => $publication,
            'form' => $form->createView()
        ]);
    }
    #[Route('/{id}/edit', name: 'app_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager,FlashBagInterface $flashBag): Response
    {
        $form = $this->createForm(PublicationType::class, $publication)->add('images', FileType::class,['label'=>false,'multiple'=>true,'mapped'=>false,'required'=>false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $flashBag->add("success","publication edited !");
            $images=$form->get('images')->getData();
            foreach($images as $image){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $img = new images();
                $img->setName($fichier);
                $publication->addImage($img);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_publications', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/publications/editpub.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/adminedit', name: 'backapp_publication_edit', methods: ['GET', 'POST'])]
    public function backedit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicationType::class, $publication)->add('images', FileType::class,['label'=>false,'multiple'=>true,'mapped'=>false,'required'=>false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images=$form->get('images')->getData();
            foreach($images as $image){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $img = new images();
                $img->setName($fichier);
                $publication->addImage($img);
            }
            $entityManager->flush();

            return $this->redirectToRoute('backapp_publications', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/publications/backedit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

   /* #[Route('/delete/{id}', name: 'app_publicationdelete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publications', [], Response::HTTP_SEE_OTHER);
    }*/
    /*#[Route('/{id}/delete', name: 'backapp_publication_delete', methods: ['POST'])]
    public function backdelete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backapp_publications', [], Response::HTTP_SEE_OTHER);
    }*/

    #[Route('/deletecm/{id}', name: 'app_publicationcomdelete', methods: ['POST'])]
    public function deletecm(Request $request, Publication $publication, EntityManagerInterface $entityManager, CommentRepository $commentRepository,FlashBagInterface $flashBag): Response
    {
        $comments = $commentRepository->findBy(['publication' => $publication]);
        
        foreach ($comments as $comment) {
            $entityManager->remove($comment);
            
        }
        $flashBag->add("error","publication deleted !");
        $entityManager->remove($publication);
        $entityManager->flush();
        
    
        return $this->redirectToRoute('app_publications', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/admindeletecm/{id}', name: 'backapp_publicationcomdelete', methods: ['GET','POST'])]
    public function deleteadcm(Request $request, Publication $publication, EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy(['publication' => $publication]);

        foreach ($comments as $comment) {
            $entityManager->remove($comment);
        }
        $entityManager->remove($publication);
        $entityManager->flush();
    
        return $this->redirectToRoute('backapp_publications', [], Response::HTTP_SEE_OTHER);
    }
}