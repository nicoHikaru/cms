<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitType;
use App\StaticData\UploadFile;
use App\StaticData\DateAndTime;
use App\Service\Nav\MainNavService;
use App\Service\Produits\ProduitsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProduitController extends AbstractController
{
    private MainNavService $mainNavService;
    private ProduitsService $produitsService;

    public function __construct(MainNavService $mainNavService,ProduitsService $produitsService)
    {
        $this->mainNavService = $mainNavService;
        $this->produitsService = $produitsService;
    }
    #[Route('/produit/detail/idProduit={idProduit}', name: 'app_produit_detail')]
    public function index(Request $request,int $idProduit): Response
    {
        $nav = $this->mainNavService->findAll();
        $getProduit = $this->produitsService->findById($idProduit);
        
        return $this->render('produit/index.html.twig', [
            'nav' => $nav,
            'produit'=> $getProduit
        ]);
    }

    #[Route('produit/ajouter', name: 'app_produit_ajout', methods: ['GET', 'POST'])]
    public function ajouter(Request $request,SluggerInterface $slugger):Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $produits = new Produits();
        
        $form = $this->createForm(ProduitType::class, $produits, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            // /** @var UploadedFile $publication */
            $picture = $form->get('photo')->getData();

            $newFilename = UploadFile::upload($picture,$this,$slugger);
            $dateTime = DateAndTime::now();
            
            $nom = $form->get('nom')->getData();
            $price = (int)$form->get('price')->getData();
            
            $this->produitsService->savePhoto($produits,$nom,$price,$newFilename,$dateTime);
            
            
            
            $this->addFlash('success', 'article ajouter');
            return $this->redirectToRoute('app_produit_ajout', [], Response::HTTP_SEE_OTHER);
        }

        $nav = $this->mainNavService->findAll();

        return $this->render('produit/ajouter.html.twig', [
            'nav' => $nav,
            'form' => $form->createView()
        ]);
    }
}
