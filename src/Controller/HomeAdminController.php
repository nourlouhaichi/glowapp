<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;

class HomeAdminController extends AbstractController
{
    #[Route('/home/admin', name: 'app_home_admin')]
    public function index(): Response
    {
        return $this->render('back/home_admin/index.html.twig', [
            'controller_name' => 'HomeAdminController',
        ]);
    }

    #[Route('/home/admin/profile', name: 'profile_admin')]
    public function showAdmin()
    {
        return $this->render("back/home_admin/showadmin.html.twig");
    }

    #[Route('/home/admin/profile/edit', name: 'profile_admin_edit')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profile_admin');
        }

        return $this->renderForm('back/home_admin/editprofileadmin.html.twig', [
            'form' => $form,
        ]);
    }
}
