<?php namespace App\Services\CsvImporter\Entities\Activity;

use App\Services\CsvImporter\Entities\Csv;
use App\Services\CsvImporter\Entities\Activity\Components\ActivityRow;
use Exception;

class Activity extends Csv
{
    public function __construct($rows)
    {
        try {
            $this->make($rows, 'App\Services\CsvImporter\Entities\Activity\Components\ActivityRow');
            dd($this);
        } catch (Exception $exception) {
            dd($exception);
        }

    }

    public function process()
    {
        foreach ($this->rows() as $row) {
            $row->process()->validate()->keep();
        }

        return $this;
    }
}
