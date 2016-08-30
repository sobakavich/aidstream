<?php namespace App\Services\CsvImporter;

use Exception;

/**
 * Class CsvProcessor
 * @package App\Services\CsvImporter
 */
class CsvProcessor
{
    /**
     * @var
     */
    protected $csv;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var
     */
    public $model;

    /**
     * @var string
     */
    protected $csvIdentifier = 'activity_identifier';

    /**
     * CsvProcessor constructor.
     * @param $csv
     */
    public function __construct($csv)
    {
        $this->csv = $csv;
    }

    /**
     * Handle the import functionality.
     */
    public function handle()
    {
        $this->groupValues($this->csv);

        try {
            $this->make('App\Services\CsvImporter\Entities\Activity\Activity');

            $this->model->process();
            dd($this);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**
     * Make objects for the provided class.
     * @param $class
     */
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
     * Group rows into single Activities.
     * @param $csv
     */
    protected function groupValues($csv)
    {
        $index = - 1;

        foreach ($csv as $row) {
            if (!$this->isSameEntity($row)) {
                $index ++;
            }

            $this->group($row, $index);
        }
    }

    /**
     * Group the values of a row to a specific index.
     * @param $row
     * @param $index
     */
    protected function group($row, $index)
    {
        foreach ($row as $key => $value) {
            $this->setValue($index, $key, $value);
        }
    }

    /**
     * Set the provided value to the provided key/index.
     * @param $index
     * @param $key
     * @param $value
     */
    protected function setValue($index, $key, $value)
    {
        $this->data[$index][$key][] = $value;
    }

    /**
     * Check if the next row is new row or not.
     * @param $row
     * @return bool
     */
    protected function isSameEntity($row)
    {
        if (is_null($row[$this->csvIdentifier]) || $row[$this->csvIdentifier] == '') {
            return true;
        }

        return false;
    }
}
