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
                ->orWhere($query->expr()->like('d.serial_number', '?1'))
                ->orWhere($query->expr()->like('d.barcode_number', '?1'))
                ->setParameter('1', '%'.$keyWord.'%');
        }
        $query->orderBy('d.id', 'DESC');

        return $query->getQuery();
    }

    public function findByRackAndKeyword($rack, $keyWord = null)
    {
        $query = $this->createQueryBuilder('d');

        if (!is_null($keyWord) && !empty($keyWord)) {
            $query->where(
                $query->expr()->orX(
                    $query->expr()->like('d.name', '?1'),
                    $query->expr()->like('d.serial_number', '?1'),
                    $query->expr()->like('d.barcode_number', '?1')
                ))
                ->setParameter('1', '%'.$keyWord.'%');
        }
        $query->andWhere('d.rack = :rack')
            ->setParameter('rack', $rack)
            ->orderBy('d.id', 'DESC');

        return $query->getQuery();
    }

    public function findOneBySerialNumberOrNameOrBarcode(
        $serialNumber,
        $name,
        $barcode
    ) {
        $a = $this->createQueryBuilder('d')
            ->where('d.serial_number = :serial_number')
            ->orWhere('d.name = :name')
            ->orWhere('d.barcode_number = :barcode')
            ->setParameter('serial_number', $serialNumber)
            ->setParameter('name', $name)
            ->setParameter('barcode', $barcode)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $a;
    }
}
