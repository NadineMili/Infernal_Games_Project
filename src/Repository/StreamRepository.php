<?php

namespace App\Repository;

use App\Entity\Stream;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stream|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stream|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stream[]    findAll()
 * @method Stream[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StreamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stream::class);
    }

    public function findByState()
    {
        $query = $this->createQueryBuilder('s')
            ->where('s.state = 1')
            ->getQuery();

        return $query->getResult();
    }

    public function findByStateRating($ratingId)
    {
        $query = $this->createQueryBuilder('s')
            ->where('s.state = 1')
            ->andWhere('s.rating = :ratingId')
            ->setParameter('ratingId', $ratingId)
            ->getQuery();

        return $query->getResult();
    }

    public function findByStateCategory($categoryId)
    {
        $query = $this->createQueryBuilder('s')
            ->where('s.state = 1')
            ->andWhere('s.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery();

        return $query->getResult();
    }

    public function findByStateUser($userId)
    {
        $query = $this->createQueryBuilder('s')
            ->leftJoin('s.accessData', 'a')
            ->where('s.state = 1')
            ->andWhere('a.streamer = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();

        return $query->getResult();
    }

    public function findById($id)
    {
        $em= $this->getEntityManager();

        $query = $this->createQueryBuilder('s')
            ->addSelect('sd') // to make Doctrine actually use the join
            ->leftJoin('s.accessData', 'sd')
            ->where('s.id= :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getResult();
    }
    // /**
    //  * @return Stream[] Returns an array of Stream objects
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
    public function findOneBySomeField($value): ?Stream
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
