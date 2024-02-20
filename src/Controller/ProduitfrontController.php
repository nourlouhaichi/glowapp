<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\Produit1Type;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitfrontController extends AbstractController
{
    #[Route('/produitfront', name: 'app_produitfront')]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produitfront/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }



   
}
