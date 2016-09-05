<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\IatiElement;

/**
 * Class ActivityDate
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class ActivityDate extends IatiElement
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
     * @param $fields
     */
    public function __construct($fields)
    {
        $this->prepare($fields);
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
            $type                             = $this->setType($key);
            $this->data[$index]['date']       = $this->setDate($value);
            $this->data[$index]['type']       = $type;
            $this->data[$index]['narratives'] = $this->setNarrative($value);
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