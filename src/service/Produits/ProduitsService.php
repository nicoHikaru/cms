<?php
namespace App\Service\Produits;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;

class ProduitsService
{
    private $produitsRepository;
    public function __construct(ProduitsRepository $produitsRepository)
    {
        $this->produitsRepository = $produitsRepository;
    }

    public function findAll():array
    {
        return $this->produitsRepository->findAll();
    }

    public function savePhoto(Produits $produits,string $nom,int $price,$newFilename,$dateTime)
    {
        $produits->setNom($nom);
        $produits->setPrice($price);

        if($newFilename !== null) {
            $produits->setPhoto($newFilename);
        }
        $produits->setDate($dateTime);
        return $this->produitsRepository->add($produits,true);
    }
}