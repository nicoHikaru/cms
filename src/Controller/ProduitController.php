<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\Produits;
use App\Form\ProduitType;
use App\StaticData\UploadFile;
use App\StaticData\DateAndTime;
use App\Service\User\UserService;
use App\Service\Nav\MainNavService;
use App\Service\Favoris\FavorisService;
use App\Service\Produits\ProduitsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProduitController extends AbstractController
{
    private MainNavService $mainNavService;
    private ProduitsService $produitsService;
    private FavorisService $favorisService;
    private UserService $userService;

    public function __construct(MainNavService $mainNavService,ProduitsService $produitsService,FavorisService $favorisService,UserService $userService)
    {
        $this->mainNavService = $mainNavService;
        $this->produitsService = $produitsService;
        $this->favorisService = $favorisService;
        $this->userService = $userService;
    }
    #[Route('/produit/detail/idProduit={idProduit}', name: 'app_produit_detail')]
    public function index(Request $request,int $idProduit): Response
    {
        $nav = $this->mainNavService->findAll();
        $getProduit = $this->produitsService->findById($idProduit);
        $user = $this->getUser();
       
        $favoris = null;
        if($user !== null) {
            $favoris = $this->favorisService->findByProduitAndUser($getProduit,$user);
        }
        
        return $this->render('produit/index.html.twig', [
            'nav' => $nav,
            'produit'=> $getProduit,
            'favoris' => $favoris
        ]);
    }

    /**
     * requete ajax return bool pour favoris produit par user
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/produit/favoris/{idProduit}/{idUser}', name: 'app_produit_favoris', methods: ['GET','POST'])]
    public function favoris(Request $request,int $idProduit,int $idUser)
    {
        $produit = $this->produitsService->findById($idProduit);
        $user = $this->userService->findById($idUser);
        $favoris = $this->favorisService->findByProduitAndUser($produit,$user);

        $bool = false;
        if($favoris === null) {
            $newInstance = new Favoris();
            $this->favorisService->saveFavoris($newInstance,$user,$produit);
            $bool = true;
        } else {
            $this->favorisService->delete($user,$produit);
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
