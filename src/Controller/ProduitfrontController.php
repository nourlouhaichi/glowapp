<?php

namespace App\Controller;

use App\Repository\CategorieProdRepository;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitfrontController extends AbstractController
{
    #[Route('/produitfront', name: 'app_produitfront')]
    public function index(ProduitRepository $produitRepository, CategorieProdRepository $categorieProdRepository): Response
    {
        $produits = $produitRepository->findAll();
        $categories = $categorieProdRepository->findAll();
       

        return $this->render('front/produitfront/index.html.twig', [
            'produits' => $produits,
            'categories' => $categories,
        ]);
    }


    #[Route('/categorie/{id}', name: 'app_category_products')]
    public function categoryProducts($id, ProduitRepository $produitRepository, CategorieProdRepository $categorieProdRepository): Response
    {
       $produits = $produitRepository->findByCategory($id);
       $categories = $categorieProdRepository->find($id);

       return $this->render('front/produitfront/category_products.html.twig', [
           'categories' => $categories,
           'produits' => $produits,
       ]);
    }
}

