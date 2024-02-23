<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('home/admin/user')]
class UserAdminController extends AbstractController
{
    // #[Route('/', name: 'app_user_admin_index', methods: ['GET'])]
    // public function index(UserRepository $userRepository): Response
    // {
    //     $currentUser = $this->getUser();
    //     return $this->render('back/user_admin/index.html.twig', [
    //         'users' => $userRepository->findAllUsersWithSpecificFieldsExceptCurrentUser($currentUser),
    //     ]);
    // }

    #[Route('/', name: 'app_user_admin_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $currentUser = $this->getUser();
        $type = $request->query->get('type');

        switch ($type) {
            case 'all':
                $users = $userRepository->findAllUsersWithSpecificFieldsExceptCurrentUser($currentUser);
                break;
            case 'admins':
                $users = $userRepository->findAllAdminsWithSpecificFieldsExceptCurrentUser($currentUser);
                break;
            case 'members':
                $users = $userRepository->findAllMembersWithSpecificFieldsExceptCurrentUser($currentUser);
                break;
            case 'banned':
                $users = $userRepository->findAllBannedWithSpecificFieldsExceptCurrentUser($currentUser);
                break;
            case 'females':
                $users = $userRepository->findAllFemalesWithSpecificFieldsExceptCurrentUser($currentUser);
                break;
            case 'males':
                $users = $userRepository->findAllMalesWithSpecificFieldsExceptCurrentUser($currentUser);
                break;
            default:
                $users = $userRepository->findAllUsersWithSpecificFieldsExceptCurrentUser($currentUser);
    }

        return $this->render('back/user_admin/index.html.twig', [
        'users' => $users,
        'type' => $type,
    ]);
}


    // #[Route('/new', name: 'app_user_admin_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_user_admin_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('back/user_admin/new.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/new', name: 'app_user_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $plainPassword = $form->get('password')->getData();
            
            $hashedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user_admin/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/{cin}', name: 'app_user_admin_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('back/user_admin/show.html.twig', [
            'user' => $user,
        ]);
    }

    
    #[Route('/{cin}/edit', name: 'app_user_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user_admin/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/{cin}', name: 'app_user_admin_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getCin(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_admin_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('home/admin/ban/{cin}', name: 'admin_ban_user')]
    public function banUser($cin): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($cin);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id '.$cin);
        }

        $user->setIsBanned(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_admin_index');
    }


    #[Route('home/admin/unban/{cin}', name: 'admin_unban_user')]
    public function unbanUser($cin): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($cin);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id '.$cin);
        }

        $user->setIsBanned(false);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_admin_index');
    }
}
