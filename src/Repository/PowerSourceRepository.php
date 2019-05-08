<?php

namespace App\Repository;

use App\Entity\PowerSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PowerSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method PowerSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method PowerSource[]    findAll()
 * @method PowerSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PowerSourceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PowerSource::class);
    }

    // /**
    //  * @return PowerSource[] Returns an array of PowerSource objects
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
    public function findOneBySomeField($value): ?PowerSource
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
