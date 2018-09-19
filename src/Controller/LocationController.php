<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Location;
use App\Entity\Model;
use App\Entity\Manufacturer;


class LocationController extends Controller
{
    /**
     * show the list of devices
     * @Route("/locations", name="locations")
     * @param  Request $request [description]
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(Request $request)
    {
        $pageNumber = $request->query->get('page', 1);
        $keyWord = $request->query->get('keyWord', null);

        $query = $this->getRepository()->findByKeyword($keyWord);

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $pageNumber,
            10
        );

        return $this->render('location/list.html.twig', array(
            'parts' => $pagination,
            'keyWord' => $keyWord,
            'navbar' => 'location'
        ));
    }

    /**
     * @Route("/locations/new", name="new_location")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function newAction(Request $request)
    {
        $location = new Location();

        $form = $this->getLocationForm(
            $location,
            $this->generateUrl('new_location')
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $part = $form->getData();
            $this->savePart($part);

            return $this->redirectToRoute('locations');
        }

        return $this->render('location/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'location'
        ));
    }

    /**
     * @Route("/locations/{location}/eidt", name="edit_location")
     * @param  Location $location
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function editAction(Location $location, Request $request)
    {
        $form = $this->getPartForm(
            $part,
            $this->generateUrl(
                'edit_location',
                ['location' => $location->getId()]
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $this->savePart($item);

            return $this->redirectToRoute('locations');
        }

        return $this->render('location/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'parts'
        ));
    }

    /**
     * [getPartForm description]
     * @param  Location   $location [description]
     * @param  String $path [description]
     * @return [type]       [description]
     */
    private function getLocationForm(Location $location, $path)
    {
        return $this->createFormBuilder($location)
            ->setAction($path)
            ->setMethod('POST')
            ->add('location', TextType::class, ['required' => false])
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();
    }

    private function savePart($part)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($part);
        $entityManager->flush();
    }

    /**
     * [getRepository description]
     * @return [type] [description]
     */
    private function getRepository() {
        return $this->getDoctrine()->getRepository(Location::class);
    }
}
