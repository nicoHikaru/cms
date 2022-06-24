<?php

namespace App\Controller;


use App\Entity\Cart;
use App\StaticData\RolesUser;
use App\Service\Cart\CartService;
use App\Service\User\UserService;
use App\Service\Nav\MainNavService;
use App\Service\Favoris\FavorisService;
use App\Service\Produits\ProduitsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $rolesAdmin = RolesUser::rolesAdmin();
        
        $carts = $this->cartService->findByUser($user);
        $total = $this->cartService->totalAPayer($user);
        $tva = $this->cartService->tva($total,0.2);
       
        $ttc = $total + $tva;
        return $this->render('cart/index.html.twig', [
            "carts" => $carts,
            "totalAPayer" => $total,
            "ttc" => $ttc,
            "tva" => $tva,
            'rolesAdmin' => $rolesAdmin
        ]);
    }

     /**
     * requete ajax return bool pour cart produit par user
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/produit/cart/{idProduit}/{idUser}', name: 'app_produit_cart', methods: ['GET','POST'])]
    public function cart(int $idProduit,int $idUser):JsonResponse
    {
        $produit = $this->produitsService->findById($idProduit);
        $user = $this->userService->findById($idUser);
        $cart = $this->cartService->findByProduitAndUser($produit,$user);

        $bool = false;
        if($cart === null) {
            $newInstance = new Cart();
            $cart = $this->cartService->saveCart($newInstance,$user,$produit);
            $this->favorisService->updateCartData($cart,$produit,$user);
            $bool = true;
        } else {
            $this->favorisService->updateCartDataBecomeNull($produit,$user);
            $this->cartService->delete($user,$produit);
        }

        $data = [
            'cart' => $bool,
        ];
        
        return new JsonResponse($data);
    }
}
