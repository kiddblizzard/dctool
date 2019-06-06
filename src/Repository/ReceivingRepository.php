<?php

namespace App\Repository;

use App\Entity\Receiving;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Receiving|null find($id, $lockMode = null, $lockVersion = null)
 * @method Receiving|null findOneBy(array $criteria, array $orderBy = null)
 * @method Receiving[]    findAll()
 * @method Receiving[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceivingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Receiving::class);
    }

    // /**
    //  * @return Receiving[] Returns an array of Receiving objects
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
    public function findOneBySomeField($value): ?Receiving
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByKeyword($keyWord = null)
    {
        $query = $this->createQueryBuilder('r');

        if (!is_null($keyWord) && !empty($keyWord)) {
            $query->where($query->expr()->like('r.detail', '?1'))
                ->setParameter('1', '%'.$keyWord.'%');
        }
        $query->orderBy('r.planned_date', 'ASC');

        return $query->getQuery();
    }

    public function findForHome($site)
    {
        $query = $this->createQueryBuilder('r');
        $query->where('r.status = :status')
            ->andWhere('r.site = :site')
            ->setParameter('status', 'new')
            ->setParameter('site', $site);

        $query->orderBy('r.planned_date', 'ASC');

        return $query->getQuery()->getResult();
    }
}
