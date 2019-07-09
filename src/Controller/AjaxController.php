<?php

namespace App\Controller;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use App\Entity\Device;
use App\Entity\Model;
use App\Entity\Receiving;
use App\Entity\PowerSource;
use App\Entity\Manufacturer;
use App\Entity\Rack;
use App\Entity\Bau;
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
                                    $array['serial_number'],
                                    $array['device_name'],
                                    $array['barcode']
                                );

                            if (count($devices)>1) {
                                $array['status'] = 'Conflict';
                            } else if (count($devices) == 1) {
                                $device = $devices[0];

                                if ($device->getName()==$array['device_name'] &&
                                    $device->getSerialNumber() == $array['serial_number'] &&
                                    $device->getBarcodeNumber() == $array['barcode']
                                ) {
                                    $this->createOrUpdateDevice(
                                        $array,
                                        $model,
                                        $rack,
                                        $PSs,
                                        $devices[0]
                                    );
                                    $array['status'] = 'Updated';
                                } else {
                                    $array['status'] = 'Conflict';
                                }

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
     * @Put("/ajax/receiving/{receiving}/access", name="ajax_receiving_access")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function putRecevingAccess(Receiving $receiving, Request $request)
    {
        $access = $request->request->get('access');
        $receiving->setAccess($access);
        $receiving->getAccess();
        $this->save($receiving);

        $this->result['result'] = $access;

        return $this->result;
    }

    /**
     * @Put("/ajax/receiving/{receiving}/status", name="ajax_receiving_status")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function putRecevingStatus(Receiving $receiving, Request $request)
    {
        $status = $request->request->get('status');
        $receiving->setStatus($status);
        $this->save($receiving);
        $this->result['result'] = $status;

        return $this->result;
    }

    /**
     * @Put("/ajax/bau/{bau}/status", name="ajax_bau_status")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function putBauStatus(Bau $bau, Request $request)
    {
        $status = $request->request->get('status');
        $bau->setStatus($status);
        $this->save($bau);
        $this->result['result'] = $status;

        return $this->result;
    }

    /**
     * @Put("/ajax/session/site", name="ajax_session_site")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function putSessionSite(Request $request)
    {
        $site = $request->request->get('site');
        $session = new Session();
        $session->set('site', $site);
        $this->result['result'] = $site;

        return $this->result;
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

    /**
     * @Get("/ajax/model/{model}", name="ajax_get_model")
     * @param  Model $model [description]
     * @return [type] [description]
     */
    public function getModel(Model $model)
    {
        $this->result['result'] = $model;

        return $this->result;
    }

    /**
     * @Get("/ajax/autocomplete/enclosure", name="ajax_autocomplete_enclosure")
     * @param  Model $model [description]
     * @return [type] [description]
     */
    public function getEnclosure()
    {

        return $enclosures = $this->getDeviceRepository()->findEnclosures(false);
        // return array_map(
        //     function(Device $device) {
        //         return [
        //             'value' => $device,
        //             'label' => $device->getName()
        //         ];
        //     },
        //     $enclosures
        // );
    }

    /**
     * @Get("/ajax/autocomplete/{device}/blades", name="ajax_autocomplete_enclosure")
     * @param  Request $request [description]
     * @param  Device $device [description]
     * @return [type] [description]
     */
    public function getBlades(Request $request, Device $device)
    {
        $keyWord = $request->query->get('query');
        $limit = $request->query->get('limit');
        $blades = $this->getDeviceRepository()
            ->findBlades($device, $keyWord, $limit, false);

        return array_map(
            function(Device $device) {
                return [
                    'value' => $device,
                    'label' => $device->getName()
                ];
            },
            $blades
        );
    }

    /**
     * @Post("/ajax/devices/{device}/parent", name="ajax_post_device_parent")
     * @param  Request $request [description]
     * @param  Device $device [description]
     * @return [type] [description]
     */
    public function postDeviceParentAction(Request $request, Device $device)
    {
        if (is_null($device)) {
            $this->result['error'] = 1;
            $this->result['msg'] = 'Device is not found.';

            return $this->result;
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data['parent'])) {
            $device->setParent(NULL);
            $this->save($device);

            $this->result['error'] = 0;
            $this->result['msg'] = 'Enclosure has been removed';
            $this->result['device'] = $device;
            return $this->result;
        }

        $parent = $this->getDeviceRepository()->find($data['parent']);

        if (!is_null($parent) && 'ENCLOSURE' == $parent->getModel()->getType()) {
            $parent->addChild($device);
            $this->save($parent);

            $this->result['error'] = 0;
            $this->result['msg'] = 'Enclosure has been added';
            $this->result['device'] = $device;
            return $this->result;
        }

        $this->result['error'] = 1;
        $this->result['msg'] = 'Enclosure is not found.';

        return $this->result;
    }

    /**
     * @Post("/ajax/devices/{device}/child", name="ajax_post_device_child")
     * @param  Request $request [description]
     * @param  Device $device [description]
     * @return [type] [description]
     */
    public function postDeviceChild(Request $request, Device $device)
    {
        if (is_null($device)) {
            $this->result['error'] = 1;
            $this->result['msg'] = 'Device is not found.';

            return $this->result;
        }

        $data = json_decode($request->getContent(), true);

        $child = $this->getDeviceRepository()->find($data['child']);

        if (!is_null($child) && 'BLADE' == $child->getModel()->getType()) {
            $child->setParent($device);
            $this->save($child);

            $this->result['error'] = 0;
            $this->result['msg'] = 'Blade has been added';
            $this->result['device'] = $device;
            return $this->result;
        }

        $this->result['error'] = 1;
        $this->result['msg'] = 'Blade is not found.';

        return $this->result;

    }

    /**
     * @Delete("/ajax/devices/{device}/child", name="ajax_delete_device_child")
     * @param  Request $request [description]
     * @param  Device $device [description]
     * @return [type] [description]
     */
    public function deleteDeviceChild(Request $request, Device $device)
    {
        if (is_null($device)) {
            $this->result['error'] = 1;
            $this->result['msg'] = 'Device is not found.';

            return $this->result;
        }

        $data = json_decode($request->getContent(), true);

        $child = $this->getDeviceRepository()->find($data['child']);

        if (!is_null($child) && 'BLADE' == $child->getModel()->getType()) {
            $device->removeChild($child);
            $this->save($device);

            $this->result['error'] = 0;
            $this->result['msg'] = 'Blade has been removed';
            $this->result['device'] = $device;
            return $this->result;
        }

        $this->result['error'] = 1;
        $this->result['msg'] = 'Blade is not found.';

        return $this->result;

    }
}
