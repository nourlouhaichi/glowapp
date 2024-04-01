<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Repository\EventRepository;

class HomeMemberController extends AbstractController
{
    #[Route('/home/member', name: 'app_home_member')]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('front/home_member/indexmember.html.twig', ['events' => $eventRepository->findAll(),
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
            $profilePictureFile = $form->get('profilePicture')->getData();

            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

                try {
                    $profilePictureFile->move(
                        $this->getParameter('profile_pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // Add flash message or log error
                }
                $user->setProfilePicture($newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profile_member');
        }

        return $this->renderForm('front/home_member/editprofilemember.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/home/member/profile/editpass', name: 'profile_member_editpass')]
    public function editProfilePass(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        if($request->isMethod('POST')){

            $user = $this->getUser();
            
            // On vérifie si les 2 mots de passe sont identiques
            if($request->request->get('pass') == $request->request->get('pass2')){

                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('message', 'Mot de passe mis à jour avec succès');

                return $this->redirectToRoute('profile_member');
            }else{
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }

        return $this->render('front/home_member/editprofilepass.html.twig');
    }

}
