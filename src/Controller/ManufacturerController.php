<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Manufacturer;
use App\Entity\Location;
use App\Controller\Traits\HasRepositories;


class ManufacturerController extends Controller
{
    use HasRepositories;

    /**
     * show the list of devices
     * @Route("/manufacturers", name="manufacturers")
     * @param  Request $request [description]
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(Request $request)
    {
        $pageNumber = $request->query->get('page', 1);
        $keyWord = $request->query->get('keyWord', null);

        $query = $this->getManufacturerRepository()->findByKeyword($keyWord);

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $pageNumber,
            10
        );

        return $this->render('manufacturer/list.html.twig', array(
            'manufacturers' => $pagination,
            'keyWord' => $keyWord,
            'navbar' => 'manufacturer'
        ));
    }

    /**
     * @Route("/manufacturers/new", name="new_manufacturer")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function newAction(Request $request)
    {
        $manufacturer = new Manufacturer();

        $manufacturers = $this->getManufacturerRepository()->findAll();

        $form = $this->getManufacturerForm(
            $manufacturer,
            $this->generateUrl('new_manufacturer')
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manufacturer = $form->getData();
            $this->saveManufacturer($manufacturer);

            return $this->redirectToRoute('manufacturers');
        }

        return $this->render('manufacturer/new.html.twig', array(
            'manufacturers' => $manufacturers,
            'form' => $form->createView(),
            'navbar' => 'manufacturer'
        ));
    }

    /**
     * @Route("/manufacturers/{manufacturer}/eidt", name="edit_manufacturer")
     * @param  Manufacturer $manufacturer
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function editAction(Manufacturer $manufacturer, Request $request)
    {
        $form = $this->getManufacturerForm(
            $manufacturer,
            $this->generateUrl(
                'edit_manufacturer',
                ['manufacturer' => $manufacturer->getId()]
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $this->savePart($item);

            return $this->redirectToRoute('manufacturers');
        }

        return $this->render('manufacturer/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'manufacturer'
        ));
    }

    /**
     * @Route("/manufacturers/{manufacturer}/delete", name="delete_manufacturer")
     * @param  Manufacturer $manufacturer
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function deleteAction(Manufacturer $manufacturer)
    {
        // var_dump($manufacturer);

        try{
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($manufacturer);
            $entityManager->flush();

            return $this->redirectToRoute('manufacturers');
        } catch (Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
    }

    /**
     * [getManufacturerForm description]
     * @param  Manufacturer   $manufacturer [description]
     * @param  String  $path [description]
     * @return [type]       [description]
     */
    private function getManufacturerForm(Manufacturer $manufacturer, $path)
    {
        return $this->createFormBuilder($manufacturer)
            ->setAction($path)
            ->setMethod('POST')
            ->add('name', TextType::class, ['required' => false])
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();
    }

    private function saveManufacturer($manufacturer)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($manufacturer);
        $entityManager->flush();
    }

}
