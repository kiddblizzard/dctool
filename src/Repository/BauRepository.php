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

    /**
    **/
    public function findByKeyword($keyWord = null, $site)
    {
        $query = $this->createQueryBuilder('b');

        if (!is_null($keyWord) && !empty($keyWord)) {
            $query->where($query->expr()->like('b.description', '?1'))
                ->setParameter('1', '%'.$keyWord.'%');
        }
        $query->andWhere('b.site = :site')
            ->setParameter('site', $site)
            ->orderBy('b.start_time', 'ASC');

        return $query->getQuery()->getResult();
    }


    public function findForHome($site)
    {
        $query = $this->createQueryBuilder('b');
        $query->where($query->expr()->orX(
                'b.status = ?1',
                'b.status = ?2'
            ))
            ->andWhere('b.site = :site')
            ->orderBy('b.start_time', 'ASC')
            ->setParameter('1', 'pending')
            ->setParameter('2', 'deployment')
            ->setParameter('site', $site);

        return $query->getQuery()->getResult();
    }
}
