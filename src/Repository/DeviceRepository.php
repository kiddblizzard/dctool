<?php

namespace App\Repository;

use App\Entity\Device;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DeviceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Device::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('d')
            ->where('d.something = :value')->setParameter('value', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByKeyword($keyWord = null)
    {
        $query = $this->createQueryBuilder('d');

        if (!is_null($keyWord) && !empty($keyWord)) {
            $query->where($query->expr()->like('d.name', '?1'))
                ->orWhere($query->expr()->like('d.serialNumber', '?1'))
                ->orWhere($query->expr()->like('d.barcode_number', '?1'))
                ->setParameter('1', '%'.$keyWord.'%');
        }
        $query->orderBy('d.id', 'DESC');

        return $query->getQuery();
    }
}
