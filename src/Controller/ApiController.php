<?php

namespace App\Controller;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\View;
use App\Entity\Device;
use App\Entity\Model;
use App\Entity\Manufacturer;

class ApiController extends FOSRestController
{
    /**
     * show the list of devices
     * @Route("/api/types/{type}/manufacturers")
     * @View(serializerGroups={"Default"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getManufacturerByTypeAction($type)
    {
        $result = $this->getDoctrine()
        ->getRepository(Model::class)
        ->findManufacturerByType($type);

        // var_dump($result);

        $view = $this->view($result, 200)
            ->setTemplate("api/api.twig")
            ->setTemplateVar('manufacturers')
        ;

        return $this->handleView($view);
    }

    private function getTagsRepository()
    {
        return  $this->getDoctrine()
            ->getRepository('Model');
    }
}
