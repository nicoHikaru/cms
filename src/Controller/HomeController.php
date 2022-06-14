<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use App\Service\Nav\MainNavService;
use App\Service\Produits\ProduitsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    private MainNavService $mainNavService;
    private ProduitsService $produitsService;
    private CartService $cartService;

    public function __construct(MainNavService $mainNavService,ProduitsService $produitsService,CartService $cartService)
    {
        $this->mainNavService = $mainNavService;
        $this->produitsService = $produitsService;
        $this->cartService = $cartService;
    }


    #[Route('/', name: 'home')]
    public function index(Request $request,AuthenticationUtils $authenticationUtils): Response
    {
        $nav = $this->mainNavService->findAll();
        $getProduits = $this->produitsService->findAll();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $produitsInCart = [];
        if($this->getUser()) {
            $produitsInCart = $this->cartService->getProduitsInCart($this->getUser(),$getProduits);
        }
        
        return $this->render('home/index.html.twig', [
            'nav' => $nav,
            'produits' => $getProduits,
            'produitsInCart' => $produitsInCart,
            'last_username' => $lastUsername, 
            'error' => $error,
        ]);
    }
}
