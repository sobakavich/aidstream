<?php namespace App\Services\CsvImporter\Entities\Activity\Components;

use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;
use App\Services\CsvImporter\Entities\Row;

/**
 * Class ResultRow
 * @package App\Services\CsvImporter\Entities\Activity\Components
 */
class ResultRow extends Row
{

    /**
     * Directory where the validated Csv data is written before import.
     */
    const CSV_DATA_STORAGE_PATH = 'csvImporter/tmp/result';

    /**
     * File in which the valida Csv data is written before import.
     */
    const VALID_CSV_FILE = 'valid.json';

    /**
     * File in which the invalid Csv data is written before import.
     */
    const INVALID_CSV_FILE = 'invalid.json';

    const RESULT_TEMPLATE_FILE = '/Services/CsvImporter/Entities/Activity/Components/Elements/Foundation/Template/Result.json';
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var
     */
    protected $fields;

    /**
     * @var array
     */
    protected $indicators = [];

    /**
     * @var array
     */
    protected $resultFields = [
        'type',
        'aggregation_status',
        'title',
        'title_language',
        'description',
        'description_language',
        'indicator' => [
            'measure',
            'ascending',
            'indicator_title',
            'indicator_title_language',
            'indicator_description',
            'indicator_description_language',
            'reference_vocabulary',
            'reference_code',
            'reference_uri',
            'baseline_year',
            'baseline_value',
            'baseline_comment',
            'baseline_comment_language',
            'period_start',
            'period_end',
            'target_value',
            'target_location_ref',
            'target_dimension_name',
            'target_dimension_value',
            'target_comment',
            'target_comment_language',
            'actual_value',
            'actual_location_ref',
            'actual_dimension_name',
            'actual_dimension_value',
            'actual_comment',
            'actual_comment_language'
        ]
    ];

    /**
     * @var array
     */
    protected $resultTemplate = [
        'type',
        'aggregation_status',
        'title',
        'title_language',
        'description',
        'description_language',
        'indicator',
        'measure',
        'ascending',
        'indicator_title',
        'indicator_title_language',
        'indicator_description',
        'indicator_description_language',
        'reference_vocabulary',
        'reference_code',
        'reference_uri',
        'baseline_year',
        'baseline_value',
        'baseline_comment',
        'baseline_comment_language',
        'period_start',
        'period_end',
        'target_value',
        'target_location_ref',
        'target_dimension_name',
        'target_dimension_value',
        'target_comment',
        'target_comment_language',
        'actual_value',
        'actual_location_ref',
        'actual_dimension_name',
        'actual_dimension_value',
        'actual_comment',
        'actual_comment_language'
    ];

    /**
     * @var array
     */
    protected $periodFields = [
        'period_start',
        'period_end',
        'target_value',
        'target_location_ref',
        'target_dimension_name',
        'target_dimension_value',
        'target_comment',
        'target_comment_language',
        'actual_value',
        'actual_location_ref',
        'actual_dimension_name',
        'actual_dimension_value',
        'actual_comment',
        'actual_comment_language'
    ];

    /**
     * @var
     */
    protected $validator;

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @var Validation
     */
    protected $factory;

    /**
     * @var array
     */
    protected $validElements = [];

    /**
     * @var
     */
    protected $organizationId;

    /**
     * @var
     */
    protected $userId;

    /**
     * ResultRow constructor.
     * @param            $fields
     * @param            $organizationId
     * @param            $userId
     * @param Validation $factory
     */
    public function __construct($fields, $organizationId, $userId, Validation $factory)
    {
        $this->fields         = $fields;
        $this->organizationId = $organizationId;
        $this->userId         = $userId;
        $this->factory        = $factory;
    }

    /**
     * Group rows into single Result.
     */
    protected function groupValues()
    {
        $index = - 1;
        foreach ($this->fields['measure'] as $i => $row) {

            if (!$this->isSameEntity($i)) {
                $index ++;
            }
            $this->setValue($index, $i);
        }
    }

    /**
     * Set the provided value to the provided key/index.
     * @param $index
     * @param $i
     */
    protected function setValue($index, $i)
    {
        foreach ($this->fields as $row => $value) {
            if (array_key_exists($row, array_flip($this->resultFields['indicator']))) {
                $this->indicators[$index][$row][] = $value[$i];
            }
        }
    }

