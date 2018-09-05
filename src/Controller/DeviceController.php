<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Device;
use App\Entity\Model;
use App\Entity\Manufacturer;

class DeviceController extends Controller
{
    /**
     * show the list of devices
     * @Route("/devices", name="devices")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list()
    {
        $devices = $this->getDoctrine()
        ->getRepository(Device::class)
        ->findAll();

        return $this->render('device/list.html.twig', array(
            'devices' => $devices,
        ));
    }

    /**
     * @Route("/devices/new", name="new_device")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function newAction(Request $request)
    {
        $device = new Device();

        $manufacturers = $this->getDoctrine()
        ->getRepository(Manufacturer::class)
        ->findAll();

        $form = $this->getDeivceForm(
            $device,
            $this->generateUrl('new_device')
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $device = $form->getData();
            $this->saveDevice($device);

            return $this->redirectToRoute('devices');
        }

        return $this->render('device/new.html.twig', array(
            'manufacturers' => $manufacturers,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/devices/{device}/eidt", name="edit_device")
     * @param  Device $device
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function editAction(Device $device, Request $request)
    {
        $form = $this->getDeivceForm(
            $device,
            $this->generateUrl(
                'edit_device',
                ['device' => $device->getId()]
            )
        );

        $manufacturers = $this->getDoctrine()
            ->getRepository(Manufacturer::class)
            ->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $device = $form->getData();
            $this->saveDevice($device);

            return $this->redirectToRoute('devices');
        }

        return $this->render('device/new.html.twig', array(
            'manufacturers' => $manufacturers,
            'form' => $form->createView()
        ));
    }

    /**
     * [getDeivceForm description]
     * @param  Device $device [description]
     * @param  String $path   [description]
     * @return [type]         [description]
     */
    private function getDeivceForm(Device $device, $path)
    {
        return $this->createFormBuilder($device)
            ->setAction($path)
            ->setMethod('POST')
            ->add('model', EntityType::class, [
                'class' => Model::class,
                'choice_label' => 'model',
                'group_by' => 'manufacturer'
            ])
            ->add('serialNumber', TextType::class, ['required' => false])
            ->add('name', TextType::class, ['required' => false])
            ->add('barcode_number', TextType::class, ['required' => false])
            ->add('po', TextType::class, ['required' => false])
            ->add('rack', TextType::class, ['required' => false])
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();
    }

    private function saveDevice($device)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($device);
        $entityManager->flush();
    }
}
