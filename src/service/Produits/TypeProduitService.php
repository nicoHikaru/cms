<?php
namespace App\Service\Produits;

use App\Repository\TypeProduitsRepository;


class TypeProduitService
{
    private $typeProduitsRepository;
    public function __construct(TypeProduitsRepository $typeProduitsRepository)
    {
        $this->typeProduitsRepository = $typeProduitsRepository;
    }

    public function findAll():array
    {
        return $this->typeProduitsRepository->findAll();
    }

    public function findById(int $id)
    {
        return $this->typeProduitsRepository->findOneBy(array("id" => $id));
    }  
    public function findOneByNom(string $nom)
    {
        return $this->typeProduitsRepository->findOneBy(array("nom" => $nom));
    } 
}