<?php

namespace App\Repository;

use App\Entity\NewsletterTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NewsletterTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsletterTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsletterTemplate[]    findAll()
 * @method NewsletterTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsletterTemplate::class);
    }

    // /**
    //  * @return NewsletterTemplate[] Returns an array of NewsletterTemplate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NewsletterTemplate
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
