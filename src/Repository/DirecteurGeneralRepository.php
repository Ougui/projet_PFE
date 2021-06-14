<?php

namespace App\Repository;

use App\Entity\DirecteurGeneral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DirecteurGeneral|null find($id, $lockMode = null, $lockVersion = null)
 * @method DirecteurGeneral|null findOneBy(array $criteria, array $orderBy = null)
 * @method DirecteurGeneral[]    findAll()
 * @method DirecteurGeneral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirecteurGeneralRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DirecteurGeneral::class);
    }

    // /**
    //  * @return DirecteurGeneral[] Returns an array of DirecteurGeneral objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DirecteurGeneral
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
