<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati;


/**
 * Class Element
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati
 */
abstract class Element
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
    protected function setValidity()
    {
        $this->isValid = $this->validator->passes();
    }

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
