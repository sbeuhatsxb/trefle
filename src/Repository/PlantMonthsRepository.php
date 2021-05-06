<?php

namespace App\Repository;

use App\Entity\Months;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Months|null find($id, $lockMode = null, $lockVersion = null)
 * @method Months|null findOneBy(array $criteria, array $orderBy = null)
 * @method Months[]    findAll()
 * @method Months[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlantMonthsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Months::class);
    }

    // /**
    //  * @return PlantMonths[] Returns an array of PlantMonths objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlantMonths
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
