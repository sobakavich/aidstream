<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;

/**
 * Class RecipientRegion
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class RecipientRegion extends Element
{
    /**
     * CSV Header of Description with their code
     */
    private $_csvHeaders = ['recipient_region_code', 'recipient_region_percentage'];

    /**
     * @var array
     */
    protected $regions = [];

    /**
     * @var array
     */
    protected $percentage = [];

    /**
     * @var array
     */
    protected $template = [['region_code' => '', 'region_vocabulary' => '', 'vocabulary_uri' => '', 'percentage' => '', 'narrative' => ['narrative' => '', 'language' => '']]];

    /**
     * Description constructor.
     * @param $fields
     */
    public function __construct($fields)
    {
        $this->prepare($fields);
    }

    /**
     * Prepare RecipientRegion Element.
     * @param $fields
     */
    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, array_flip($this->_csvHeaders))) {
                foreach ($values as $index => $value) {
                    $this->map($key, $value, $index);
                }
            }
        }
    }

    /**
     * Map data from CSV into RecipientRegion data format.
     * @param $key
     * @param $value
     * @param $index
     */
    public function map($key, $value, $index)
    {
        if (!(is_null($value) || $value == "")) {
            $this->setRegion($key, $value, $index);
            $this->setRegionVocabulary($index);
            $this->setVocabularyUri($index);
            $this->setPercentage($key, $value, $index);
            $this->setNarrative($index);
        }
    }

    /**
     * Set Region of RecipientRegion.
     * @param $key
     * @param $value
     * @param $index
     * @return mixed
     */
    protected function setRegion($key, $value, $index)
    {
        if (!isset($this->data[$index]['region_code'])) {
            $this->data[$index]['region_code'] = '';
        }

        if ($key == $this->_csvHeaders[0] && (!is_null($value))) {
            $this->regions[] = $value;
            $this->regions   = array_unique($this->regions);

            $this->data[$index]['region_code'] = $value;
        }
    }

    /**
     * Set Percentage of RecipientRegion.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setPercentage($key, $value, $index)
    {
        if (!isset($this->data[$index]['percentage'])) {
            $this->data[$index]['percentage'] = '';
        }

        if ($key == $this->_csvHeaders[1] && (!is_null($value))) {
            $this->percentage[] = $value;

            $this->data[$index]['percentage'] = $value;
        }
    }

    /**
     * Set Narrative of RecipientRegion.
     * @param $index
     * @return array
     */
    protected function setNarrative($index)
    {
        $narrative = ['narrative' => '', 'language' => ''];

        $this->data[$index]['narrative'] = $narrative;
    }

    /**
     * Set VocabularyUri of RecipientRegion.
     * @param $index
     */
    protected function setVocabularyUri($index)
    {
        $this->data[$index]['vocabulary_uri'] = '';
    }

    /**
     *Set Region Vocabulary of RecipientRegion.
     * @param $index
     */
    protected function setRegionVocabulary($index)
    {
        $this->data[$index]['region_vocabulary'] = '';
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
