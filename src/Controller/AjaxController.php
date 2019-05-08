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
use App\Entity\Manufacturer;
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

                return ;
            }

            $result = [];
            for ($i = 2; $i <= $totalRow; $i++) {
                $array = $this->sheetToArray($sheet, $i);

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

                array_push($this->result['result'], $array);
            }
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

            if (!$this->isModelExcel($sheet)) {
                $this->result['error'] = 1;
                $this->result['msg'] = "Excel Format Wrong";

                return ;
            }

            $result = [];
            for ($i = 2; $i <= $totalRow; $i++) {
                $array = $this->sheetToArray($sheet, $i);

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

                array_push($this->result['result'], $array);
            }
        } catch(\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            die('Error loading file: '.$e->getMessage());
        }

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

}
