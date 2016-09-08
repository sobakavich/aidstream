<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class ActivityDate
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class ActivityDate extends Element
{
    /**
     * Csv Headers for the ActivityDate element.
     * @var array
     */
    private $_csvHeaders = ['actual_start_date' => 2, 'actual_end_date' => 4, 'planned_start_date' => 1, 'planned_end_date' => 3];

    /**
     * Template for the ActivityDate element.
     * @var array
     */
    protected $template = ['type' => '', 'date' => '', 'narrative' => ['narrative' => '', 'language' => '']];

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @var
     */
    protected $narratives;

    /**
     * @var
     */
    protected $dates;

    /**
     * ActivityDate constructor.
     * @param            $fields
     * @param Validation $factory
     */
    public function __construct($fields, Validation $factory)
    {
        $this->prepare($fields);
        $this->factory = $factory;
    }

    /**
     * Prepare ActivityDate element.
     * @param $fields
     */
    public function prepare($fields)
    {
        $index = 0;

        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, $this->_csvHeaders)) {
                foreach ($values as $value) {
                    $this->map($key, $value, $index);
                    $index ++;
                }
            }
        }
    }

    /**
     * Map data from CSV file into the ActivityDate data format.
     * @param $key
     * @param $value
     * @param $index
     */
    public function map($key, $value, &$index)
    {
        if (!(is_null($value) || $value == "")) {
            $type                                              = $this->setType($key);
            $this->data['activity_date'][$index]['date']       = $this->setDate($value);
            $this->data['activity_date'][$index]['type']       = $type;
            $this->data['activity_date'][$index]['narratives'] = $this->setNarrative($value);
        }
    }

    /**
     * Set the type for ActivityDate element.
     * @param $key
     * @return mixed
     */
    public function setType($key)
    {
        $this->types[] = $key;
        $this->types   = array_unique($this->types);

        return $this->_csvHeaders[$key];
    }

    /**
     * Set the Date for the ActivityDate element.
     * @param $value
     * @return mixed
     */
    public function setDate($value)
    {
        $this->dates[] = $value;

        return $value;
    }

    /**
     * Set the Narrative for the ActivityDate element.
     * @param $value
     * @return array
     */
    public function setNarrative($value)
    {
        $narrative          = ['narrative' => '', 'language' => ''];
        $this->narratives[] = $narrative;

        return $narrative;
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        return [
            'activity_date' => 'required'
        ];
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        return ['activity_date.required' => 'Activity Date is required.'];
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
}
