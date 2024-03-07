<?php

namespace App\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Produit;
use App\Form\Produit1Type;
use MercurySeries\FlashyBundle\FlashyNotifier;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        $lowQuantityThreshold = 3;

        return $this->render('back/produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
            'lowQuantityThreshold' => $lowQuantityThreshold,
        ]);
    }
    #[Route('/api/produits', name: 'api_produits')]
    public function apiProduits(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();
        $data = [];

        foreach ($produits as $produit) {
            $data[] = [
                'name' => $produit->getName(),
                'quantity' => $produit->getQuantity(),
            ];
        }

        return $this->json($data);
    }
    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(Produit1Type::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('image')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // Add flash message or log error
                }

                // Update the entity with the file path or filename
                $produit->setImage($newFilename);
            }
            $entityManager->persist($produit);
            $entityManager->flush();
            flash()->addSuccess('Produit ajouté avec succès !');

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }
        
       
        
    #[Route('/print', name: 'app_produit_print', methods: ['GET'])]
    public function print(ProduitRepository $produitRepository)
    {      
        // Get all products from the repository
        $produits = $produitRepository->findAll();
    
        // Render the HTML template to generate PDF content
        $html = $this->renderView('back/produit/print.html.twig', [
            'logoPath' => 'public/front/img/logoglowapp.png', // Path to your logo image
            'produits' => $produits 
        ]);
    
        // Configure Dompdf options
        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
    
        // Instantiate Dompdf with the configured options
        $dompdf = new Dompdf($pdfOptions);
    
        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);
    
        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');
    
        // Render PDF content
        $dompdf->render();
    
        // Output PDF content as response
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename=my_pdf.pdf');
    
        return $response;
    }
    
    #[Route('/{ref}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('back/produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{ref}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Produit1Type::class, $produit);
        $form->handleRequest($request);
        // $form->handleRequest($request); bch man5srhomich ya3ni les donnees yab9o persisté
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            flash()->addSuccess('Produit modifier avec succès ! Vous êtes prêt à passer à létape suivante.');
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{ref}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getRef(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }


   
    #[Route('/dql', name: 'dql', methods: ['POST'])]//recherche avec dql
    public function dql(EntityManagerInterface $em, Request $request, ProduitRepository $produitRepository):Response
    {
        $result=$produitRepository->findAll();
        $req=$em->createQuery("select d from App\Entity\Produit d where d.price=:n OR d.categorieProd =:n OR d.name=:n OR d.ref=:n");
        if($request->isMethod('post'))
        {
            $value=$request->get('test');
            $req->setParameter('n',$value);
            $result=$req->getResult();

        }

        return $this->render('back/produit/index.html.twig',[
            'produits'=>$result,
        ]);
    }
}
