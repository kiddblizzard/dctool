<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Entity\Device;
use App\Entity\Model;
use App\Entity\Manufacturer;
use App\Service\FileUploader;

class DeviceController extends Controller
{
    /**
     * show the list of devices
     * @Route("/devices", name="devices")
     * @param  Request $request [description]
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
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

        $form = $this->getDeviceExcelForm(
            $this->generateUrl('device_upload_excel')
        );

        return $this->render('device/list.html.twig', array(
            'devices' => $pagination,
            'keyWord' => $keyWord,
            'navbar' => 'device',
            'form' => $form->createView()
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
            'form' => $form->createView(),
            'navbar' => 'device'
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
            'form' => $form->createView(),
            'navbar' => 'device'
        ));
    }

    /**
     * @Route("/devices/upload", name="device_upload_excel")
     * @param  Request $request [description]
     * @param  FileUploader $fileUploader
     * @return [type] [description]
     */
    public function uploadExcelAction(Request $request, FileUploader $fileUploader)
    {
        $form = $this->getDeviceExcelForm(
            $this->generateUrl('device_upload_excel')
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $file = $form['attachment']->getData();

            $fileName = $fileUploader->upload($file);
            $path = $fileUploader->getTargetDirectory().$fileName;

            try {
                /** Load $inputFileName to a Spreadsheet Object  **/
                $reader = new Xlsx();
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($path);
                $sheet = $spreadsheet->getSheet(0);
                $totalRow = $sheet->getHighestRow();

                for ($i = 2; $i <= $totalRow; $i++) {
                

                }
            } catch(\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                die('Error loading file: '.$e->getMessage());
            }
        }
        exit;
    }



    private function getDeviceExcelForm($path)
    {
        return $this->createFormBuilder()
            ->setAction($path)
            ->setMethod('POST')
            ->add('attachment', FileType::class)
            ->add('save', SubmitType::class, array('label' => 'Upload'))
            ->getForm();
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

    /**
     * [getRepository description]
     * @return [type] [description]
     */
    private function getRepository() {
        return $this->getDoctrine()->getRepository(Device::class);
    }
}
