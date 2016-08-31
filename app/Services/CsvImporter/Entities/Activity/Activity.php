<?php namespace App\Services\CsvImporter\Entities\Activity;

use App\Services\CsvImporter\Entities\Csv;
use App\Services\CsvImporter\Entities\Activity\Components\ActivityRow;

class Activity extends Csv
{
    public function __construct($rows)
    {
        $this->make($rows, 'App\Services\CsvImporter\Entities\Activity\Components\ActivityRow');
    }

    public function process()
    {
        foreach ($this->rows() as $row) {
            $row->process()->validate()->keep();
        }

        return $this;
    }
}
