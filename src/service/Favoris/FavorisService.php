<?php
namespace App\Service\Favoris;

use App\Entity\User;
use App\Entity\Favoris;
use App\Entity\Produits;
use App\Repository\FavorisRepository;

class FavorisService
{
    private $produitsRepository;
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

    public function update(int $idProduit)
    {
        return $this->favorisRepository->update($idProduit);
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