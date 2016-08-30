<?php namespace App\Services\CsvImporter\Entities;


abstract class Row
{
    protected $fields;

    abstract public function init();

    abstract public function process();

    abstract public function validate();

    abstract public function keep();

    public function fields()
    {
        return $this->fields;
    }

    public function field($fieldName)
    {
        if (array_key_exists($fieldName, $this->fields)) return $this->fields[$fieldName];
    }
}
