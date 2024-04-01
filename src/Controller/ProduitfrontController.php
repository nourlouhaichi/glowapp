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
use Knp\Component\Pager\PaginatorInterface;

class ProduitfrontController extends AbstractController
{
    #[Route('/produitfront', name: 'app_produitfront')]
    public function index(ProduitRepository $produitRepository, CategorieProdRepository $categorieProdRepository ,Request $request,PaginatorInterface $paginator): Response
    {
        $produits = $produitRepository->findAll();
        $categories = $categorieProdRepository->findAll();
       

        $pagination = $paginator->paginate(
            $produitRepository->paginationQuery(),
            $request->query->get('page',1),
            5
        );


        return $this->render('front/produitfront/index.html.twig', [
            'pagination'=>$pagination,
            
            'categories' => $categories,
        ]);
    }


    #[Route('/categorie/{id}', name: 'app_category_products')]
    public function categoryProducts($id, ProduitRepository $produitRepository, CategorieProdRepository $categorieProdRepository, Request $request, PaginatorInterface $paginator): Response
{
    $categories = $categorieProdRepository->find($id);
    $produits = $produitRepository->findByCategory($id);
    if (!$categories) {
        throw $this->createNotFoundException('La catégorie n\'existe pas.');
    }

    $pagination = $paginator->paginate(
        $produitRepository->paginationCQuery($id),
        $request->query->get('page', 1),
        3
    );

    return $this->render('front/produitfront/category_products.html.twig', [
        'categories' => $categories,
        'pagination' => $pagination,
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
    public function triPrix(Request $request, ProduitRepository $produitRepository, CategorieProdRepository $categorieProdRepository, PaginatorInterface $paginator): Response
    {
        $tri = $request->query->get('tri', 'asc'); // Par défaut, tri croissant
        $produits = $produitRepository->findBy([], ['price' => $tri]);
        $categories = $categorieProdRepository->findAll();
    
        $pagination = $paginator->paginate(
            $produits,
            $request->query->get('page', 1),
            3
        );
    
        return $this->render('front/produitfront/index.html.twig', [
            'pagination' => $pagination,
            'categories' => $categories,
        ]);
    }
    


    
   
     
     
    
     
}
