<?php

namespace App\Repository;

use App\Entity\Concerts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Concerts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Concerts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Concerts[]    findAll()
 * @method Concerts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConcertsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Concerts::class);
    }

    // /**
    //  * @return Concerts[] Returns an array of Concerts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Concerts
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
