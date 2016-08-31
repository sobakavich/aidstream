<?php namespace App\Services\CsvImporter\Entities;


abstract class Csv
{
    protected $rows;

    protected function make($rows, $class)
    {
        array_walk($rows, function ($row) use ($class) {
            if (class_exists($class)) {
                $this->rows[] = app()->make($class, [$row]);
            }
        });
    }

    public function rows()
    {
        return $this->rows;
    }
}
