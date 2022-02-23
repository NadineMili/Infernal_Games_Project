<?php

namespace App\Repository;

use App\Entity\StreamData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StreamData|null find($id, $lockMode = null, $lockVersion = null)
 * @method StreamData|null findOneBy(array $criteria, array $orderBy = null)
 * @method StreamData[]    findAll()
 * @method StreamData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StreamDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StreamData::class);
    }

    // /**
    //  * @return StreamData[] Returns an array of StreamData objects
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
    public function findOneBySomeField($value): ?StreamData
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
