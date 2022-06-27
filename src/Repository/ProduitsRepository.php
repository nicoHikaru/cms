<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Produits;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Cart;

/**
 * @extends ServiceEntityRepository<Produits>
 *
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produits::class);
    }

    public function add(Produits $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produits $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllAndProduitInCart(User $user,Cart $cart)
    {
        $qb = $this->createQueryBuilder('p');
        $query = $qb
            ->select('produits')
            ->from(' App\Entity\Cart', 'cart')
            ->leftJoin('cart.id', 'c')
            ->where('user.id = :userId')
            ->setParameters(array(':userId' => $user->getId()))
            ->getQuery();
    }

    public function deleteProduit(Produits $produit)
    {

        $em =  $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $query = $qb->delete('App\Entity\Produits', 'p')
                    ->where('p.id = :produit')
                    ->setParameter('produit', $produit)
                    ->getQuery();

        $query->execute();
    }

//    /**
//     * @return Produits[] Returns an array of Produits objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produits
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
