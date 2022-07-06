<?php
namespace App\Service\Produits;

use Exception;
use ErrorException;
use App\Entity\TypeProduits;
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
    
    public function saveNewTypeProduit(TypeProduits $typeProduit,string $name)
    {
        $typeProduit->setNom($name);
        if($typeProduit) {
            $flush = true;
            return $this->typeProduitsRepository->add($typeProduit,$flush); 
        }
        throw new ErrorException('erreur setNom');
    }
}