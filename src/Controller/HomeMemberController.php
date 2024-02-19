<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;

class HomeMemberController extends AbstractController
{
    #[Route('/home/member', name: 'app_home_member')]
    public function index(): Response
    {
        return $this->render('front/home_member/indexmember.html.twig', [
            'controller_name' => 'HomeMemberController',
        ]);
    }

    #[Route('/home/member/profile', name: 'profile_member')]
    public function showMember()
    {
        return $this->render("front/home_member/showmember.html.twig");
    }

    #[Route('/home/member/profile/edit', name: 'profile_member_edit')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profile_member');
        }

        return $this->renderForm('front/home_member/editprofilemember.html.twig', [
            'form' => $form,
        ]);
    }

}
