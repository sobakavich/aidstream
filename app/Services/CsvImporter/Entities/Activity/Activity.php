<?php namespace App\Services\CsvImporter\Entities\Activity;

use App\Services\CsvImporter\Entities\Csv;
use App\Services\CsvImporter\Entities\Activity\Components\ActivityRow;

class Activity extends Csv
{
    public function __construct($row)
    {
        $this->row = $row;
    }
}
