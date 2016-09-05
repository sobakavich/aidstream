<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\IatiElement;

/**
 * Class Sector
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class Sector extends IatiElement
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
     * @param $fields
     */
    public function __construct($fields)
    {
        $this->prepare($fields);
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
            $value                                   = (!$value) ? '' : $value;
            $this->vocabularies[]                    = $value;
            $this->data[$index]['sector_vocabulary'] = $value;
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
            $sectorVocabulary         = $this->data[$index]['sector_vocabulary'];
            $sectorVocabularyResponse = $this->isValidSectorVocabulary($sectorVocabulary);

            if ($sectorVocabularyResponse == 1) {
                ($value) ?: $value = '';
                $this->codes[]                     = $value;
                $this->data[$index]['sector_code'] = $value;
            } else {
                $this->data[$index]['sector_code'] = '';
            }
        }
    }

    /**
     * Set vocabulary uri for Sector.
     * @param $index
     */
    protected function setVocabularyUri($index)
    {
        $this->data[$index]['vocabulary_uri'] = '';
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
            $sectorVocabulary         = $this->data[$index]['sector_vocabulary'];
            $sectorVocabularyResponse = $this->isValidSectorVocabulary($sectorVocabulary);

            if ($sectorVocabularyResponse == 2) {
                ($value) ?: $value = '';
                $this->codes[]                              = $value;
                $this->data[$index]['sector_category_code'] = $value;
            } else {
                $this->data[$index]['sector_category_code'] = '';
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
            $sectorVocabulary         = $this->data[$index]['sector_vocabulary'];
            $sectorVocabularyResponse = $this->isValidSectorVocabulary($sectorVocabulary);

            if (!$sectorVocabularyResponse || ($sectorVocabularyResponse != 1 && $sectorVocabularyResponse != 2)) {
                ($value) ?: $value = '';
                $this->codes[]                     = $value;
                $this->data[$index]['sector_text'] = $value;
            } else {
                $this->data[$index]['sector_text'] = '';
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
            $this->percentage[]               = $value;
            $this->data[$index]['percentage'] = $value;
        }
    }

    /**
     * Set narrative for Sector.
     * @param $index
     */
    protected function setNarrative($index)
    {
        if (array_key_exists('percentage', $this->data[$index])) {
            $narrative                       = ['narrative' => '', 'language' => ''];
            $this->data[$index]['narrative'] = $narrative;
            $this->isEmptySector($index);
        }
    }

    /**
     * Check if the sector array is empty.
     * @param $index
     */
    protected function isEmptySector($index)
    {
        if ($this->data[$index]['sector_vocabulary'] == ""
            && $this->data[$index]['sector_code'] == ""
            && $this->data[$index]['sector_category_code'] == ""
            && $this->data[$index]['sector_text'] == ""
            && $this->data[$index]['percentage'] == ""
        ) {
            unset($this->data[$index]);
        }
    }

    /**
     * Check if the Sector Vocabulary from CSV file is valid.
     * @param $value
     * @return bool
     */
    protected function isValidSectorVocabulary($value)
    {
        $sectorVocabularyCodelist = $this->codeList('SectorVocabulary.json');

        foreach ($sectorVocabularyCodelist['SectorVocabulary'] as $vocabulary) {
            if ($value == $vocabulary['code']) {
                return $value;
            }
        }

        return false;
    }

    /**
     * Load codelist of Sector.
     * @param $filename
     * @return mixed
     */
    protected function codeList($filename)
    {
        $sectorVocabularyCodelistFile = file_get_contents(sprintf('%s%s/%s', app_path(), self::CODE_LIST_PATH, $filename));

        return json_decode($sectorVocabularyCodelistFile, true);
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
