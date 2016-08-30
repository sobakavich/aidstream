<?php namespace App\Services\CsvImporter\Entities;


/**
 * Class Row
 * @package App\Services\CsvImporter\Entities
 */
abstract class Row
{
    /**
     * Elements for a Row.
     * @var array
     */
    protected $elements;

    /**
     * Fields in the Row.
     * @var
     */
    protected $fields;

    /**
     * Initialize the Row object.
     * @return mixed
     */
    abstract public function init();

    /**
     * Process the Row.
     * @return mixed
     */
    abstract public function process();

    /**
     * Validate the Row.
     * @return mixed
     */
    abstract public function validate();

    /**
     * Store the Row in a temporary JSON File for further usage.
     * @return mixed
     */
    abstract public function keep();

    /**
     * Initialize the objects for the all the elements in the Row.
     * @param $element
     * @param $fields
     * @param $namespace
     * @return mixed
     */
    protected function make($element, $fields, $namespace)
    {
        if (class_exists($class = sprintf('%s\%s', $namespace, ucfirst($element)))) {
            return app()->make($class, [$fields]);
        }
    }

    /**
     * Get the Fields of the Row.
     * @return mixed
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Get the value of a Field in a Row with specific fieldName.
     * @param $fieldName
     * @return mixed
     */
    public function field($fieldName)
    {
        if (array_key_exists($fieldName, $this->fields)) return $this->fields[$fieldName];
    }

    /**
     * Get all elements of a Row.
     * @return array
     */
    protected function elements()
    {
        return $this->elements;
    }
}
