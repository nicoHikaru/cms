<?php

namespace App\Controller;

use ErrorException;
use App\Entity\Favoris;
use App\Entity\Produits;
use App\Form\ProduitType;
use App\Entity\TypeProduits;
use App\StaticData\RolesUser;
use App\StaticData\UploadFile;
use App\StaticData\DateAndTime;
use App\Form\TypeProduitFormType;
use App\Service\Cart\CartService;
use App\Service\User\UserService;
use App\Service\Nav\MainNavService;
use App\Service\Favoris\FavorisService;
use App\Service\Produits\ProduitsService;
use App\Repository\TypeProduitsRepository;
use App\Service\Produits\TypeProduitService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class ProduitController extends AbstractController
{
    private MainNavService $mainNavService;
    private ProduitsService $produitsService;
    private FavorisService $favorisService;
    private UserService $userService;
    private CartService $cartService;
    private TypeProduitService $typeProduitService;

    public function __construct(MainNavService $mainNavService,ProduitsService $produitsService,FavorisService $favorisService,UserService $userService,CartService $cartService,TypeProduitService $typeProduitService)
    {
        $this->mainNavService = $mainNavService;
        $this->produitsService = $produitsService;
        $this->favorisService = $favorisService;
        $this->userService = $userService;
        $this->cartService = $cartService;
        $this->typeProduitService = $typeProduitService;
    }
    #[Route('/produit/detail/idProduit={idProduit}', name: 'app_produit_detail')]
    public function index(Request $request,int $idProduit,AuthenticationUtils $authenticationUtils): Response
    {
        $nav = $this->mainNavService->findAll();
        $getProduit = $this->produitsService->findById($idProduit);
        $user = $this->getUser();
        
        $rolesAdmin = RolesUser::rolesAdmin();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $favoris = null;
        if($user !== null) {
            $favoris = $this->favorisService->findByProduitAndUser($getProduit,$user);
        }
       
        return $this->render('produit/index.html.twig', [
            'nav' => $nav,
            'produit'=> $getProduit,
            'favoris' => $favoris,
            'last_username' => $lastUsername, 
            'error' => $error,
            'rolesAdmin' => $rolesAdmin
        ]);
    }

    /**
     * requete ajax return bool pour favoris produit par user
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/produit/favoris/{idProduit}/{idUser}', name: 'app_produit_favoris', methods: ['GET','POST'])]
    public function favoris(Request $request,int $idProduit,int $idUser):JsonResponse
    {
        $produit = $this->produitsService->findById($idProduit);
        $user = $this->userService->findById($idUser);
        $favoris = $this->favorisService->findByProduitAndUser($produit,$user);
        $carts = $this->cartService->findAll(); 

        $bool = false;
        if($favoris === null) {
            $newInstance = new Favoris();
            $this->favorisService->saveFavoris($newInstance,$user,$produit);
            $bool = true;
        } else {
            $this->favorisService->delete($user,$produit);
        }

        if($bool === true) {
            $favoris = $this->favorisService->findAll();
            foreach($carts as $cart) {
                foreach($favoris as $favori) {
                    if($cart->getProduit() === $favori->getProduit() && $cart->getUser() === $favori->getUser()) {
                       if($favori->getCart() === null) {
                            $this->favorisService->updateCartData($cart,$cart->getProduit(),$this->getUser());
                       }
                    }
                }
            }
        }

        $data = [
            'favoris' => $bool,
        ];
        
        return new JsonResponse($data);
    }

    #[Route('produit/ajouter', name: 'app_produit_ajout', methods: ['GET', 'POST'])]
    public function ajouter(Request $request,SluggerInterface $slugger):Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $produits = new Produits();
        $rolesAdmin = RolesUser::rolesAdmin();
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

        return $this->render('produit/admin/ajouter.html.twig', [
            'nav' => $nav,
            'form' => $form->createView(),
            'rolesAdmin' => $rolesAdmin
        ]);
    }

    #[Route('produit/listeFavoris', name: 'app_produit_listeFavoris', methods: ['GET', 'POST'])]
    public function listeFavoris(Request $request,SluggerInterface $slugger):Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $nav = $this->mainNavService->findAll();
        $favoris = $this->favorisService->findByUser($this->getUser());
        $rolesAdmin = RolesUser::rolesAdmin();
        return $this->render('produit/listeFavoris.html.twig', [
            'nav' => $nav,
            'favoris' => $favoris,
            'rolesAdmin' => $rolesAdmin
        ]);

    }

    #[Route('/produit/liste', name: 'app_produit_liste')]
    public function liste(Request $request): Response
    {
       
        $nav = $this->mainNavService->findAll();
        $getProduit = $this->produitsService->findAll();
        $user = $this->getUser();
        
        $rolesAdmin = RolesUser::rolesAdmin();
        $typesProduits = $this->typeProduitService->findAll();
        
        if($user === null or empty($user)) {
            return $this->redirectToRoute('home');
        }
        
        $req = $request->request;
        if($req->get('choiceType') == true) {
            $type = $req->get('choiceType');
            $typeProduit = $this->typeProduitsRepository->findOneByNom($type);
            $getProduit = $this->produitsService->findByType($typeProduit);
        }
       
        return $this->render('produit/admin/liste.html.twig', [
            'nav' => $nav,
            'produit'=> $getProduit,
            'rolesAdmin' => $rolesAdmin,
            'typesProduits' => $typesProduits
        ]);
    }

    /**
     * Permet effacer article
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/delete/article/{idUser}/{idProduit}', name: 'app_produit_delete', methods: ['GET','POST'])]
    public function deleteArticle(Request $request,int $idProduit,int $idUser):JsonResponse
    {
        $produit = $this->produitsService->findById($idProduit);
        $user = $this->userService->findById($idUser);

        $rolesAdmin = RolesUser::rolesAdmin();

        $bool = false;
        if($user->getRoles()[0] === $rolesAdmin) {
            $this->favorisService->deleteFavoris($produit);
            $this->cartService->deleteCart($produit);
            $this->produitsService->deleteProduit($produit);
            $bool = true;
        }

        $data = [
            'info' => $bool,
        ];
        
        return new JsonResponse($data);
    }

    /**
     * Permet effacer article
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/produit/edit/{idUser}/{idProduit}', name: 'app_produit_edit', methods: ['GET','POST'])]
    public function editArticle(Request $request,int $idUser,int $idProduit):JsonResponse
    {
        $produit = $this->produitsService->findById($idProduit);
        $user = $this->userService->findById($idUser);

        $rolesAdmin = RolesUser::rolesAdmin();
        $req = $request->request;
        $bool = false;
        if($user->getRoles()[0] === $rolesAdmin) {
            
            $bool = true;
        }

        $data = [
            'info' => $user,
        ];
        
        return new JsonResponse($data);
    }

    #[Route('/produit/gestion', name: 'app_produit_gestion')]
    public function listeGestion(Request $request): Response
    {
    
        $nav = $this->mainNavService->findAll();
        $getTypeProduit = $this->typeProduitService->findAll();
        $user = $this->getUser();
        
        $rolesAdmin = RolesUser::rolesAdmin();
        $typesProduits = $this->typeProduitService->findAll();
        
        if($user === null or empty($user)) {
            return $this->redirectToRoute('home');
        }
        
        $req = $request->request;

        $typesProduits = new TypeProduits();
        $form = $this->createForm(TypeProduitFormType::class, $typesProduits, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $name = $form->get('nom')->getData();
            $typeProduits = new TypeProduits();
            $this->typeProduitService->saveNewTypeProduit($typeProduits,$name);
            
            $this->addFlash('success', 'ajout type produit');
            return $this->redirectToRoute('app_produit_gestion', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('produit/admin/gestion.html.twig', [
            'nav' => $nav,
            'rolesAdmin' => $rolesAdmin,
            'typesProduits' => $typesProduits,
            'form' => $form->createView(),
            'produits' => $getTypeProduit
        ]);
    }
}
