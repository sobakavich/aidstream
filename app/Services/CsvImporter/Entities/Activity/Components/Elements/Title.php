<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\IatiElement;

/**
 * Class Title
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class Title extends IatiElement
{
    /**
     * Csv Header for Title element.
     * @var array
     */
    private $_csvHeader = ['activity_title'];

    /**
     * @var
     */
    protected $narratives;

    /**
     * @var
     */
    protected $languages;

    /**
     * Template for Title element.
     * @var array
     */
    protected $template = [['narrative' => '', 'language' => '']];

    /**
     * Title constructor.
     * @param $fields
     */
    public function __construct($fields)
    {
        $this->prepare($fields);
    }

    /**
     * Prepare Title element.
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
     * Map data from CSV file into Title data format.
     * @param $value
     */
    public function map($value)
    {
        if (!(is_null($value) || $value == "")) {
            $this->data[] = $this->setNarrative($value);
        }
    }

    /**
     * Set the Narrative for the Title element.
     * @param $value
     * @return array
     */
    public function setNarrative($value)
    {
        $narrative          = ['narrative' => $value, 'language' => ''];
        $this->narratives[] = $narrative;

        return $narrative;
    }


    /**
     * Get the languages for the Title element.
     * @return mixed
     */
    public function language()
    {
        return $this->languages;
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
