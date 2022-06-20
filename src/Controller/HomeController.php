<?php

namespace App\Controller;

use RolesUser;
use App\Service\Cart\CartService;
use App\Service\User\UserService;
use App\Service\Nav\MainNavService;
use App\Service\Favoris\FavorisService;
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
    private FavorisService $favorisService;
    private UserService $userService;

    public function __construct(MainNavService $mainNavService,ProduitsService $produitsService,CartService $cartService,FavorisService $favorisService,UserService $userService)
    {
        $this->mainNavService = $mainNavService;
        $this->produitsService = $produitsService;
        $this->cartService = $cartService;
        $this->favorisService = $favorisService;
        $this->userService = $userService;
    }


    #[Route('/', name: 'home')]
    public function index(Request $request,AuthenticationUtils $authenticationUtils): Response
    {

        $nav = $this->mainNavService->findAll();
        $getProduits = $this->produitsService->findAll();
        
        $rolesAdmin = RolesUser::rolesAdmin();
        
        $displayProduit = [];
        foreach($getProduits as $produit) {
            $displayProduit[] = array($produit,null);
        }


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
       
        $produitsInCart = [];
        $favoris = [];
        if($this->getUser()) {
            $produitsInCart = $this->cartService->getProduitsInCart($this->getUser(),$getProduits);
           
            foreach($getProduits as $content) {
                $favoris[] = $this->favorisService->findByProduitAndUser($content,$this->getUser());
            }
           
            foreach($getProduits as $key => $produit) {
                foreach($favoris as $favContent) {
                    if($favContent !== null && $produit->getId() === $favContent->getProduit()->getId()) {
                        $displayProduit[$key][1] = $favContent;
                    }
                } 
            }
        }
       
       
        return $this->render('home/index.html.twig', [
            'nav' => $nav,
            'produits' => $displayProduit,
            'produitsInCart' => $produitsInCart,
            'last_username' => $lastUsername, 
            'error' => $error,
            'favoris' => $favoris,
            'rolesAdmin' => $rolesAdmin
        ]);
    }
}
