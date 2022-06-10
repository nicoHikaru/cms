<?php
namespace App\Service\Cart;

use App\Entity\Cart;
use App\Entity\Produits;
use App\Entity\User;
use App\Repository\CartRepository;

class CartService
{
    private CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository) {
        $this->cartRepository = $cartRepository;
    }

    public function findAll():array
    {
        return $this->cartRepository->findAll();
    }

    public function findById(int $id)
    {
        return $this->cartRepository->findOneBy(array("id" => $id));
    }

    // public function getListeCartForCurrentUser($user)
    // {
    //     return $this->cartRepository->findBy(array("user" => $user));
    // }

    public function findByUser(User $user)
    {
        return $this->cartRepository->findBy(array("user" => $user));
    }

    public function update(int $idProduit)
    {
        return $this->cartRepository->update($idProduit);
    }

    public function findByProduitAndUser(Produits $produit,User $user) {
        return $this->cartRepository->findOneBy(array("produit" => $produit, "user" => $user));
    }

    public function delete(User $user,Produits $produit)
    {
        return $this->cartRepository->delete($user,$produit);
    }

    public function saveCart(Cart $cart,User $user,Produits $produit)
    {
        $cart->setUser($user);
        $cart->setProduit($produit);

        // $favoris->setDate($dateTime);
        $this->cartRepository->add($cart,true);
        return $cart;
    }
}