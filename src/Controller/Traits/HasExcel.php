<?php

namespace App\Controller\Traits;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

trait HasExcel
{
    var $modelExcel = [
            'A' => [
                'label' => "manufacturer",
                'column' => "manufacturer",
                'required' => true
            ],
            'B' => [
                'label' => "model",
                'column' => "model",
                'required' => true
            ],
            'C' => [
                'label' => "categorytag",
                'column' => "type",
                'required' => true
            ],
        ];
    var $deviceExcel = [
            'A' => [
                'label' => "row name",
                'column' => "row_name",
                'required' => true
            ],
            'B' => [
                'label' => "rack id",
                'column' => "rack_name",
                'required' => true
            ],
            'C' => [
                'label' => "system name",
                'column' => "c_system_alias_name",
                'required' => true
            ],
            'D' => [
                'label' => "power source",
                'column' => "power_source",
                'required' => true
            ],
            'E' => [
                'label' => "device rack slot",
                'column' => "unit",
                'required' => true
            ],
            'F' => [
                'label' => "manufacturer",
                'column' => "manufacturer",
                'required' => true
            ],
            'G' => [
                'label' => "model",
                'column' => "model",
                'required' => true
            ],
            'H' => [
                'label' => "platform",
                'column' => "platform",
                'required' => true
            ],
            'I' => [
                'label' => "serial number",
                'column' => "serial_number",
                'required' => true
            ],
            'J' => [
                'label' => "host name",
                'column' => "device_name",
                'required' => true
            ],
            'K' => [
                'label' => "aperture",
                'column' => "aperture",
                'required' => false
            ],
            'L' => [
                'label' => "rps",
                'column' => "rps",
                'required' => false
            ],
            'M' => [
                'label' => "barcode",
                'column' => "barcode",
                'required' => true
            ],
            'N' => [
                'label' => "support chg",
                'column' => "support_chg",
                'required' => false
            ],
        ];

    private function getExcelSheet($path)
    {
        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);

        return $spreadsheet->getSheet(0);
    }

    private function isModelExcel($sheet)
    {
        return $this->isVailExcel($this->modelExcel, $sheet);
    }

    private function isDeviceExcel($sheet)
    {
        return $this->isVailExcel($this->deviceExcel, $sheet);
    }

    private function sheetToArray($sheet, $i, $type)
    {
        $arr = [];
        $arr['status'] = "Existed";
        $format = [];

        if ($type == 'model') {
            $format = $this->modelExcel;
        } else if($type = 'device') {
            $format = $this->deviceExcel;
        }

        foreach($format as $key => $val) {
            $arr[$val['column']] = $sheet->getCell($key.$i)->getValue();
            if (
                $val['required'] == true && (
                    is_null($arr[$val['column']]) ||
                    empty($arr[$val['column']])
                )
            ) {
                $arr['status'] = 'Data Incomplete';
            }
        }

        return $arr;
    }

    private function isVailExcel($array, $sheet)
    {
        foreach($array as $key => $val) {
            if (
                strtolower($sheet->getCell($key.'1')->getValue()) !== $val['label']
            ) {
                return false;
            }
        }

        return true;
    }
}
