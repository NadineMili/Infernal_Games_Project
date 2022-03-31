<?php

namespace App\Repository;

use App\Entity\StreamRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StreamRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method StreamRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method StreamRating[]    findAll()
 * @method StreamRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StreamRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StreamRating::class);
    }

    // /**
    //  * @return StreamRating[] Returns an array of StreamRating objects
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
    public function findOneBySomeField($value): ?StreamRating
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
