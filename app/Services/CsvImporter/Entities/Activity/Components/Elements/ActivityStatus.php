<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class ActivityStatus
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class ActivityStatus extends Element
{
    /**
     * CSV Header of Description with their code.
     */
    private $_csvHeader = ['activity_status'];

    /**
     * Description constructor.
     * @param            $fields
     * @param Validation $factory
     */
    public function __construct($fields, Validation $factory)
    {
        $this->prepare($fields);
        $this->factory = $factory;
    }

    /**
     * Prepare the ActivityStatus element.
     * @param $fields
     */
    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, array_flip($this->_csvHeader))) {
                foreach ($values as $value) {
                    $this->map($value);
                }
            }
        }
    }

    /**
     * Map data from CSV into ActivityStatus data format.
     * @param $value
     */
    public function map($value)
    {
        if (!(is_null($value) || $value == "")) {
            $this->data[$this->csvHeader()][] = $value;
        }
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        return [$this->csvHeader() => 'required|size:1'];
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        $key = $this->csvHeader();

        return [
            sprintf('%s.required', $key) => 'Activity Status is required.',
            sprintf('%s.size', $key) => 'Multiple Activity Statuses are not allowed.'
        ];
    }

    /**
     * Validate data for IATI Element.
     */
    public function validate()
    {
        $this->validator = $this->factory->sign($this->data())
                                         ->with($this->rules(), $this->messages())
                                         ->getValidatorInstance();

        $this->setValidity();
    }

    protected function csvHeader()
    {
        return end($this->_csvHeader);
    }
}
