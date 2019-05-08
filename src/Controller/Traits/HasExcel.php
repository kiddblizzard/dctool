<?php

namespace App\Controller\Traits;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

trait HasExcel
{
    var $modelExcel = [
            'A' => 'manufacturer',
            'B' => 'model',
            'C' => 'type',
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

    private function sheetToArray($sheet, $i)
    {
        $arr = [];
        $arr['status'] = "Existed";
        foreach($this->modelExcel as $key => $val) {
            $arr[$val] = $sheet->getCell($key.$i)->getValue();
            if (is_null($arr[$val]) || empty($arr[$val])) {
                $arr['status'] = 'Data Incomplete';
            }
        }

        return $arr;
    }

    private function isVailExcel($array, $sheet)
    {
        foreach($array as $key => $val) {
            if (
                strtolower($sheet->getCell($key.'1')->getValue()) !== $val
            ) {
                return false;
            }
        }

        return true;
    }
}
