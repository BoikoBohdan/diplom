<?php

namespace App\Components\Export\Contract;

interface Export
{
    public function setColumnNames (array $columns);

    public function setData (array $data);

    public function setFileName (string $fname);

    public function generate ();
}
