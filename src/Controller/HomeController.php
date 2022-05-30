<?php

namespace App\Controller;

use App\service\nav\MainNavService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private MainNavService $mainNavService;

    public function __construct(MainNavService $mainNavService)
    {
        $this->mainNavService = $mainNavService;
    }


    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $nav = $this->mainNavService->findAll();
        
        return $this->render('home/index.html.twig', [
            'nav' => $nav
        ]);
    }
}
