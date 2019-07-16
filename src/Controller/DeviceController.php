<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Button;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Entity\Rack;
use App\Entity\Device;
use App\Entity\Model;
use App\Entity\Manufacturer;
use App\Controller\Traits\HasRepositories;
use App\Service\FileUploader;

class DeviceController extends Controller
{
    use HasRepositories;

    /**
     * show the list of devices
     * @Route("/devices", name="devices")
     * @param  Request $request [description]
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $session = new Session();
        $site = $this->getSiteRepository()->find($session->get('site'));
        $pageNumber = $request->query->get('page', 1);
        $keyWord = $request->query->get('keyWord', null);

        $query = $this->getDeviceRepository()->findByKeyword($keyWord);

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $pageNumber,
            10
        );

        $form = $this->getDeviceExcelForm(
            $this->generateUrl('ajax_device_upload_excel')
        );

        return $this->render('device/list.html.twig', array(
            'devices' => $pagination,
            'keyWord' => $keyWord,
            'navbar' => 'device',
            'currentRack' => null,
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
        $isEnclosure = false;
        $isBlade = false;
        $device = new Device();
        $form = $this->getDeivceForm(
            $device,
            $this->generateUrl('new_device')
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $device = $form->getData();
            $this->save($device);

            return $this->redirectToRoute('devices');
        }

        return $this->render('device/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'device',
            'isEnclosure' => $isEnclosure,
            'isBlade' => $isBlade
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
        $isEnclosure = false;
        $isBlade = false;

        if(!is_null($device->getModel())) {
            if ('ENCLOSURE' == $device->getModel()->getType()) {
                $isEnclosure = true;
            } else if ('BLADE' == $device->getModel()->getType()) {
                $isBlade = true;
            }
        }

        $form = $this->getDeivceForm(
            $device,
            $this->generateUrl(
                'edit_device',
                ['device' => $device->getId()]
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $device = $form->getData();
            $this->save($device);

            return $this->redirectToRoute('devices');
        }

        return $this->render('device/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'device',
            'isEnclosure' => $isEnclosure,
            'isBlade' => $isBlade
        ));
    }

    /**
     * @Route("/devices/{device}", name="device_detail")
     * @param  Device $device
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function getDetailAction(Device $device)
    {
        $isEnclosure = false;
        $isBlade = false;
        $enclosureForm = $this->getEnclosureForm($device);

        if(!is_null($device->getModel())) {
            if ('ENCLOSURE' == $device->getModel()->getType()) {
                $isEnclosure = true;
            } else if ('BLADE' == $device->getModel()->getType()) {
                $isBlade = true;
            }
        }

        return $this->render('device/detail.html.twig', array(
            'navbar' => 'device',
            'device' => $device,
            'enclosureForm' => $enclosureForm->createView(),
            'isEnclosure' => $isEnclosure,
            'isBlade' => $isBlade,
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
                    $modelName = $sheet->getCell('G'.$i);
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
                        $this->save($rackEntity);
                    }

                    $manufacturerEntity = $this->getManuRepository()
                        ->findOneByName($manufacturer);

                    if (is_null($manufacturerEntity)) {
                        $manufacturerEntity = New Manufacturer();
                        $manufacturerEntity->setName($manufacturer);
                        $this->save($manufacturerEntity);
                    }

                    $modelEntity = $this->getModelRepository()
                        ->getOneByManuModelName(
                            $manufacturerEntity,
                            $modelName
                        );

                    if (is_null($modelEntity)) {

                        if($platform == 'Network') {
                            $modelType == 'switch';
                        }

                        $modelEntity = New Model();
                        $modelEntity->setManufacturer($manufacturerEntity);
                        $modelEntity->setType();
                        $modelEntity->setModel($model);
                        $this->save($modelEntity);
                    }

                    $device = New Device();
                    $device->setRack($rackEntity);
                    $device->setName($name);
                    $device->setUnit($unit);
                    $device->setModel($modelEntity);
                    $device->setSerialNumber($sn);
                    $device->setBarcodeNumber($barcode);

                    $this->save($device);
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
        $isEnclosure = false;
        $isBlade = false;

        if(!is_null($device->getModel())) {
            if ('ENCLOSURE' == $device->getModel()->getType()) {
                $isEnclosure = true;
            } else if ('BLADE' == $device->getModel()->getType()) {
                $isBlade = true;
            }
        }

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
            ->add('rack', EntityType::class, [
                'placeholder' => 'Choose an option',
                'class' => Rack::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('unit', IntegerType::class, ['required' => false])
            ->add('status', ChoiceType::class, [
                'placeholder' => 'Choose an option',
                'choices'  => [
                    'In Depository' => 'in_depository',
                    'Running' => 'running',
                    'Isolated' => 'isolated',
                    'Decommissioned' => 'decommissioned'
                ],
            ])
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();
    }

    private function getEnclosureForm(Device $device)
    {
        return $this->createFormBuilder($device)
            ->add('id', HiddenType::class)
            ->add('parent', EntityType::class, [
                'placeholder' => 'Choose an option',
                'class' => Device::class,
                'choices'  => $this->getDeviceRepository()->findEnclosures(false),
                'choice_label' => 'name',
                'label' => 'Enclosure',
                'required' => false,
            ])
            ->getForm();
    }

}
