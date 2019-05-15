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
use FOS\RestBundle\Controller\Annotations\Post;
use App\Entity\Device;
use App\Entity\Model;
use App\Entity\PowerSource;
use App\Entity\Manufacturer;
use App\Entity\Rack;
use App\Controller\Traits\HasRepositories;
use App\Controller\Traits\HasExcel;
use App\Service\FileUploader;

class AjaxController extends FOSRestController
{
    use HasRepositories;
    use HasExcel;

    var $result = [
        "error" => 0,
        "msg" => "",
        "result" => [],
        ];

    /**
     * @Post("/ajax/models/upload", name="ajax_model_upload_excel")
     * @param  Request $request [description]
     * @param  FileUploader $fileUploader
     * @return [type] [description]
     */
    public function uploadModelExcelAction(
        Request $request,
        FileUploader $fileUploader
    ){
        $file = $request->files->get('file');
        $fileName = $fileUploader->upload($file);
        $path = $fileUploader->getTargetDirectory().$fileName;

        try {
            /** Load $inputFileName to a Spreadsheet Object  **/
            $sheet = $this->getExcelSheet($path);
            $totalRow = $sheet->getHighestRow();

            if (!$this->isModelExcel($sheet)) {
                $this->result['error'] = 1;
                $this->result['msg'] = "Excel Format Wrong";

                return $this->result;
            }

            $result = [];
            for ($i = 2; $i <= $totalRow; $i++) {
                $array = $this->sheetToArray($sheet, $i, 'model');
                if ($array['status'] != 'Data Incomplete') {
                    $manufacturer = $this->getManufacturerRepository()
                        ->findOneByName($array['manufacturer']);

                    if (!is_null($manufacturer)) {
                        $model = $this->getModelRepository()
                            ->getOneByManuModelName(
                                $manufacturer,
                                $array['model']
                            );
                    } else {
                        $manufacturer = New Manufacturer();
                        $manufacturer->setName($array['manufacturer']);
                        $this->save($manufacturer);
                        $model = NULL;
                    }

                    if (is_null($model)) {
                        $model = New Model();
                        $model->setModel($array['model']);
                        $model->setType($array['type']);
                        $model->setManufacturer($manufacturer);
                        $this->save($model);
                        $array['status'] = 'Added';
                    }
                }

                array_push($result, $array);
            }
            $this->result['result'] = $result;
        } catch(\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            die('Error loading file: '.$e->getMessage());
        }

        return $this->result;
    }

    /**
     * @Post("/ajax/devices/upload", name="ajax_device_upload_excel")
     * @param  Request $request [description]
     * @param  FileUploader $fileUploader
     * @return [type] [description]
     */
    public function uploadDeviceExcelAction(
        Request $request,
        FileUploader $fileUploader
    ) {
        $file = $request->files->get('file');
        $fileName = $fileUploader->upload($file);
        $path = $fileUploader->getTargetDirectory().$fileName;

        try {
            /** Load $inputFileName to a Spreadsheet Object  **/
            $sheet = $this->getExcelSheet($path);
            $totalRow = $sheet->getHighestRow();

            if (!$this->isDeviceExcel($sheet)) {
                $this->result['error'] = 1;
                $this->result['msg'] = "Excel Format Wrong";

                return $this->result;
            }

            $result = [];
            for ($i = 2; $i <= $totalRow; $i++) {
                $array = $this->sheetToArray($sheet, $i, 'device');
                if ($array['status'] != 'Data Incomplete') {
                    $rack = $this->getRackRepository()
                        ->findOneByName($array['rack_name']);

                    if (is_null($rack)) {
                        $rack = New Rack();
                        $rack->setName($array['rack_name']);
                        $rack->setRackRow($array['row_name']);
                        $this->save($rack);
                    }

                    $manufacturer = $this->getManufacturerRepository()
                        ->findOneByName($array['manufacturer']);
                    if (is_null($manufacturer)) {
                        $array['status'] = 'Model Not Existing';
                    } else {
                        $model = $this->getModelRepository()
                            ->getOneByManuModelName(
                                $manufacturer,
                                $array['model']
                            );

                        if (is_null($model)) {
                            $array['status'] = 'Model Not Existing';
                        } else {
                            $PSs = $this->getPSEntity($array['power_source']);
                            $devices = $this->getDeviceRepository()
                                ->findOneBySerialNumberOrNameOrBarcode(
                                    $array['device_name'],
                                    $array['serial_number'],
                                    $array['barcode']
                                );

                            if (count($devices)>1) {
                                $array['status'] = 'Conflict';
                            } else if (count($devices) == 1) {
                                $this->createOrUpdateDevice(
                                    $array,
                                    $model,
                                    $rack,
                                    $PSs,
                                    $devices[0]
                                );
                                $array['status'] = 'Updated';
                            } else {
                                $this->createOrUpdateDevice(
                                    $array,
                                    $model,
                                    $rack,
                                    $PSs
                                );
                                $array['status'] = 'Added';
                            }
                        }
                    }
                }
                array_push($result, $array);
            }
            $this->result['result'] = $result;
        } catch(\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            die('Error loading file: '.$e->getMessage());
        }

        return $this->result;
    }

    private function createOrUpdateDevice(
        Array $array,
        Model $model,
        Rack $rack,
        Array $PSs,
        Device $device = null
    ) {
        if (is_null($device)) {
            $device = New Device();
        }

        foreach ($PSs as $ps) {
            $device->addPowerSource($ps);
        }

        $device->setName($array['device_name']);
        $device->setSerialNumber($array['serial_number']);
        $device->setModel($model);
        $device->setRack($rack);
        $device->setUnit($array['unit']);
        $device->setBarcodeNumber($array['barcode']);
        $device->setSupportChg($array['support_chg']);
        $device->setPlatform($array['platform']);
        $device->setCSystemAliasName($array['c_system_alias_name']);
        $this->save($device);
    }

    private function getPSEntity($string)
    {
        $arr = explode('/', $string);
        $arrEntity = [];

        foreach ($arr as $name) {
            $powerSource = $this->getPowerSourceRepository()
                ->findOneByName(trim($name));

            if (is_null($powerSource)) {
                $powerSource = New PowerSource();
                $powerSource->setName(trim($name));
                $this->save($powerSource);
            }
            array_push($arrEntity, $powerSource);
        }
        return $arrEntity;
    }

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

}
