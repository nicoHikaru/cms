<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use App\Service\User\UserService;
use App\Service\Nav\MainNavService;
use App\Service\Favoris\FavorisService;
use App\Service\Produits\ProduitsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    private MainNavService $mainNavService;
    private ProduitsService $produitsService;
    private FavorisService $favorisService;
    private UserService $userService;
    private CartService $cartService;

    public function __construct(MainNavService $mainNavService,ProduitsService $produitsService,FavorisService $favorisService,UserService $userService,CartService $cartService)
    {
        $this->mainNavService = $mainNavService;
        $this->produitsService = $produitsService;
        $this->favorisService = $favorisService;
        $this->userService = $userService;
        $this->cartService = $cartService;
    }


    #[Route('/cart', name: 'app_cart')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $carts = $this->cartService->findByUser($user);
        $total = $this->cartService->totalAPayer($user);
        $tva = $this->cartService->tva($total,0.2);
       
        $ttc = $total + $tva;
        return $this->render('cart/index.html.twig', [
            "carts" => $carts,
            "totalAPayer" => $total,
            "ttc" => $ttc,
            "tva" => $tva,
        ]);
    }
}
