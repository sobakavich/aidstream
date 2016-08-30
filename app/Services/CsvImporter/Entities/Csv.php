<?php namespace App\Services\CsvImporter\Entities;

use Exception;

abstract class Csv
{
    protected $rows;

    protected function make($rows, $class)
    {
        array_walk(
            $rows,
            function ($row) use ($class) {
                if (class_exists($class)) {
                    try {
                        $this->rows[] = app()->make($class, [$row]);
                    } catch (Exception $exception) {
                        dd($exception);
                    }

                }
            }
        );
    }

    public function rows()
    {
        return $this->rows;
    }
}
