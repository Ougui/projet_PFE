<?php

namespace App\Repository;

use App\Entity\RH;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RH|null find($id, $lockMode = null, $lockVersion = null)
 * @method RH|null findOneBy(array $criteria, array $orderBy = null)
 * @method RH[]    findAll()
 * @method RH[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RHRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RH::class);
    }

    // /**
    //  * @return RH[] Returns an array of RH objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RH
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
