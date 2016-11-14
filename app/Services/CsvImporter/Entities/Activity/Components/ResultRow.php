<?php namespace App\Services\CsvImporter\Entities\Activity\Components;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;
use App\Services\CsvImporter\Entities\Row;
use App\Services\CsvImporter\Entities\Activity\Components\Grouping;

/**
 * Class ActivityRow
 * @package App\Services\CsvImporter\Entities\Activity\Components
 */
class ResultRow
{

    /**
     * Base Namespace for the Activity Element classes.
     */
    const BASE_NAMESPACE = 'App\Services\CsvImporter\Entities\Activity\Components\Elements';

    /**
     * Number of headers for the Activity Csv.
     */
    const RESULT_HEADER_COUNT = 33;

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

    /**
     * @var array
     */
    protected $otherElements = ['activityScope', 'budget', 'policyMarker'];

    protected $data = [];

    protected $fields;
    /**
     * All Elements for an Result Row.
     * @var
     */

    protected $indicators = [];

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

    protected $validator;

    /**
     * ActivityRow constructor.
     * @param $fields
     * @param $organizationId
     * @param $userId
     */
    public function __construct($fields, $organizationId, $userId, Validation $validator)
    {
        $this->fields         = $fields;
        $this->organizationId = $organizationId;
        $this->userId         = $userId;
        $this->validator      = $validator;
    }

    /**
     * Group rows into single Activities.
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

    /**
     * @return $this
     * @internal param $fields
     */
    public function mapResultRow()
    {
        $this->beginMapping();

        return $this;
    }

    protected function beginMapping()
    {
        $this->setType()
             ->setAggregationStatus()
             ->setTitle()
             ->setDescription()
             ->setIndicator();
//        dd($this->data);
    }

    protected function setType()
    {
        $value = getVal($this->fields, [$this->resultFields[0]]);
        if (!is_null($value)) {
            $this->data[$this->resultFields[0]] = $value[0];
        }

        return $this;
    }

    protected function setAggregationStatus()
    {
        $value = getVal($this->fields, [$this->resultFields[1]], []);
        if (!is_null($value)) {
            $this->data[$this->resultFields[1]] = $value[0];
        }

        return $this;
    }

    protected function setTitle()
    {
        $this->setNarrative(['title'], $this->resultFields[2], $this->resultFields[3]);

        return $this;
    }

    protected function setDescription()
    {
        $this->setNarrative(['description'], $this->resultFields[4], $this->resultFields[5]);

        return $this;
    }

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


    protected function groupIndicator()
    {
        $this->groupValues();
    }

    protected function setIndicatorMeasure($index)
    {
        $measure = getVal($this->indicators[$index], [$this->resultFields['indicator'][0]], []);
        foreach ($measure as $values) {
            if (!is_null($values)) {
                $this->data['indicator'][$index][$this->resultFields['indicator'][0]] = $values;
            }
        }

        return $this;
    }

    protected function setIndicatorAscending($index)
    {
        $ascending = getVal($this->indicators[$index], [$this->resultFields['indicator'][1]], []);
        foreach ($ascending as $values) {
            if (!is_null($values)) {
                $this->data['indicator'][$index][$this->resultFields['indicator'][1]] = $values;
            }
        }

        return $this;
    }

    protected function setIndicatorTitle($index)
    {
        $this->setNarrative(['indicator', $index, 'title'], $this->resultFields['indicator'][2], $this->resultFields['indicator'][3], $this->indicators[$index]);

        return $this;
    }

    protected function setIndicatorDescription($index)
    {
        $this->setNarrative(['indicator', $index, 'description'], $this->resultFields['indicator'][4], $this->resultFields['indicator'][5], $this->indicators[$index]);

        return $this;
    }

    protected function setReferenceVocabulary($index)
    {
        $array = getVal($this->indicators[$index], [$this->resultFields['indicator'][6]], []);
        foreach ($array as $i => $values) {
            if (!is_null($values)) {
                $this->data['indicator'][$index]['reference'][$i]['vocabulary'] = $values;
            }
        }

        return $this;
    }

    protected function setReferenceCode($index)
    {
        $array = getVal($this->indicators[$index], [$this->resultFields['indicator'][7]], []);
        foreach ($array as $i => $values) {
            if (!is_null($values)) {
                $this->data['indicator'][$index]['reference'][$i]['code'] = $values;
            }
        }

        return $this;
    }

    protected function setReferenceURI($index)
    {
        $array = getVal($this->indicators[$index], [$this->resultFields['indicator'][8]], []);
        foreach ($array as $i => $values) {
            if (!is_null($values)) {
                $this->data['indicator'][$index]['reference'][$i]['indicator-uri'] = $values;
            }
        }

        return $this;
    }

