<?php

namespace App\Repository;

use App\Entity\StreamComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StreamComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method StreamComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method StreamComment[]    findAll()
 * @method StreamComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StreamCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StreamComment::class);
    }

    // /**
    //  * @return StreamComment[] Returns an array of StreamComment objects
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
    public function findOneBySomeField($value): ?StreamComment
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
