<?php

namespace App\Controller\Traits;

use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Entity\Manufacturer;
use App\Entity\Bau;
use App\Entity\Device;
use App\Entity\Part;
use App\Entity\Rack;
use App\Entity\Model;
use App\Entity\Receiving;
use App\Entity\PowerSource;

trait HasRepositories
{
    /**
     * [getManufacturerRepository description]
     * @return [type] [description]
     */
    private function getManufacturerRepository()
    {
        return $this->getDoctrine()->getRepository(Manufacturer::class);
    }

    /**
     * [getBauRepository description]
     * @return [type] [description]
     */
    private function getBauRepository () {
        return $this->getDoctrine()->getRepository(Bau::class);
    }

    /**
     * [getDeviceRepository description]
     * @return [type] [description]
     */
    private function getDeviceRepository() {
        return $this->getDoctrine()->getRepository(Device::class);
    }

    /**
     * [getRackRepository description]
     * @return [type] [description]
     */
    private function getRackRepository() {
        return $this->getDoctrine()->getRepository(Rack::class);
    }

    /**
     * [getModelRepository description]
     * @return [type] [description]
     */
    private function getModelRepository() {
        return $this->getDoctrine()->getRepository(Model::class);
    }

    /**
     * [getPartRepository description]
     * @return [type] [description]
     */
    private function getPartRepository() {
        return $this->getDoctrine()->getRepository(Part::class);
    }

    /**
     * [getTaskRepository description]
     * @return [type] [description]
     */
    private function getTaskRepository () {
        return $this->getDoctrine()->getRepository(Task::class);
    }

    /**
     * [getReceivingRepository description]
     * @return [type] [description]
     */
    private function getReceivingRepository () {
        return $this->getDoctrine()->getRepository(Receiving::class);
    }

    /**
     * [getReceivingRepository description]
     * @return [type] [description]
     */
    private function getPowerSourceRepository () {
        return $this->getDoctrine()->getRepository(PowerSource::class);
    }

    private function save($entity)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    }
}
