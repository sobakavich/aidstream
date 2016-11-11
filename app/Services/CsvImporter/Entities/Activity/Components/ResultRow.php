<?php namespace App\Services\CsvImporter\Entities\Activity\Components;

use App\Services\CsvImporter\Entities\Row;

/**
 * Class ActivityRow
 * @package App\Services\CsvImporter\Entities\Activity\Components
 */
class ResultRow extends Row
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
    /**
     * All Elements for an Result Row.
     * @var
     */
    protected $elements;

    protected $indicators = [];

    /**
     * @var
     */
    protected $type;

    /**
     * @var
     */
    protected $aggregationStatus;

    /**
     * @var
     */
    public $title;

    /**
     * @var
     */
    public $titleDescription;

    /**
     * @var
     */
    protected $description;

    /**
     * @var
     */
    protected $descriptionLanguage;

    /**
     * @var
     */
    protected $measure;

    /**
     * @var
     */
    protected $ascending;

    /**
     * @var
     */
    protected $indicatorTitle;

    /**
     * @var
     */
    protected $indicatorTitleLanguage;

    /**
     * @var
     */
    protected $indicatorDescription;

    /**
     * @var
     */
    protected $indicatorDescriptionLanguage;

    /**
     * @var
     */
    public $referenceVocabulary;

    /**
     * @var
     */
    public $referenceCode;

    /**
     * @var
     */
    public $referenceURI;

    /**
     * @var
     */
    public $baselineYear;

    /**
     * @var
     */
    public $baselineValue;

    /**
     * @var
     */
    public $baselineComment;

    /**
     * @var
     */
    public $baselineCommentLanguage;

    /**
     * @var
     */
    public $periodStart;

    /**
     * @var array
     */
    protected $periodEnd;

    /**
     * @var
     */
    protected $budget;

    /**
     * @var
     */
    protected $activityScope;

    /**
     * @var
     */
    protected $policyMarker;

    /**
     * @var array
     */
    protected $validElements = [];

    /**
     * Current Organization's id.
     * @var
     */
    protected $organizationId;

    /**
     * Current User's id.
     * @var
     */
    protected $userId;

    protected $result;

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
     * ActivityRow constructor.
     * @param $fields
     * @param $organizationId
     * @param $userId
     */
    public function __construct($fields, $organizationId, $userId)
    {
        $this->fields         = $fields;
        $this->organizationId = $organizationId;
        $this->userId         = $userId;
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
        $this->data = $this->loadTemplate();
        $this->beginMapping();

        return $this;
    }

    protected function loadTemplate()
    {
//        return json_decode(file_get_contents(app_path('Services/CsvImporter/Entities/Activity/Components/Elements/Foundation/Template/Result.json')), true);
    }

    protected function beginMapping()
    {
        $this->setType()
             ->setAggregationStatus()
             ->setTitle()
             ->setDescription()
             ->setIndicator();
        dd($this->data);
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
            ;
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
                $this->data['indicator'][$index]['reference'][$i][$this->resultFields['indicator'][6]] = $values;
            }
        }

        return $this;
    }

    protected function setReferenceCode($index)
    {
        $array = getVal($this->indicators[$index], [$this->resultFields['indicator'][7]], []);
        foreach ($array as $i => $values) {
            if (!is_null($values)) {
                $this->data['indicator'][$index]['reference'][$i][$this->resultFields['indicator'][7]] = $values;
            }
        }

        return $this;
    }

    protected function setReferenceURI($index)
    {
        $array = getVal($this->indicators[$index], [$this->resultFields['indicator'][8]], []);
        foreach ($array as $i => $values) {
            if (!is_null($values)) {
                $this->data['indicator'][$index]['reference'][$i][$this->resultFields['indicator'][8]] = $values;
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

    protected function setIndicatorPeriod()
    {

    }

    protected function setIndicatorPeriodStart()
    {

    }

    protected function setIndicatorPeriodEnd()
    {

    }

    protected function setIndicatorPeriodTarget()
    {

    }

    protected function setIndicatorPeriodTargetValue()
    {

    }

    protected function setIndicatorPeriodTargetComment()
    {

    }

    protected function setIndicatorPeriodActualValue()
    {

    }

    protected function setIndicatorPeriodActualComment()
    {

    }

    protected function setNarrative(array $key, $narrativeKey, $languageKey, $fields = null)
    {
        if(is_null($fields)) {
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
     * Map Transaction data into singular Transaction block for each Activity.
     */
    protected function mapTransactionData()
    {
        foreach ($this->fields() as $key => $values) {
            if (array_key_exists($key, array_flip($this->transactionCSVHeaders))) {
                foreach ($values as $index => $value) {
                    $this->transactionRows[$index][$key] = $value;
                }
            }
        }

        $this->removeEmptyTransactionData();
    }

    /**
     * Remove empty Transaction rows.
     */
    protected function removeEmptyTransactionData()
    {
        foreach ($this->transactionRows as $index => $transactionRow) {
            $totalNull = 0;
            foreach ($transactionRow as $value) {
                if (!$value) {
                    $totalNull ++;
                }
            }

            if ($totalNull == count($this->transactionCSVHeaders)) {
                unset($this->transactionRows[$index]);
            }
        }
    }

    /**
     * Get the Activity elements.
     * @return array
     */
    protected function activityElements()
    {
        return $this->activityElements;
    }

    /**
     * Get the Transaction Elements.
     * @return array
     */
    protected function transactionElement()
    {
        return $this->transactionElement;
    }

    /**
     * Get the other Elements.
     * @return array
     */
    protected function otherElements()
    {
        return $this->otherElements;
    }

    /**
     * Validate all elements contained in the ActivityRow.
     */
    protected function validateElements()
    {
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

    /**
     * Initialize the Row object.
     * @return mixed
     */
    public function init()
    {
        // TODO: Implement init() method.
    }

}
