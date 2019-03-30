<?php

namespace App\Controller;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use App\Entity\Device;
use App\Entity\Model;
use App\Entity\Manufacturer;
use App\Controller\Traits\HasRepositories;

class AjaxController extends FOSRestController
{
    use HasRepositories;

    /**
     * show the list of devices
     * @Get("/ajax/manufacturer/{manufacturer}")
     * @View(serializerGroups={"Default"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteManufacturerAction(Manufacturer $manufacturer)
    {
        return $manufacturer->getModels();
    }

    private function getModelRepository()
    {
        return $this->getDoctrine()->getRepository(Model::class);
    }

}
