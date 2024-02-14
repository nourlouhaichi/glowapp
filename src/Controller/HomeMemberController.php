<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

}
