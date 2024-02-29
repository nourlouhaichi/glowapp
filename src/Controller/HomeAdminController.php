<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class HomeAdminController extends AbstractController
{
    #[Route('/home/admin', name: 'app_home_admin')]
    public function index(UserRepository $userRepository): Response
    {
        $nbFemales = $userRepository->countFemales();
        $nbMales = $userRepository->countMales();
        $nbBan = $userRepository->countBannedUsers();
        $nbNoBan = $userRepository->countNoBannedUsers();

        $userEvolutionData = $userRepository->getUserEvolutionData();
        $dates = [];
        $userCounts = [];

        foreach ($userEvolutionData as $data) {
            $dates[] = $data['date'];
            $userCounts[] = $data['userCount'];
        }
        
        $rolesDistribution = $userRepository->getRolesDistribution();

        return $this->render('back/home_admin/index.html.twig', [
        'nbFemales' => $nbFemales,
        'nbMales' => $nbMales,
        'nbBan' => $nbBan,
        'nbNoBan' => $nbNoBan,
        'dates' => json_encode($dates),
        'userCounts' => json_encode($userCounts),
        'rolesDistribution' => $rolesDistribution,
        
        ]);
        // return $this->render('back/home_admin/index.html.twig', [
        //     'controller_name' => 'HomeAdminController',
        // ]);
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

            return $this->redirectToRoute('profile_admin');
        }

        return $this->renderForm('back/home_admin/editprofileadmin.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/home/admin/profile/editpass', name: 'profile_admin_editpass')]
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

                return $this->redirectToRoute('profile_admin');
            }else{
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }

        return $this->render('back/home_admin/editprofilepass.html.twig');
    }
}
