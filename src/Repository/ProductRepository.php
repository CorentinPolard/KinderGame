<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
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

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findThreeByCategory($value, $name): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category = :val AND p.label != :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $name)
            // ->orderBy('p.id', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function paginateProducts(int $page, int $limit): Paginator
    {
        return new Paginator($this->createQueryBuilder('p')
            // Va chercher dans la bdd $limit (ici 3) produits à partir de produit numéro setFirstResult
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->setHint(Paginator::HINT_ENABLE_DISTINCT, false));
    }
}
