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

        }

        return $this;
    }

    public function validate()
    {
        // TODO: Validate each row.

        return $this;
    }

    public function keep()
    {
        // TODO: Implement keep() method.
        // TODO: Write validated activity into JSON.
    }
}
