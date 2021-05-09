<?php

namespace App\Repository;

use App\Entity\SearchPlant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SearchPlant|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchPlant|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchPlant[]    findAll()
 * @method SearchPlant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchPlantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchPlant::class);
    }

    // /**
    //  * @return SearchPlant[] Returns an array of SearchPlant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SearchPlant
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
