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
            ->orderBy('d.unit');

        return $query->getQuery();
    }

    public function findOneBySerialNumberOrNameOrBarcode(
        $serialNumber,
        $name,
        $barcode
    ) {
        $query = $this->createQueryBuilder('d')
            ->where('d.serial_number = :serial_number')
            ->orWhere('d.name = :name')
            ->orWhere('d.barcode_number = :barcode')
            ->setParameter('serial_number', $serialNumber)
            ->setParameter('name', $name)
            ->setParameter('barcode', $barcode)
            ->orderBy('d.id', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function findByRackByUnit($rack, $unit)
    {
        $query = $this->createQueryBuilder('d');
        $query->leftJoin('d.model',  'm');
        $query->Where('d.rack = :rack')
            ->andWhere('d.unit + m.height - 1 = :unit')
            ->andWhere($query->expr()->orX(
                    'd.status = ?1',
                    'd.status = ?2'
            ))
            ->setParameter('rack', $rack)
            ->setParameter('unit', $unit)
            ->setParameter('1', 'isolated')
            ->setParameter('2', 'running');

        return $query->getQuery()->getOneOrNullResult();
    }

    public function findByRackByTopUnit($rack, $unit)
    {
        $query = $this->createQueryBuilder('d');
        $query->leftJoin('d.model',  'm');
        $query->Where('d.rack = :rack')
            ->andWhere('d.unit - m.height + 1 = :unit')
            ->andWhere($query->expr()->orX(
                    'd.status = ?1',
                    'd.status = ?2'
            ))
            ->setParameter('rack', $rack)
            ->setParameter('unit', $unit)
            ->setParameter('1', 'isolated')
            ->setParameter('2', 'running');

        return $query->getQuery()->getOneOrNullResult();
    }

    public function findEnclosures($decom = false){

        $query = $this->createQueryBuilder('d');
        $query->leftJoin('d.model',  'm')
            ->where('m.type = :type');
        if (!$decom) {
            $query->andWhere(
                $query->expr()->orX(
                    'd.status = ?1',
                    'd.status = ?2',
                    'd.status = ?3'
                ))
                ->setParameter('1', 'isolated')
                ->setParameter('2', 'in_depository')
                ->setParameter('3', 'running');
        }

        $query->setParameter('type', 'ENCLOSURE')
            ->orderBy('d.id', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function findBlades(Device $parent, $keyWord, $limit, $decom = false)
    {
        $query = $this->createQueryBuilder('d');
        $query->leftJoin('d.model',  'm')
            ->where('m.type = :type')
            ->andWhere($query->expr()->like('d.name', ':keyWord'))
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->neq('d.parent', ':parent'),
                    $query->expr()->isNull('d.parent')
                )
            );
        if (!$decom) {
            $query->andWhere(
                $query->expr()->orX(
                    'd.status = ?1',
                    'd.status = ?2',
                    'd.status = ?3'
                ))
                ->setParameter('1', 'isolated')
                ->setParameter('2', 'in_depository')
                ->setParameter('3', 'running');
        }

        $query->setParameter('type', 'BLADE')
            ->setParameter('keyWord', '%'.$keyWord.'%')
            ->setParameter('parent', $parent)
            ->setMaxResults($limit)
            ->orderBy('d.id', 'ASC');

        return $query->getQuery()->getResult();
    }
}