    protected function setIndicatorBaseline($index)
    {
        $this->setIndicatorBaselineYear($index)
             ->setIndicatorBaselineValue($index)
             ->setIndicatorBaselineComment($index);

        return $this;
    }

    protected function setIndicatorBaselineYear($index)
    {
        $values = getVal($this->indicators[$index], [$this->resultFields['indicator'][9]]);
        foreach ($values as $i => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['baseline'][0]['year'][$i] = $value;
            }
        }

        return $this;
    }

    protected function setIndicatorBaselineValue($index)
    {
        $values = getVal($this->indicators[$index], [$this->resultFields['indicator'][10]]);
        foreach ($values as $i => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['baseline'][0]['value'][$i] = $value;
            }
        }

        return $this;
    }

    protected function setIndicatorBaselineComment($index)
    {
        $value = getVal($this->indicators[$index], [$this->resultFields['indicator'][11]]);
        if (!is_null($value)) {
            $this->setNarrative(['indicator', $index, 'baseline', 0, 'comment'], $this->resultFields['indicator'][11], $this->resultFields['indicator'][12], $this->indicators[$index]);
        }

        return $this;
    }

    protected function setIndicatorPeriod($index)
    {
        $this->groupPeriods();
//        dd($this->indicators[$index]['period']);
        foreach ($this->indicators[$index]['period'] as $i => $value) {
            $this->setIndicatorPeriodStart($index, $i)
                 ->setIndicatorPeriodEnd($index, $i)
                 ->setIndicatorPeriodTarget($index, $i)
                 ->setIndicatorPeriodActual($index, $i);
        }

        return $this;
    }

    protected function groupPeriods()
    {
        foreach ($this->indicators as $indicatorIndex => $values) {
            $grouping                                    = app()->make(Grouping::class, [$this->indicators[$indicatorIndex], $this->periodFields])->groupValues();
            $this->indicators[$indicatorIndex]['period'] = $grouping;
        }
    }

    protected function setIndicatorPeriodStart($index, $i)
    {
        $value = getVal($this->indicators[$index], [$this->resultFields['indicator'][13]])[$i];
        if (!is_null($value)) {
            $this->data['indicator'][$index]['period'][$i]['period-start']['date'] = $value;
        }

        return $this;
    }

    protected function setIndicatorPeriodEnd($index, $i)
    {
        $value = getVal($this->indicators[$index], [$this->resultFields['indicator'][14]])[$i];
        if (!is_null($value)) {
            $this->data['indicator'][$index]['period'][$i]['period-end']['date'] = $value;
        }

        return $this;
    }

    protected function setIndicatorPeriodTarget($index, $i)
    {
        $this->setIndicatorPeriodTargetValue($index, $i)
             ->setIndicatorPeriodTargetLocationRef($index, $i)
             ->setIndicatorPeriodTargetDimensionName($index, $i)
             ->setIndicatorPeriodTargetDimensionValue($index, $i)
             ->setIndicatorPeriodTargetComment($index, $i);

        return $this;

    }

    protected function setIndicatorPeriodTargetValue($index, $i)
    {
        $value = getVal($this->indicators[$index], [$this->resultFields['indicator'][15]])[$i];
        if (!is_null($value)) {
            $this->data['indicator'][$index]['period'][$i]['target']['value'] = $value;
        }

        return $this;

    }

    protected function setIndicatorPeriodTargetLocationRef($index, $i)
    {
        $values = getVal($this->indicators[$index]['period'][$i], [$this->resultFields['indicator'][16]]);
        foreach ($values as $locationIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['target']['location_ref'][$locationIndex] = $value;
            }
        }

        return $this;

    }

    protected function setIndicatorPeriodTargetDimensionName($index, $i)
    {
        $values = getVal($this->indicators[$index]['period'][$i], [$this->resultFields['indicator'][17]]);
        foreach ($values as $dIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['target']['dimension_name'][$dIndex] = $value;
            }
        }

        return $this;

    }

    protected function setIndicatorPeriodTargetDimensionValue($index, $i)
    {
        $values = getVal($this->indicators[$index]['period'][$i], [$this->resultFields['indicator'][18]]);
        foreach ($values as $dIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['target']['dimension_value'][$dIndex] = $value;
            }
        }

        return $this;

    }

    protected function setIndicatorPeriodTargetComment($index, $i)
    {
        $values = getVal($this->indicators[$index]['period'][$i], [$this->resultFields['indicator'][19]]);
        foreach ($values as $cIndex => $value) {
            if (!is_null($value)) {
                $this->setNarrative(
                    ['indicator', $index, 'period', $i, 'target', 'comment'],
                    $this->resultFields['indicator'][19],
                    $this->resultFields['indicator'][20],
                    $this->indicators[$index]['period'][$i]
                );
            }
        }

        return $this;
    }

    protected function setIndicatorPeriodActual($index, $i)
    {
        $this->setIndicatorPeriodActualValue($index, $i)
             ->setIndicatorPeriodActualLocationRef($index, $i)
             ->setIndicatorPeriodActualDimensionName($index, $i)
             ->setIndicatorPeriodActualDimensionValue($index, $i)
             ->setIndicatorPeriodActualComment($index, $i);

        return $this;

    }

    protected function setIndicatorPeriodActualValue($index, $i)
    {
        $value = getVal($this->indicators[$index], [$this->resultFields['indicator'][21]])[$i];
        if (!is_null($value)) {
            $this->data['indicator'][$index]['period'][$i]['actual']['value'] = $value;
        }

        return $this;

    }

    protected function setIndicatorPeriodActualLocationRef($index, $i)
    {
        $values = getVal($this->indicators[$index], [$this->resultFields['indicator'][22]]);
        foreach ($values as $locationIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['actual']['location_ref'][$locationIndex] = $value;
            }
        }

        return $this;

    }

    protected function setIndicatorPeriodActualDimensionName($index, $i)
    {
        $values = getVal($this->indicators[$index], [$this->resultFields['indicator'][23]]);
        foreach ($values as $dIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['actual']['dimension_name'][$dIndex] = $value;
            }
        }

        return $this;

    }

    protected function setIndicatorPeriodActualDimensionValue($index, $i)
    {
        $values = getVal($this->indicators[$index], [$this->resultFields['indicator'][24]]);
        foreach ($values as $dIndex => $value) {
            if (!is_null($value)) {
                $this->data['indicator'][$index]['period'][$i]['actual']['dimension_value'][$dIndex] = $value;
            }
        }

        return $this;

    }

    protected function setIndicatorPeriodActualComment($index, $i)
    {
        $values = getVal($this->indicators[$index], [$this->resultFields['indicator'][25]]);
        foreach ($values as $cIndex => $value) {
            if (!is_null($value)) {
                $this->setNarrative(['indicator', $index, 'period', $i, 'actual', 'comment'], $this->resultFields['indicator'][25], $this->resultFields['indicator'][26], $this->indicators[$index]);
            }
        }

        return $this;
    }

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

    }

    /**
     * Validate the Row.
     * @return $this
     */
    public function validate()
    {
        $this->validateElements()->validateSelf();

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
     * Validate all elements contained in the ActivityRow.
     */
    protected function validateElements()
    {
        $this->validator->sign($this->data())->with($this->rules(), $this->messages())->getValidatorInstance();
        foreach ($this->elements() as $element) {
            if ($element == 'transaction') {
                foreach ($this->$element as $transaction) {
                    $transaction->validate()->withErrors();
                    $this->recordErrors($transaction);

                    $this->validElements[] = $transaction->isValid();
                }
            } else {
                $this->$element->validate()->withErrors();
                $this->recordErrors($this->$element);

                $this->validElements[] = $this->$element->isValid();
            }
        }

        return $this;
    }

    public function rules()
    {

        $rules = [];

        $rules['type']                                = sprintf('required|in:%s', $this->resultTypeCodeList());
        $rules['aggregation_status']                  = 'boolean';
        $rules['title.*.narrative']                   = 'required';
        $rules['title.*.narrative.0.narrative']       = 'required|string';
        $rules['title.*.narrative.0.language']        = sprintf('in:%s', $this->languageCodeList());
        $rules['description.*.narrative.0.narrative'] = 'string';
        $rules['description.*.narrative.0.language']  = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.measure']                 = sprintf('required|string|in:%s', $this->indicatorMeasureCodeList());
        $rules['indicator.*.ascending'] = 'boolean';
        $rules['indicator.*.title.*.narrative.0.narrative']       = 'required|string';
        $rules['indicator.*.title.*.narrative.0.language']        = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.description.*.narrative.0.narrative']       = 'string';
        $rules['indicator.*.description.*.narrative.0.language']        = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.reference.*.vocabulary'] = sprintf('required|string|in:%s', $this->indicatorVocabularyCodeList());
        $rules['indicator.*.reference.*.code'] = 'require|string';
        $rules['indicator.*.reference.*.indicator-uri'] = 'url';
        $rules['indicator.*.baseline.0.year.*'] = 'required|integer';
        $rules['indicator.*.baseline.0.value.*'] = 'required|string';
        $rules['indicator.*.baseline.0.comment.*.narrative.0.narrative'] = 'required|string';
        $rules['indicator.*.baseline.0.comment.*.narrative.0.language'] = sprintf('in:%s', $this->languageCodeList());
        $period_start = $rules['indicator.*.period.*.period-start.0.date'] = 'required|date';
        $rules['indicator.*.period.*.period-end.0.date'] = sprintf('required|date|after:%s', $period_start);
        $rules['indicator.*.period.*.target.value'] = 'required|string';
        $rules['indicator.*.period.*.target.location_ref'] = 'string';
        $rules['indicator.*.period.*.target.dimension_name'] = 'string';
        $rules['indicator.*.period.*.target.dimension_value'] = 'string';
        $rules['indicator.*.period.*.target.comment.0.narrative.0.narrative'] = 'string';
        $rules['indicator.*.period.*.target.comment.0.narrative.0.language'] = sprintf('in:%s', $this->languageCodeList());
        $rules['indicator.*.period.*.actual.value'] = 'required|string';
        $rules['indicator.*.period.*.actual.location_ref'] = 'string';
        $rules['indicator.*.period.*.actual.dimension_name'] = 'string';
        $rules['indicator.*.period.*.actual.dimension_value'] = 'string';
        $rules['indicator.*.period.*.actual.comment.0.narrative.0.narrative'] = 'string';
        $rules['indicator.*.period.*.actual.comment.0.narrative.0.language'] = sprintf('in:%s', $this->languageCodeList());

        dd($rules);
    }

    protected function messages()
    {
        $messages = [];

        $messages['type.required'] = 'Result type is required';
        $messages['type.in']       = 'Entered result type is invalid.';


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

    protected function resultTypeCodeList()
    {
        $resultTypes = $this->loadCodeList('ResultType', 'V201');
        $codes       = [];
        foreach ($resultTypes['ResultType'] as $type) {
            $codes[] = $type['code'];
        }

        return implode(',', $codes);
    }

    private function languageCodeList()
    {
        $languageList = $this->loadCodeList('Language', 'V201');

        $codes = [];
        foreach ($languageList['Language'] as $code) {
            $codes[] = $code['code'];
        }

        return implode(',', $codes);

    }

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
     * @param $element
     */
    protected function recordErrors($element)
    {
        foreach ($element->errors() as $errors) {
            $this->errors[] = $errors;
        }
    }

    /**
     * Validate unique against Identifiers and Transaction Internal References within the uploaded CSV file.
     * @param $rows
     * @return $this
     */
    public function validateUnique($rows)
    {
        $commonIdentifierCount = $this->countDuplicateActivityIdentifiers($rows);
        $references            = $this->getTransactionInternalReferences();

        if ($this->containsDuplicateActivities($commonIdentifierCount) || $this->containsDuplicateTransactions($references)) {
            $this->isValid = false;
        }

        return $this;
    }

    /**
     * Get the Transactions for the ActivityRow.
     * @return array
     */
    public function getTransactions()
    {
        return $this->transaction;
    }

    /**
     * Get all the internal references for an Activity's Transactions.
     * @return array
     */
    protected function getTransactionInternalReferences()
    {
        $references = [];

        foreach ($this->getTransactions() as $transaction) {
            $references[] = getVal($transaction->data(), ['transaction', 'reference']);
        }

        return $references;
    }

    /**
     * Get the count of duplicated Activity Identifiers.
     * @param $rows
     * @return int
     */
    protected function countDuplicateActivityIdentifiers($rows)
    {
        $commonIdentifierCount = 0;

        foreach ($rows as $index => $row) {
            if (array_key_exists('activity_identifier', $row)) {
                if ($this->identifier->data()['activity_identifier'] == getVal($row, ['activity_identifier', 0])) {
                    $commonIdentifierCount ++;
                }
            }
        }

        return $commonIdentifierCount;
    }

    /**
     * Check if the Transaction Internal References are duplicated within the uploaded CSV file.
     * @param $references
     * @return bool
     */
    protected function containsDuplicateTransactions($references)
    {
        return (count(array_unique($references)) != count($this->getTransactions()));
    }

    /**
     * Check if the Activity Identifiers are duplicated within the uploaded CSV file.
     * @param $commonIdentifierCount
     * @return bool
     */
    protected function containsDuplicateActivities($commonIdentifierCount)
    {
        return ($commonIdentifierCount > 1);
    }

    private function fields()
    {
        return $this->fields;
    }

    private function indicatorMeasureCodeList()
    {
        $list = $this->loadCodeList('IndicatorMeasure', 'V201');

        $codes = [];
        foreach ($list['IndicatorMeasure'] as $code) {
            $codes[] = $code['code'];
        }

        return implode(',', $codes);

    }

    private function indicatorVocabularyCodeList()
    {
        $list = $this->loadCodeList('IndicatorVocabulary', 'V201');

        $codes = [];
        foreach ($list['data'] as $code) {
            $codes[] = $code['code'];
        }

        return implode(',', $codes);

    }


}
