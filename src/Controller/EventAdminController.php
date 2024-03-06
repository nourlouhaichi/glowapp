<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;


#[Route('/event/admin')]
class EventAdminController extends AbstractController
{
    #[Route('/', name: 'app_event_admin_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('back/event_admin/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_event_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('image')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // Add flash message or log error
                }

                // Update the entity with the file path or filename
                $event->setImage($newFilename);
            }
            $entityManager->persist($event);
            $entityManager->flush();
            flash()->addSuccess('Ajout réussi ! ');

            return $this->redirectToRoute('app_event_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/event_admin/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_admin_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('back/event_admin/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $photoFile = $form->get('image')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // Add flash message or log error
                }

                // Update the entity with the file path or filename
                $event->setImage($newFilename);
            }
            $entityManager->flush();
            flash()->addSuccess('Modification réussite ! ');

            return $this->redirectToRoute('app_event_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/event_admin/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
            flash()->addSuccess('Suppression réussite ! ');
        }

        return $this->redirectToRoute('app_event_admin_index', [], Response::HTTP_SEE_OTHER);
    }

     #[Route('/dql', name: 'dql', methods: ['POST'])]//recherche avec dql
    public function dql(EntityManagerInterface $entityManager, Request $request, EventRepository $eventRepository): Response
    {
        $result=$eventRepository->findAll();
        $req=$entityManager->createQuery("select d from App\Entity\Event d where d.title=:n OR d.description =:n OR d.location=:n");
        if($request->isMethod('post'))
        {
            $value=$request->get('test');
            $req->setParameter('n',$value);
            $result=$req->getResult();

        }

        return $this->render('back/event_admin/index.html.twig',[
            'events'=>$result,
        ]);
    }
}