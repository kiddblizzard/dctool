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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Entity\Rack;
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
            $this->saveEntity($device);

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
            $this->saveEntity($device);

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

                    $rackRow = $sheet->getCell('A'.$i);
                    $rackName = $sheet->getCell('B'.$i);
                    $systemName = $sheet->getCell('C'.$i);
                    $powerSource = $sheet->getCell('D'.$i);
                    $unit = $sheet->getCell('E'.$i);
                    $manufacturer = $sheet->getCell('F'.$i);
                    $model = $sheet->getCell('G'.$i);
                    $platform = $sheet->getCell('H'.$i);
                    $sn = $sheet->getCell('I'.$i);
                    $name = $sheet->getCell('J'.$i);
                    $rps = $sheet->getCell('L'.$i);
                    $barcode = $sheet->getCell('M'.$i);

                    $rackEntity = $this->getRackRepository()
                        ->findOneByName($rackName);

                    if (is_null($rackEntity)) {
                        $rackEntity = New Rack();
                        $rackEntity->setName($rackName);
                        $rackEntity->setRackRow($rackRow);
                        $this->saveEntity($rackEntity);
                    }

                    $manufacturerEntity = $this->getManuRepository()
                        ->findOneByName($manufacturer);

                    if (is_null($manufacturerEntity)) {
                        $manufacturerEntity = New Manufacturer();
                        $manufacturerEntity->setName($manufacturer);
                        $this->saveEntity($manufacturerEntity);
                    }

                    $modelEntity = $this->getModelRepository()
                        ->getOneByMM(
                            $manufacturerEntity,
                            $model
                        );

                    if (is_null($modelEntity)) {

                        if($platform == 'Network') {
                            $modelType == 'switch';
                        }

                        $modelEntity = New Model();
                        $modelEntity->setManufacturer($manufacturerEntity);
                        $modelEntity->setType();
                        $modelEntity->setModel($model);
                        $this->saveEntity($modelEntity);
                    }

                    var_dump($modelEntity);

                    $device = New Device();
                    $device->setRack($rackEntity);
                    $device->setName($name);
                    $device->setUnit($unit);
                    $device->setModel($modelEntity);
                    $device->setSerialNumber($sn);
                    $device->setBarcodeNumber($barcode);

                    $this->saveEntity($device);
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
            ->add('unit', TextType::class, ['required' => false])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'In Depository' => 'in_depository',
                    'Running' => 'running',
                    'Decommission' => 'decommission'
                ],
            ])
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();
    }

    private function saveEntity($entity)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    }

    /**
     * [getRepository description]
     * @return [type] [description]
     */
    private function getRepository() {
        return $this->getDoctrine()->getRepository(Device::class);
    }

    /**
     * [getRepository description]
     * @return [type] [description]
     */
    private function getRackRepository() {
        return $this->getDoctrine()->getRepository(Rack::class);
    }

    private function getManuRepository() {
        return $this->getDoctrine()->getRepository(Manufacturer::class);
    }

    /**
     * [getRepository description]
     * @return [type] [description]
     */
    private function getModelRepository() {
        return $this->getDoctrine()->getRepository(Model::class);
    }
}
