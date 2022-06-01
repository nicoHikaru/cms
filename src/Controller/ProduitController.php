<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitType;
use App\Service\Nav\MainNavService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProduitController extends AbstractController
{
    private MainNavService $mainNavService;

    public function __construct(MainNavService $mainNavService)
    {
        $this->mainNavService = $mainNavService;
    }
    #[Route('/produit/detail', name: 'app_produit_detail')]
    public function index(): Response
    {
        $nav = $this->mainNavService->findAll();
        return $this->render('produit/index.html.twig', [
            'nav' => $nav
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
            $picture = $form->get('picture')->getData();

            if ($picture !== null) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();

                try {
                    $picture->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                //$produits->setPicture($newFilename);
            }
           
            
            
            // $dateTime = date('Y-m-d H:i');
            // setlocale(LC_TIME, "fr_FR");
            // $date = strftime("%Y-%m-%d %H:%M", strtotime($dateTime));
            // $publication->setCreatedAt($date);

            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }



        $nav = $this->mainNavService->findAll();

        return $this->render('produit/ajouter.html.twig', [
            'nav' => $nav,
            'form' => $form->createView()
        ]);
    }
}
