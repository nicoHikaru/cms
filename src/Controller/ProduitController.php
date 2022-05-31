<?php

namespace App\Controller;

use App\Service\Nav\MainNavService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            'controller_name' => 'ProduitController',
            'nav' => $nav
        ]);
    }
}
