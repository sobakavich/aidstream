<?php namespace App\Services\CsvImporter;

use App\Services\CsvImporter\Entities\Activity\Activity;
use App\Services\CsvImporter\Entities\Activity\Components\ActivityRow;
use Exception;

class CsvProcessor
{
    protected $csv;

    protected $data = [];

    public $model;

    protected $csvIdentifier = 'activity_identifier';

    public function __construct($csv)
    {
        $this->csv = $csv;
    }

    public function handle()
    {
        $this->groupValues($this->csv);

        try {
            $this->make('App\Services\CsvImporter\Entities\Activity\Activity');

            $this->model->process();
        } catch (Exception $exception) {
            dd($exception);
        }

//        dd($this);
    }

    protected function make($class)
    {
        try {
            if (class_exists($class)) {
                $this->model = app()->make($class, [$this->data]);
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**
     * @param $csv
     */
    protected function groupValues($csv)
    {
        $index = - 1;
        foreach ($csv as $row) {
            $sameElement = $this->isSameElement($row);
            if (!$sameElement) {
                $index ++;
                foreach ($row as $key => $value) {
                    $this->setValue($index, $key, $value);
                }
            } else {
                foreach ($row as $key => $value) {
                    $this->setValue($index, $key, $value);
                }
            }
        }
    }

    protected function setValue($index, $key, $value)
    {
        if (!isset($this->data[$index][$key])) {
            $this->data[$index][$key] = null;
        }

        if (!(is_null($value) || $value == "")) {
            $this->data[$index][$key][] = $value;
        }
    }

    /**
     * @param $row
     * @return bool
     */
    protected function isSameElement($row)
    {
        if (is_null($row[$this->csvIdentifier]) || $row[$this->csvIdentifier] == '') {
            return true;
        }

        return false;
    }
}