    /**
     * Check if the next row is new row or not.
     * @param $i
     * @return bool
     */
    protected function isSameEntity($i)
    {
        if ((is_null($this->fields[$this->resultFields['indicator'][0]][$i]) || $this->fields[$this->resultFields['indicator'][0]][$i] == '')
            && (is_null($this->fields[$this->resultFields['indicator'][1]][$i]) || $this->fields[$this->resultFields['indicator'][1]][$i] == '')
        ) {
            return true;
        }

        return false;
    }

    protected function loadTemplate()
    {
        $path       = app_path(self::RESULT_TEMPLATE_FILE);
        $this->data = json_decode(file_get_contents($path), true);
    }

    /**
     * @return $this
     * @internal param $fields
     */
    public function mapResultRow()
    {
        $this->loadTemplate();
        $this->beginMapping();

        return $this;
    }

    /**
     *
     */
    protected function beginMapping()
    {
        $this->setType()
             ->setAggregationStatus()
             ->setTitle()
             ->setDescription()
             ->setIndicator();
    }

    /**
     * @return $this
     */
    protected function setType()
    {
        $value = getVal($this->fields, ['type']);
        if (!is_null($value)) {
            $this->data['type'] = $value[0];
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function setAggregationStatus()
    {
        $values = getVal($this->fields, ['aggregation_status'], []);
        if (!is_null($values[0])) {
            $value                            = $this->isBoolean($values[0]);
            $this->data['aggregation_status'] = $value;
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function setTitle()
    {
        $this->setNarrative(['title'], 'title', 'title_language');

        return $this;
    }

    /**
     * @return $this
     */
    protected function setDescription()
    {
        $this->setNarrative(['description'], 'description', 'description_language');

        return $this;
    }

    /**
     * @return $this
     */
    protected function setIndicator()
    {
        $this->groupIndicator();

        foreach ($this->indicators as $index => $values) {
            $this->setIndicatorMeasure($index)
                 ->setIndicatorAscending($index)
                 ->setIndicatorTitle($index)
                 ->setIndicatorDescription($index)
                 ->setReferenceVocabulary($index)
                 ->setReferenceCode($index)
                 ->setReferenceURI($index)
                 ->setIndicatorBaseline($index)
                 ->setIndicatorPeriod($index);
        }

        return $this;
    }


    /**
     * Grouping of Indicator
     */
    protected function groupIndicator()
    {
        $this->groupValues();
    }

    /**
     * @param $index
     * @return $this
     */
    protected function setIndicatorMeasure($index)
    {
        $measure = getVal($this->indicators[$index], ['measure'], []);
        foreach ($measure as $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['measure'] = $value;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @return $this
     */
    protected function setIndicatorAscending($index)
    {
        $ascending = getVal($this->indicators[$index], ['ascending'], []);
        foreach ($ascending as $values) {
            if (!is_null($values)) {
                $value                                        = $this->isBoolean($values);
                $this->data['indicator'][$index]['ascending'] = $value;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @return $this
     */
    protected function setIndicatorTitle($index)
    {
        $this->setNarrative(['indicator', $index, 'title'], 'indicator_title', 'indicator_title_language', $this->indicators[$index]);

        return $this;
    }

    /**
     * @param $index
     * @return $this
     */
    protected function setIndicatorDescription($index)
    {
        $this->setNarrative(['indicator', $index, 'description'], 'indicator_description', 'indicator_description_language', $this->indicators[$index]);

        return $this;
    }

    /**
     * @param $index
     * @return $this
     */
    protected function setReferenceVocabulary($index)
    {
        $array = getVal($this->indicators[$index], ['reference_vocabulary'], []);
        foreach ($array as $i => $values) {
            if (!is_null($values)) {
                $this->data['indicator'][$index]['reference'][$i]['vocabulary'] = $values;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @return $this
     */
    protected function setReferenceCode($index)
    {
        $array = getVal($this->indicators[$index], ['reference_code'], []);
        foreach ($array as $i => $values) {
            if (!is_null($values)) {
                $this->data['indicator'][$index]['reference'][$i]['code'] = $values;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @return $this
     */
    protected function setReferenceURI($index)
    {
        $array = getVal($this->indicators[$index], ['reference_uri'], []);
        foreach ($array as $i => $values) {
            if (!is_null($values)) {
                $this->data['indicator'][$index]['reference'][$i]['indicator_uri'] = $values;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @return $this
     */
    protected function setIndicatorBaseline($index)
    {
        $this->setIndicatorBaselineYear($index)
             ->setIndicatorBaselineValue($index)
             ->setIndicatorBaselineComment($index);

        return $this;
    }

    /**
     * @param $index
     * @return $this
     */
    protected function setIndicatorBaselineYear($index)
    {
        $values = getVal($this->indicators[$index], ['baseline_year']);
        foreach ($values as $i => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['baseline'][0]['year'] = $value;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @return $this
     */
    protected function setIndicatorBaselineValue($index)
    {
        $values = getVal($this->indicators[$index], ['baseline_value']);
        foreach ($values as $i => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['baseline'][0]['value'] = $value;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @return $this
     */
    protected function setIndicatorBaselineComment($index)
    {
        $value = getVal($this->indicators[$index], ['baseline_comment']);
        if (!is_null($value)) {
            $this->setNarrative(['indicator', $index, 'baseline', 0, 'comment'], 'baseline_comment', 'baseline_comment_language', $this->indicators[$index]);
        }

        return $this;
    }

    /**
     * @param $index
     * @return $this
     */
    protected function setIndicatorPeriod($index)
    {
        $this->groupPeriods();

        foreach ($this->indicators[$index]['period'] as $i => $value) {
            $this->setIndicatorPeriodStart($index, $i)
                 ->setIndicatorPeriodEnd($index, $i)
                 ->setIndicatorPeriodTarget($index, $i)
                 ->setIndicatorPeriodActual($index, $i);
        }

        return $this;
    }

    /**
     *
     */
    protected function groupPeriods()
    {
        foreach ($this->indicators as $indicatorIndex => $values) {
            $grouping                                    = app()->make(Grouping::class, [$this->indicators[$indicatorIndex], $this->periodFields])->groupValues();
            $this->indicators[$indicatorIndex]['period'] = $grouping;
        }
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodStart($index, $i)
    {
        $value = getVal($this->indicators[$index], ['period_start'])[$i];
        if (!is_null($value)) {
            $this->data['indicator'][$index]['period'][$i]['period_start'][0]['date'] = $value;
        }

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodEnd($index, $i)
    {
        $value = getVal($this->indicators[$index], ['period_end'])[$i];
        if (!is_null($value)) {
            $this->data['indicator'][$index]['period'][$i]['period_end'][0]['date'] = $value;
        }

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodTarget($index, $i)
    {
        $this->setIndicatorPeriodTargetValue($index, $i)
             ->setIndicatorPeriodTargetLocationRef($index, $i)
             ->setIndicatorPeriodTargetDimensionName($index, $i)
             ->setIndicatorPeriodTargetDimensionValue($index, $i)
             ->setIndicatorPeriodTargetComment($index, $i);

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodTargetValue($index, $i)
    {
        $value = getVal($this->indicators[$index], ['target_value'])[$i];
        if (!is_null($value)) {
            $this->data['indicator'][$index]['period'][$i]['target'][0]['value'] = $value;
        }

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodTargetLocationRef($index, $i)
    {
        $values = getVal($this->indicators[$index]['period'][$i], ['target_location_ref']);
        foreach ($values as $locationIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['target'][0]['location_ref'][$locationIndex] = $value;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodTargetDimensionName($index, $i)
    {
        $values = getVal($this->indicators[$index]['period'][$i], ['target_dimension_name']);
        foreach ($values as $dIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['target'][0]['dimension_name'][$dIndex] = $value;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodTargetDimensionValue($index, $i)
    {
        $values = getVal($this->indicators[$index]['period'][$i], ['target_dimension_value']);
        foreach ($values as $dIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['target'][0]['dimension_value'][$dIndex] = $value;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodTargetComment($index, $i)
    {
        $values = getVal($this->indicators[$index]['period'][$i], ['target_comment']);
        foreach ($values as $cIndex => $value) {
            if (!is_null($value)) {
                $this->setNarrative(
                    ['indicator', $index, 'period', $i, 'target', 0, 'comment'],
                    'target_comment',
                    'target_comment_language',
                    $this->indicators[$index]['period'][$i]
                );
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodActual($index, $i)
    {
        $this->setIndicatorPeriodActualValue($index, $i)
             ->setIndicatorPeriodActualLocationRef($index, $i)
             ->setIndicatorPeriodActualDimensionName($index, $i)
             ->setIndicatorPeriodActualDimensionValue($index, $i)
             ->setIndicatorPeriodActualComment($index, $i);

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodActualValue($index, $i)
    {
        $value = getVal($this->indicators[$index], ['actual_value'])[$i];
        if (!is_null($value)) {
            $this->data['indicator'][$index]['period'][$i]['actual'][0]['value'] = $value;
        }

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodActualLocationRef($index, $i)
    {
        $values = getVal($this->indicators[$index], ['actual_location_ref']);
        foreach ($values as $locationIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['actual'][0]['location_ref'][$locationIndex] = $value;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodActualDimensionName($index, $i)
    {
        $values = getVal($this->indicators[$index], ['actual_dimension_name']);
        foreach ($values as $dIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['actual'][0]['dimension_name'][$dIndex] = $value;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodActualDimensionValue($index, $i)
    {
        $values = getVal($this->indicators[$index], ['actual_dimension_value']);
        foreach ($values as $dIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['actual'][0]['dimension_value'][$dIndex] = $value;
            }
        }

        return $this;
    }

    /**
     * @param $index
     * @param $i
     * @return $this
     */
    protected function setIndicatorPeriodActualComment($index, $i)
    {
        $values = getVal($this->indicators[$index], ['actual_comment']);
        foreach ($values as $cIndex => $value) {
            if (!is_null($value)) {
                $this->setNarrative(['indicator', $index, 'period', $i, 'actual', 0, 'comment'], 'actual_comment', 'actual_comment_language', $this->indicators[$index]);
            }
        }

        return $this;
    }

    /**
     * @param array $key
     * @param       $narrativeKey
     * @param       $languageKey
     * @param null  $fields
     */
    protected function setNarrative(array $key, $narrativeKey, $languageKey, $fields = null)
    {
        if (is_null($fields)) {
            $data = $this->fields();
        } else {
            $data = $fields;
        }

        $narrative = getVal($data, [$narrativeKey], []);
        $language  = getVal($data, [$languageKey], []);

        foreach ($narrative as $index => $value) {
            if (!is_null($value)) {
                array_set($this->data, implode('.', [implode('.', $key), $index, 'narrative', 0, 'narrative']), $value);
            }
        }

        foreach ($language as $index => $value) {
            if (!is_null($value)) {
                array_set($this->data, implode('.', [implode('.', $key), $index, 'narrative', 0, 'language']), $value);
            }
        }
    }

    /**
     * Process the Row.
     * @return $this
     */
    public function process()
    {
        //
    }

    /**
     * Validate the Row.
     * @return $this
     */
    public function validate()
    {
        $this->validator = $this->factory->sign($this->data())->with($this->rules(), $this->messages())->getValidatorInstance();

        $this->setValidity();

        $this->recordErrors();

        return $this;
    }

    /**
     * Store the Row in a temporary JSON File for further usage.
     */
    public function keep()
    {
        $this->makeDirectoryIfNonExistent()
             ->writeCsvDataAsJson($this->getCsvFilepath());
    }


    /**
     * @return array
     */
    protected function data()
    {
        return $this->data;
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        $rules = [];

        foreach ($this->data['indicator'] as $indicatorIndex => $indicators) {
            foreach ($indicators['period'] as $periodIndex => $periods) {
                $period                                                                                   = $periods['period_start'][0]['date'];
                $rules['indicator.' . $indicatorIndex . '.period.' . $periodIndex . '.period_end.0.date'] = sprintf('required|date_format:Y-m-d|after:%s', $period);
            }
        }

        $rules['type']                                                         = sprintf('required|in:%s', $this->resultTypeCodeList());
        $rules['aggregation_status']                                           = 'boolean';
        $rules['title.*.narrative.0.narrative']                                = 'required';
        $rules['title.*.narrative.0.language']                                 = sprintf('in:%s', $this->languageCodeList());
        $rules['title']                                                        = 'unique_lang|unique_default_lang';
        $rules['description.*.narrative.0.language']                           = sprintf('in:%s', $this->languageCodeList());
        $rules['description']                                                  = 'unique_lang|unique_default_lang';
        $rules['indicator.*.measure']                                          = sprintf('required|in:%s', $this->indicatorMeasureCodeList());
        $rules['indicator.*.ascending']                                        = 'boolean';
        $rules['indicator.*.title.*.narrative.0.narrative']                    = 'required';
        $rules['indicator.*.title.*.narrative.0.language']                     = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.title']                                            = 'unique_lang|unique_default_lang';
        $rules['indicator.*.description.*.narrative.0.language']               = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.description']                                      = 'unique_lang|unique_default_lang';
        $rules['indicator.*.reference.*.vocabulary']                           = sprintf('required|in:%s', $this->indicatorVocabularyCodeList());
        $rules['indicator.*.reference.*.indicator_uri']                        = 'url';
        $rules['indicator.*.baseline.0.year']                                  = 'integer';
        $rules['indicator.*.baseline.0.comment.*.narrative.0.language']        = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.baseline.0.comment']                               = 'unique_lang|unique_default_lang';
        $rules['indicator.*.period.*.period_start.0.date']                     = 'required|date_format:Y-m-d';
        $rules['indicator.*.period.*.target.0.comment.*.narrative.0.language'] = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.period.*.target.0.comment']                        = 'unique_lang|unique_default_lang';
        $rules['indicator.*.period.*.actual.0.comment.*.narrative.0.language'] = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.period.*.actual.0.comment']                        = 'unique_lang|unique_default_lang';

        return $rules;
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages['type.required']                                                   = 'Result type is required';
        $messages['type.in']                                                         = 'Invalid result type';
        $messages['aggregation_status.boolean']                                      = 'Aggregation status type should be true or false';
        $messages['title.*.narrative.0.narrative.required']                          = 'Title is required';
        $messages['title.*.narrative.0.language.in']                                 = 'Title language should be in the language code list';
        $messages['title.unique_lang']                                               = 'Repeated title in same language';
        $messages['description.unique_lang']                                         = 'Repeated description in same language';
        $messages['indicator.*.title.unique_lang']                                   = 'Repeated indicator title in same language';
        $messages['indicator.*.description.unique_lang']                             = 'Repeated indicator description in same language';
        $messages['indicator.*.baseline.0.comment.unique_lang']                      = 'Repeated baseline comment in same language';
        $messages['indicator.*.period.*.target.0.comment.unique_lang']               = 'Repeated period target comment in same language';
        $messages['indicator.*.period.*.actual.0.comment.unique_lang']               = 'Repeated period actual comment in same language';
        $messages['description.*.narrative.0.language.in']                           = 'Description language should be in the language code list';
        $messages['indicator.*.measure.required']                                    = 'Indicator measure is required';
        $messages['indicator.*.measure.in']                                          = 'Indicator measure should be from indicator measure code list';
        $messages['indicator.*.ascending.boolean']                                   = 'Indicator ascending should be true or false';
        $messages['indicator.*.title.*.narrative.0.narrative.required']              = 'Indicator title is required';
        $messages['indicator.*.title.*.narrative.0.language.in']                     = 'Indicator title language should be in the language code list';
        $messages['indicator.*.description.*.narrative.0.language.in']               = 'Indicator description language should be in the language code list';
        $messages['indicator.*.reference.*.vocabulary.in']                           = 'Reference vocabulary should be in the indicator vocabulary code list';
        $messages['indicator.*.reference.*.indicator_uri.url']                       = 'Invalid reference url';
        $messages['indicator.*.baseline.0.year.integer']                             = 'Indicator baseline year should be integer';
        $messages['indicator.*.baseline.0.comment.*.narrative.0.narrative.required'] = 'Baseline comment narrative is required';
        $messages['indicator.*.baseline.0.comment.*.narrative.0.language.in']        = 'Baseline comment language is invalid';
        $messages['indicator.*.period.*.period_start.0.date.required']               = 'Period start date is required';
        $messages['indicator.*.period.*.period_start.0.date.date_format']            = 'Invalid period start date, correct format Y-m-d';
        $messages['indicator.*.period.*.period_end.0.date.required']                 = 'The period_end date is required';
        $messages['indicator.*.period.*.period_end.0.date.date_format']              = 'Invalid period end date, correct format Y-m-d';
        $messages['indicator.*.period.*.target.0.comment.*.narrative.0.language.in'] = 'Target comment language should be in the language code list';
        $messages['indicator.*.period.*.actual.0.comment.*.narrative.0.language.in'] = 'Actual comment language should be in the language code list';

        return $messages;
    }

    /**
     * Set the validity for the whole ActivityRow.
     * @return $this
     */
    protected function validateSelf()
    {
        if (in_array(false, $this->validElements)) {
            $this->isValid = false;
        } else {
            $this->isValid = true;
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function resultTypeCodeList()
    {
        $resultTypes = $this->loadCodeList('ResultType', 'V201');
        $codes       = [];
        foreach ($resultTypes['ResultType'] as $type) {
            $codes[] = $type['code'];
        }

        return implode(',', $codes);
    }

    /**
     * @return string
     */
    private function languageCodeList()
    {
        $languageList = $this->loadCodeList('Language', 'V201');

        $codes = [];
        foreach ($languageList['Language'] as $code) {
            $codes[] = $code['code'];
        }

        return implode(',', $codes);
    }

    /**
     * @param        $codeList
     * @param        $version
     * @param string $directory
     * @return mixed
     */
    protected function loadCodeList($codeList, $version, $directory = "Activity")
    {
        return json_decode(file_get_contents(app_path(sprintf('Core/%s/Codelist/en/%s/%s.json', $version, $directory, $codeList))), true);
    }

    /**
     * Make the storage directory, if it does not exist, to store the validated Csv data before import.
     */
    protected function makeDirectoryIfNonExistent()
    {
        $path = sprintf('%s/%s/%s/', storage_path(self::CSV_DATA_STORAGE_PATH), $this->organizationId, $this->userId);

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        shell_exec(sprintf('chmod 777 -R %s', $path));

        return $this;
    }

    /**
     * Get the file path for the validated Csv data to be stored before import.
     * @return string
     */
    protected function getCsvFilepath()
    {
        if ($this->isValid) {
            return storage_path(sprintf('%s/%s/%s/%s', self::CSV_DATA_STORAGE_PATH, $this->organizationId, $this->userId, self::VALID_CSV_FILE));
        }

        return storage_path(sprintf('%s/%s/%s/%s', self::CSV_DATA_STORAGE_PATH, $this->organizationId, $this->userId, self::INVALID_CSV_FILE));
    }

    /**
     * Write the validated data into the designated destination file.
     * @param $destinationFilePath
     */
    protected function writeCsvDataAsJson($destinationFilePath)
    {
        if (file_exists($destinationFilePath)) {
            $this->appendDataIntoFile($destinationFilePath);
        } else {
            $this->createNewFile($destinationFilePath);
        }
    }

    /**
     * Append data into the file containing previous data.
     * @param $destinationFilePath
     */
    protected function appendDataIntoFile($destinationFilePath)
    {
        if ($currentContents = json_decode(file_get_contents($destinationFilePath), true)) {
            $currentContents[] = ['data' => $this->data(), 'errors' => $this->errors(), 'status' => 'processed'];

            file_put_contents($destinationFilePath, json_encode($currentContents));
        } else {
            $this->createNewFile($destinationFilePath);
        }
    }

    /**
     * Write the validated data into a new file.
     * @param $destinationFilePath
     */
    protected function createNewFile($destinationFilePath)
    {
        file_put_contents($destinationFilePath, json_encode([['data' => $this->data(), 'errors' => $this->errors(), 'status' => 'processed']]));
        shell_exec(sprintf('chmod 777 -R %s', $destinationFilePath));
    }

    /**
     * Get all the errors associated with the current ActivityRow.
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Record errors within the ActivityRow.
     */
    protected function recordErrors()
    {
        foreach ($this->validator->errors()->getMessages() as $errors) {
            foreach ($errors as $error) {
                $this->errors[] = $error;
            }
        }

        $this->errors = array_unique($this->errors);

        return $this;
    }

    /**
     * @return string
     */
    private function indicatorMeasureCodeList()
    {
        $list = $this->loadCodeList('IndicatorMeasure', 'V201');

        $codes = [];
        foreach ($list['IndicatorMeasure'] as $code) {
            $codes[] = $code['code'];
        }

        return implode(',', $codes);
    }

    /**
     * @return string
     */
    private function indicatorVocabularyCodeList()
    {
        $list = $this->loadCodeList('IndicatorVocabulary', 'V201');

        $codes = [];
        foreach ($list['data'] as $code) {
            $codes[] = $code['code'];
        }

        return implode(',', $codes);
    }

    /**
     *
     */
    protected function setValidity()
    {
        $this->isValid = $this->validator->passes();
    }

    /**
     * Initialize the Row object.
     * @return mixed
     */
    public function init()
    {
        // TODO: Implement init() method.
        return;
    }

    /**
     * @param $values
     * @return bool
     */
    private function isBoolean($values)
    {
        if (((int) $values === 1) || ($values === true) || ($values === true) || ($values === "true") || ($values === "TRUE")) {
            return 1;
        } else {
            if (((int) $values === 0) || ($values === false) || ($values === false) || ($values === "false") || ($values === "FALSE")) {
                return 0;
            }
        }

        return $values;
    }
}

