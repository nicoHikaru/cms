<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Favoris;
use App\Entity\Produits;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Favoris>
 *
 * @method Favoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favoris[]    findAll()
 * @method Favoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavorisRepository extends ServiceEntityRepository
{
   
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favoris::class);
        
    }

    public function add(Favoris $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Favoris $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function delete(User $user,Produits $produit)
    {

        $em =  $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $query = $qb->delete('App\Entity\Favoris', 'f')
                    ->where('f.produit = :produit AND f.user = :user')
                    ->setParameter('user', $user)
                    ->setParameter('produit', $produit)
                    ->getQuery();

        $query->execute();
    }

    public function updateCartData(Cart $cart ,Produits $produit,User $user)
    {
       
        $em =  $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $query = $queryBuilder->update('App\Entity\Favoris', 'f')
                ->set('f.cart', ':cart')
                ->where('f.produit = :produit and f.user = :user')
                ->setParameter('cart', $cart)
                ->setParameter('produit', $produit)
                ->setParameter('user', $user)
                ->getQuery();
        $result = $query->execute();
    }

    public function updateCartDataBecomeNull($cart ,Produits $produit,User $user)
    {
        $em =  $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $query = $queryBuilder->update('App\Entity\Favoris', 'f')
                ->set('f.cart', ':cart')
                ->where('f.produit = :produit and f.user = :user')
                ->setParameter('cart', $cart)
                ->setParameter('produit', $produit)
                ->setParameter('user', $user)
                ->getQuery();
        $result = $query->execute();
    }

//    /**
//     * @return Favoris[] Returns an array of Favoris objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Favoris
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
