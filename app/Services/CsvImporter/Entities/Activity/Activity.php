<?php namespace App\Services\CsvImporter\Entities\Activity;

use App\Services\CsvImporter\Entities\Activity\Components\ActivityRow;
use App\Services\CsvImporter\Entities\Csv;
use Exception;

/**
 * Class Activity
 * @package App\Services\CsvImporter\Entities\Activity
 */
class Activity extends Csv
{
    /**
     * Activity constructor.
     * @param $rows
     */
    public function __construct($rows)
    {
        try {
            $this->make($rows, ActivityRow::class);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**
     * Process the Activity Csv.
     * @return $this
     */
    public function process()
    {
        foreach ($this->rows() as $row) {
            $row->process()->validate()->keep();
        }

        return $this;
    }
}
