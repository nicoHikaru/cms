<?php
namespace App\Service\Favoris;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Favoris;
use App\Entity\Produits;
use App\Repository\FavorisRepository;

class FavorisService
{
    private FavorisRepository $favorisRepository;
    public function __construct(FavorisRepository $favorisRepository)
    {
        $this->favorisRepository = $favorisRepository;
    }

    public function findAll():array
    {
        return $this->favorisRepository->findAll();
    }

    public function findById(int $id)
    {
        return $this->favorisRepository->findOneBy(array("id" => $id));
    }

    public function findByUser(User $user)
    {
        return $this->favorisRepository->findBy(array("user" => $user));
    }

    // public function update(int $idProduit)
    // {
    //     return $this->favorisRepository->update($idProduit);
    // }

    public function updateCartData(Cart $cart ,Produits $produit,User $user)
    {
        return $this->favorisRepository->updateCartData($cart,$produit,$user);
    }

    public function updateCartDataBecomeNull(Produits $produit,User $user)
    {
        $cart = null;
        return $this->favorisRepository->updateCartDataBecomeNull($cart,$produit,$user);
    }

    public function findByProduitAndUser(Produits $produit,User $user) {
        return $this->favorisRepository->findOneBy(array("produit" => $produit, "user" => $user));
    }

    public function delete(User $user,Produits $produit)
    {
        return $this->favorisRepository->delete($user,$produit);
    }

    public function saveFavoris(Favoris $favoris,User $user,Produits $produit)
    {
        $favoris->setUser($user);
        $favoris->setProduit($produit);

        // $favoris->setDate($dateTime);
        return $this->favorisRepository->add($favoris,true);
    }
}