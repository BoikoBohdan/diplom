<?php

namespace App\Components\Export;

use App\Components\Export\Contract\Export;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Storage;

class ExportEXCEL implements Export
{
    private $columns;

    private $data;

    private $fname = 'export';

    public function setColumnNames (array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function setData (array $data)
    {
        $this->data = array_map(function ($item) {
            if (is_array($item))
                return array_values($item);
            else
                return [$item];
        }, $data);
        return $this;
    }

    public function setFileName (string $fname)
    {
        $this->fname = $fname;
        return $this;
    }

    public function generate ()
    {
        Storage::deleteDirectory('export');

        $spreadsheet = new Spreadsheet();
        $cols = array_merge([$this->columns], $this->data);

        $spreadsheet->getActiveSheet()->fromArray($cols, null, 'A1');

        $writer = new Xlsx($spreadsheet);
        $fname = $this->fname . '_' . time() . '.xlsx';
        Storage::makeDirectory('export');
        $writer->save(storage_path('app/export/') . $fname);

        return $fname;
    }
}
