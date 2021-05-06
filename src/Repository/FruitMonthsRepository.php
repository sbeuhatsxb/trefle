<?php

namespace App\Repository;

use App\Entity\FruitMonths;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FruitMonths|null find($id, $lockMode = null, $lockVersion = null)
 * @method FruitMonths|null findOneBy(array $criteria, array $orderBy = null)
 * @method FruitMonths[]    findAll()
 * @method FruitMonths[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitMonthsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FruitMonths::class);
    }

    // /**
    //  * @return FruitMonths[] Returns an array of FruitMonths objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FruitMonths
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
