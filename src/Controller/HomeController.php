<?php

namespace App\Controller;

use App\Service\Nav\MainNavService;
use App\Service\Produits\ProduitsService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private MainNavService $mainNavService;
    private ProduitsService $produitsService;

    public function __construct(MainNavService $mainNavService,ProduitsService $produitsService)
    {
        $this->mainNavService = $mainNavService;
        $this->produitsService = $produitsService;
    }


    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $nav = $this->mainNavService->findAll();
        $getProduits = $this->produitsService->findAll();
        
        return $this->render('home/index.html.twig', [
            'nav' => $nav,
            'produits' => $getProduits
        ]);
    }
}
