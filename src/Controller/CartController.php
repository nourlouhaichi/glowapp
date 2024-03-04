<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



 #[Route("/cart", name:"cart_")]
 
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $ref => $quantite){
            $produit = $produitRepository->find($ref);
            $dataPanier[] = [
                "produit" => $produit,
                "quantite" => $quantite
            ];
            $total += $produit->getPrice() * $quantite +3 ;
        }

        return $this->render('front/cart/index.html.twig', compact("dataPanier", "total"));
    }


    
   
      #[Route("/add/{ref}", name:"add")]
     
    public function add(Produit $produit, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $ref = $produit->getref();

        if(!empty($panier[$ref])){
            $panier[$ref]++;
        }else{
            $panier[$ref] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    
      #[Route("/remove/{ref}", name:"remove")]
     
    public function remove(Produit $produit, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $produit->getRef();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }
    #[Route("/delete/{ref}", name:"delete")]
    
   public function delete(Produit $produit, SessionInterface $session)
   {
       // On récupère le panier actuel
       $panier = $session->get("panier", []);
       $ref = $produit->getRef();

       if(!empty($panier[$ref])){
           unset($panier[$ref]);
       }

       // On sauvegarde dans la session
       $session->set("panier", $panier);

       return $this->redirectToRoute("cart_index");
   }
  
    #[Route("/delete", name:"delete_all")]
     
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("cart_index");
    }
}
