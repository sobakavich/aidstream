<?php namespace App\Services\CsvImporter\Entities;

use Exception;

/**
 * Class Csv
 * @package App\Services\CsvImporter\Entities
 */
abstract class Csv
{
    /**
     * @var
     */
    protected $rows;

    protected $organizationId;

    /**
     * Initialize objects for the CSV class with the respective Row objects.
     * @param $rows
     * @param $class
     */
    protected function make($rows, $class)
    {
        array_walk(
            $rows,
            function ($row) use ($class) {
                if (class_exists($class)) {
                    try {
                        $this->rows[] = app()->make($class, [$row, $this->organizationId]);
                    } catch (Exception $exception) {
                        dd($exception->getMessage());
                    }

                }
            }
        );
    }

    /**
     * Get the rows in the CSV.
     * @return mixed
     */
    public function rows()
    {
        return $this->rows;
    }
}
