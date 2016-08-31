<?php namespace App\Services\CsvImporter\Entities;


abstract class Row
{
    protected $fields;

    public function fields()
    {
        return $this->fields;
    }

    public function field($fieldName)
    {
        if (array_key_exists($fieldName, $this->fields)) return $this->fields[$fieldName];
    }
}
