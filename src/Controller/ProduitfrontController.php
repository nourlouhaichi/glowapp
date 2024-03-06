<?php

namespace App\Controller;

use App\Repository\CategorieProdRepository;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProduitfrontController extends AbstractController
{
    #[Route('/produitfront', name: 'app_produitfront')]
    public function index(ProduitRepository $produitRepository, CategorieProdRepository $categorieProdRepository, Request $request): Response
    {
        $searchQuery = $request->query->get('search');
        $produits = $produitRepository->findAll();
        $categories = $categorieProdRepository->findAll();

        if ($searchQuery) {
            // Filter products based on search query
            $produits = $produitRepository->searchProducts($searchQuery);
        }

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
    #[Route('/produitfront/{ref}', name: 'app_produitfront_details', methods: ['GET'])]
    public function produitfrontdetails(Produit $produit,CategorieProdRepository $categorieProdRepository): Response
    {
        return $this->render('front/produitfront/showdetails.html.twig', [
            'produit' => $produit,
            'categories' => $categorieProdRepository->findAll(),
        ]);
    }

    #[Route('/tri-prix', name: 'tri_prix')]
    public function triPrix(Request $request, ProduitRepository $produitRepository,CategorieProdRepository $categorieProdRepository): Response
    {
        $tri = $request->query->get('tri', 'asc'); // Par défaut, tri croissant
        $produits = $produitRepository->findBy([], ['price' => $tri]);
        $categories = $categorieProdRepository->findAll();

        return $this->render('front/produitfront/index.html.twig', [
            'produits' => $produits,
            'categories' => $categories,
        ]);
    }
    
    
   
     
     
    
     
}
