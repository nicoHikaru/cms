<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Produits;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Cart>
 *
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function add(Cart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cart $entity, bool $flush = false): void
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
        $query = $qb->delete('App\Entity\Cart', 'c')
                    ->where('c.produit = :produit AND c.user = :user')
                    ->setParameter('user', $user)
                    ->setParameter('produit', $produit)
                    ->getQuery();

        $query->execute();
    }

    public function deleteCart(Produits $produit)
    {

        $em =  $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $query = $qb->delete('App\Entity\Cart', 'c')
                    ->where('c.produit = :produit')
                    ->setParameter('produit', $produit)
                    ->getQuery();

        $query->execute();
    }
//    /**
//     * @return Cart[] Returns an array of Cart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cart
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
