<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class Sector
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class Sector extends Element
{
    /**
     * CSV Header of Description with their code
     */
    private $_csvHeaders = ['sector_vocabulary', 'sector_code', 'sector_percentage'];

    /**
     * @var array
     */
    protected $vocabularies = [];

    /**
     * @var array
     */
    protected $codes = [];

    /**
     * @var array
     */
    protected $percentage = [];

    /**
     * File path for the english IATI code list for an Activity.
     */
    const CODE_LIST_PATH = '/Core/V201/Codelist/en/Activity';

    /**
     * @var array
     */
    protected $template = [
        [
            'sector_vocabulary'    => '',
            'vocabulary_uri'       => '',
            'sector_code'          => '',
            'sector_category_code' => '',
            'sector_text'          => '',
            'percentage'           => '',
            'narrative'            => ['narrative' => '', 'language' => '']
        ]
    ];

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
     * Prepare data for Sector Element.
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
     * Map data from CSV File to Sector data format.
     * @param $key
     * @param $value
     * @param $index
     */
    public function map($key, $value, $index)
    {
        $this->setSectorVocabulary($key, $value, $index);
        $this->setVocabularyUri($index);
        $this->setSectorCode($key, $value, $index);
        $this->setSectorCategoryCode($key, $value, $index);
        $this->setSectorText($key, $value, $index);
        $this->setSectorPercentage($key, $value, $index);
        $this->setNarrative($index);
    }

    /**
     * Set sector vocabulary for Sector.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setSectorVocabulary($key, $value, $index)
    {
        if ($key == $this->_csvHeaders[0]) {
            $value                                             = (!$value) ? '' : $value;
            $this->vocabularies[]                              = $value;
            $this->data['sector'][$index]['sector_vocabulary'] = $value;
        }
    }


    /**
     * Set sector code for Sector.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setSectorCode($key, $value, $index)
    {
        if ($key == $this->_csvHeaders[1]) {
            $sectorVocabulary         = $this->data['sector'][$index]['sector_vocabulary'];
            $sectorVocabularyResponse = $this->isValidSectorVocabulary($sectorVocabulary);

            if ($sectorVocabularyResponse == 1) {
                ($value) ?: $value = '';
                $this->codes[]                               = $value;
                $this->data['sector'][$index]['sector_code'] = $value;
            } else {
                $this->data['sector'][$index]['sector_code'] = '';
            }
        }
    }

    /**
     * Set vocabulary uri for Sector.
     * @param $index
     */
    protected function setVocabularyUri($index)
    {
        $this->data['sector'][$index]['vocabulary_uri'] = '';
    }

    /**
     * Set sector category code for Sector.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setSectorCategoryCode($key, $value, $index)
    {
        if ($key == $this->_csvHeaders[1]) {
            $sectorVocabulary         = $this->data['sector'][$index]['sector_vocabulary'];
            $sectorVocabularyResponse = $this->isValidSectorVocabulary($sectorVocabulary);

            if ($sectorVocabularyResponse == 2) {
                ($value) ?: $value = '';
                $this->codes[]                                        = $value;
                $this->data['sector'][$index]['sector_category_code'] = $value;
            } else {
                $this->data['sector'][$index]['sector_category_code'] = '';
            }
        }
    }

    /**
     * Set sector text for Sector.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setSectorText($key, $value, $index)
    {
        if ($key == $this->_csvHeaders[1]) {
            $sectorVocabulary         = $this->data['sector'][$index]['sector_vocabulary'];
            $sectorVocabularyResponse = $this->isValidSectorVocabulary($sectorVocabulary);

            if (!$sectorVocabularyResponse || ($sectorVocabularyResponse != 1 && $sectorVocabularyResponse != 2)) {
                ($value) ?: $value = '';
                $this->codes[]                               = $value;
                $this->data['sector'][$index]['sector_text'] = $value;
            } else {
                $this->data['sector'][$index]['sector_text'] = '';
            }
        }
    }

    /**
     * Set sector percentage for Sector.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setSectorPercentage($key, $value, $index)
    {
        if ($key == $this->_csvHeaders[2]) {
            ($value) ?: $value = '';
            $this->percentage[]                         = $value;
            $this->data['sector'][$index]['percentage'] = $value;
        }
    }

    /**
     * Set narrative for Sector.
     * @param $index
     */
    protected function setNarrative($index)
    {
        if (array_key_exists('percentage', $this->data['sector'][$index])) {
            $narrative                                   = ['narrative' => '', 'language' => ''];
            $this->data['sector'][$index]['narrative'][] = $narrative;
            $this->isEmptySector($index);
        }
    }

    /**
     * Check if the sector array is empty.
     * @param $index
     */
    protected function isEmptySector($index)
    {
        if ($this->data['sector'][$index]['sector_vocabulary'] == ""
            && $this->data['sector'][$index]['sector_code'] == ""
            && $this->data['sector'][$index]['sector_category_code'] == ""
            && $this->data['sector'][$index]['sector_text'] == ""
            && $this->data['sector'][$index]['percentage'] == ""
        ) {
            unset($this->data['sector'][$index]);
        }
    }

    /**
     * Check if the Sector Vocabulary from CSV file is valid.
     * @param $value
     * @return bool
     */
    protected function isValidSectorVocabulary($value)
    {
        $sectorVocabularyCodelist = $this->validSectorCodelist('SectorVocabulary','V201');

        foreach ($sectorVocabularyCodelist as $key => $code) {
            if ($value == $code) {
                return $value;
            }
        }

        return false;
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

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {

        $sectorVocabulary   = implode(",", $this->validSectorCodelist('SectorVocabulary', 'V201'));
        $sectorCode         = implode(",", $this->validSectorCodelist('Sector', 'V201'));
        $sectorCategoryCode = implode(",", $this->validSectorCodelist('SectorCategory', 'V201'));

        $rules = [
            'sector'                     => 'required|sector_percentage_sum',
            'sector.*.sector_vocabulary' => sprintf('required|in:%s', $sectorVocabulary),
            'sector.*.percentage'        => 'required'
        ];

        foreach (getVal($this->data(), ['sector']) as $key => $value) {
            $rules['sector.' . $key . '.sector_code']          = sprintf('required_if:sector.%s.sector_vocabulary,1|in:%s', $key, $sectorCode);
            $rules['sector.' . $key . '.sector_category_code'] = sprintf('required_if:sector.%s.sector_vocabulary,2|in:%s', $key, $sectorCategoryCode);
            $rules['sector.' . $key . '.sector_text']          = sprintf(
                'required_unless:sector.%s.sector_vocabulary,1,required_unless:sector.%s.sector_vocabulary,2',
                $key,
                $key
            );
            $rules['sector.' . $key . '.percentage']           = 'numeric';
        }

        foreach (getVal($this->data(), ['sector']) as $key => $value) {

        }

        return $rules;
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        $messages = [
            'sector.required'                     => 'At least one sector is required.',
            'sector.sector_percentage_sum'        => 'Sum of percentage under same vocabulary must be 100',
            'sector.*.sector_vocabulary.required' => 'Sector Vocabulary is required.',
            'sector.*.sector_vocabulary.in'       => 'Entered sector vocabulary is invalid.',
            'sector.*.percentage'                 => 'Percentage is required'
        ];

        foreach (getVal($this->data(), ['sector']) as $key => $value) {
            $messages['sector.' . $key . '.sector_code.required_if']          = "Sector code is required when sector vocabulary is 1";
            $messages['sector.' . $key . '.sector_code.in']                   = "Entered sector code for sector vocabulary 1 is invalid";
            $messages['sector.' . $key . '.sector_category_code.required_if'] = "Sector code is required when sector category code is 2";
            $messages['sector.' . $key . '.sector_category_code.in']          = "Entered sector code for sector vocabulary 2 is invalid";
            $messages['sector.' . $key . '.sector_text.required_unless']      = "Sector code is required.";
        }

        return $messages;
    }

    /**
     * Return Valid Sector Vocabulary Code.
     * @param $name
     * @param $version
     * @return array
     */
    protected function validSectorCodelist($name, $version)
    {
        $sectorVocabulary = $this->loadCodeList($name, $version);
        $vocabularies     = [];

        array_walk(
            $sectorVocabulary[$name],
            function ($vocabulary) use (&$vocabularies) {
                $vocabularies[] = $vocabulary['code'];
            }
        );

        return $vocabularies;
    }
}
