<?php

namespace App\Repository;

use App\Entity\GrowthMonths;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GrowthMonths|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrowthMonths|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrowthMonths[]    findAll()
 * @method GrowthMonths[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrowthMonthsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GrowthMonths::class);
    }

    // /**
    //  * @return GrowthMonths[] Returns an array of GrowthMonths objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GrowthMonths
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
