<?php

namespace App\Repository;

use App\Entity\Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ModelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Model::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('m')
            ->where('m.something = :value')->setParameter('value', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

   public function findManufacturerByType($type)
   {
       $a = $this->createQueryBuilder('m')
           ->where('m.type = :value')
           ->setParameter('value', $type)
           ->orderBy('m.id', 'ASC')
           ->getQuery();

       return $a->getOneOrNullResult();
   }

   public function getOneByMM($manufacturer, $model)
   {
       $a = $this->createQueryBuilder('m')
           ->where('m.manufacturer = :manufacturer')
           ->andWhere('m.model = :model')
           ->setParameter('manufacturer', $manufacturer)
           ->setParameter('model', $model)
           ->orderBy('m.id', 'ASC')
           ->getQuery();

       return $a->getOneOrNullResult();
   }

   public function findByKeyword($keyWord = null)
   {
       $query = $this->createQueryBuilder('m');

       if (!is_null($keyWord) && !empty($keyWord)) {
           $query->where($query->expr()->like('m.model', '?1'))
               ->setParameter('1', '%'.$keyWord.'%');
       }
       $query->orderBy('m.id', 'DESC');

       return $query->getQuery();
   }
}
