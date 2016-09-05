<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation;


/**
 * Class IatiElement
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation
 */
abstract class IatiElement
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $template = [];

    /**
     * @var
     */
    protected $validator;

    /**
     * @var
     */
    protected $isValid;

    /**
     * @var
     */
    protected $factory;

    /**
     * Prepare the IATI Element.
     * @param $fields
     */
    abstract protected function prepare($fields);

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    abstract public function rules();

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    abstract public function messages();

    /**
     * Validate data for IATI Element.
     */
    abstract public function validate();

    /**
     * Set the validity for the IATI Element data.
     */
    abstract protected function setValidity();

    /**
     * @return array
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Get the template for the IATI Element.
     * @return array
     */
    public function template()
    {
        return $this->template;
    }
}
