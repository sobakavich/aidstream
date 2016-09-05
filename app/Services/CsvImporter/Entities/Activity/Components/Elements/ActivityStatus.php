<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\IatiElement;

/**
 * Class ActivityStatus
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class ActivityStatus extends IatiElement
{
    /**
     * CSV Header of Description with their code.
     */
    private $_csvHeader = ['activity_status'];

    /**
     * Description constructor.
     * @param $fields
     */
    public function __construct($fields)
    {
        $this->prepare($fields);
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
            $this->data[] = $value;
        }
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        // TODO: Implement rules() method.
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        // TODO: Implement messages() method.
    }

    /**
     * Validate data for IATI Element.
     */
    public function validate()
    {
        // TODO: Implement validate() method.
    }

    /**
     * Set the validity for the IATI Element data.
     */
    protected function setValidity()
    {
        // TODO: Implement setValidity() method.
    }
}
