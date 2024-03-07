<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


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
     #[Route('/checkout', name: "checkout")]
     public function checkout(SessionInterface $session, ProduitRepository $produitRepository): Response
     {

         Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

         $panier = $session->get("panier", []);
         $dataPanier = [];
         $total = 0;

         $lineItems = [];

         foreach ($panier as $ref => $quantite) {
             $produit = $produitRepository->find($ref);


             $totalPrice = $produit->getPrice() * $quantite * 100;

             // Add product to line items
             $lineItems[] = [
                 'price_data' => [
                     'currency' => 'usd', // Change to your currency if needed
                     'product_data' => [
                         'name' => $produit->getName(),
                     ],
                     'unit_amount' => $totalPrice,
                 ],
                 'quantity' => $quantite,
             ];

             $total += $totalPrice;
         }

         $session = StripeSession::create([
             'payment_method_types' => ['card'],
             'line_items' => $lineItems,
             'mode' => 'payment',
             'success_url' => $this->generateUrl('cart_checkout_success',[],UrlGeneratorInterface::ABSOLUTE_URL),
             'cancel_url' =>  $this->generateUrl('cart_checkout_cancel',[],UrlGeneratorInterface::ABSOLUTE_URL),
         ]);

         return new RedirectResponse($session->url, 303);
     }

    #[Route('/checkout/success', name: 'checkout_success')]
    public function checkoutSuccess(): Response
    {
        // Render the success template
        return $this->render('front/cart/checkout_success.html.twig');
    }

    #[Route('/checkout/cancel', name: 'checkout_cancel')]
    public function checkoutCancel(): Response
    {
        // Render the cancel template
        return $this->render('front/cart/checkout_cancel.html.twig');
    }
}
