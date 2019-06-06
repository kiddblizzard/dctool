<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Part;
use App\Entity\Model;
use App\Entity\Manufacturer;
use App\Entity\Location;
use App\Controller\Traits\HasRepositories;

class PartController extends Controller
{
    use HasRepositories;

    /**
     * show the list of devices
     * @Route("/parts", name="parts")
     * @param  Request $request [description]
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(Request $request)
    {
        $pageNumber = $request->query->get('page', 1);
        $keyWord = $request->query->get('keyWord', null);

        $query = $this->getPartRepository()->findByKeyword($keyWord);

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $pageNumber,
            10
        );

        return $this->render('part/list.html.twig', array(
            'parts' => $pagination,
            'keyWord' => $keyWord,
            'navbar' => 'parts'
        ));
    }

    /**
     * @Route("/parts/new", name="new_part")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function newAction(Request $request)
    {
        $part = new Part();

        $manufacturers = $this->getManufacturerRepository()->findAll();

        $form = $this->getPartForm(
            $part,
            $this->generateUrl('new_part')
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $part = $form->getData();
            $this->savePart($part);

            return $this->redirectToRoute('parts');
        }

        return $this->render('part/new.html.twig', array(
            'manufacturers' => $manufacturers,
            'form' => $form->createView(),
            'navbar' => 'parts'
        ));
    }

    /**
     * @Route("/parts/{part}/eidt", name="edit_part")
     * @param  Part $part
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function editAction(Part $part, Request $request)
    {
        $form = $this->getPartForm(
            $part,
            $this->generateUrl(
                'edit_part',
                ['part' => $part->getId()]
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $this->savePart($item);

            return $this->redirectToRoute('parts');
        }

        return $this->render('part/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'parts'
        ));
    }

    /**
     * [getPartForm description]
     * @param  Part   $part [description]
     * @param  String $path [description]
     * @return [type]       [description]
     */
    private function getPartForm(Part $part, $path)
    {
        return $this->createFormBuilder($part)
            ->setAction($path)
            ->setMethod('POST')
            ->add('model', EntityType::class, [
                'class' => Model::class,
                'choice_label' => 'model',
                'group_by' => 'manufacturer'
            ])
            ->add('amount', TextType::class, ['required' => false])


            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'Location'
            ])
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();
    }

    private function savePart($part)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($part);
        $entityManager->flush();
    }

}
