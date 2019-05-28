<?php

namespace App\Repository;

use App\Entity\Bau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Bau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bau[]    findAll()
 * @method Bau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BauRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bau::class);
    }

//    /**
//     * @return Bau[] Returns an array of Bau objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bau
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByKeyword($keyWord = null)
    {
        $query = $this->createQueryBuilder('t');

        if (!is_null($keyWord) && !empty($keyWord)) {
            $query->where($query->expr()->like('t.description', '?1'))
                ->setParameter('1', '%'.$keyWord.'%');
        }
        $query->orderBy('t.start_time', 'ASC');

        return $query->getQuery();
    }

    public function findForHome()
    {
        $query = $this->createQueryBuilder('t');
        $query->where('t.status = ?1')
            ->orWhere('t.status = ?2')
            ->orderBy('t.start_time', 'ASC')
            ->setParameter('1', 'pending')
            ->setParameter('2', 'deployment');

        return $query->getQuery()->getResult();
    }
}
