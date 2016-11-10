<?php namespace App\Services\CsvImporter\Entities\Activity\Components;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Result;
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
     * Result Elements for an Result Row.
     * @var array
     */
    protected $activityElements = [
        'result',
    ];

    /**
     * @var array
     */
    protected $otherElements = ['activityScope', 'budget', 'policyMarker'];
    /**
     * All Elements for an Result Row.
     * @var
     */
    protected $elements;

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
//        dd($this->fields());
//        $this->init();
    }

    /**
     * Initialize the Row object.
     */
    public function init()
    {
        dd($this->fields);
        $method = $this->getMethodNameByType();
        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    /**
     * Initiate the ActivityRow elements for Activity Csv.
     */
    public function activity()
    {
        $this->makeActivityElements();
    }

    /**
     * Initiate the ActivityRow elements with Activity with Transactions Csv.
     */
    public function transaction()
    {
        $this->makeActivityElements()->makeTransactionElements();
    }

    /**
     * Initiate the ActivityRow elements with Activity and Other Fields.
     */
    public function otherFields()
    {
        $this->makeActivityElements()->makeOtherFieldsElements();
    }

    /**
     * Initiate the ActivityRow elements with Activity, Transaction and Other Fields.
     */
    public function otherFieldsWithTransaction()
    {
        $this->makeActivityElements()->makeTransactionElements()->makeOtherFieldsElements();
    }

    /**
     * Process the Row.
     * @return $this
     */
    public function process()
    {
//        dd($this->fields());
        $this->result = app()->make(Result::class, [$this->fields]);
        dd($this->result);

        return $this;
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
     * Get the name of a method according to the type of uploaded Csv.
     * @return null|string
     */
    protected function getMethodNameByType()
    {
        if (count($this->fields()) == self::ACTIVITY_HEADER_COUNT) {
            return 'activity';
        }

        if (count($this->fields()) == self::TRANSACTION_HEADER_COUNT) {
            return 'transaction';
        }

        if (count($this->fields()) == self::ACTIVITY_OTHERS_HEADER_COUNT) {
            return 'otherFields';
        }

        if (count($this->fields()) == self::ACTIVITY_TRANSACTION_OTHERS_HEADER_COUNT) {
            return 'otherFieldsWithTransaction';
        }

        return null;
    }

    /**
     * Instantiate the Activity Element classes.
     * @return $this
     */
    protected function makeActivityElements()
    {
        foreach ($this->activityElements() as $element) {
            if (class_exists($namespace = $this->getNamespace($element, self::BASE_NAMESPACE))) {
                $this->$element   = $this->make($namespace, $this->fields());
                $this->elements[] = $element;
            }
        }

        return $this;
    }

    /**
     * Instantiate the Transaction Element classes.
     * @return $this
     */
    protected function makeTransactionElements()
    {
        $this->mapTransactionData();

        foreach ($this->transactionRows as $transactionRow) {
            if (class_exists($namespace = $this->getNamespace($this->transactionElement(), self::BASE_NAMESPACE))) {
                $this->transaction[] = $this->make($namespace, $transactionRow, $this);
            }
        }

        $this->elements[] = $this->transactionElement();

        return $this;
    }

    /**
     *
     */
    protected function makeOtherFieldsElements()
    {
        foreach ($this->otherElements() as $element) {
            if (class_exists($namespace = $this->getNamespace($element, self::BASE_NAMESPACE))) {
                $this->$element   = $this->make($namespace, $this->fields());
                $this->elements[] = $element;
            }
        }

        return $this;
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
     * Get the data in the current ActivityRow.
     * @return array
     */
    protected function data()
    {
        $this->data = [];

        foreach ($this->elements() as $element) {
            if ($element == 'transaction') {
                foreach ($this->$element as $transaction) {
                    $this->data[$element][] = $transaction->data($transaction->pluckIndex());
                }
            } else {
                $this->data[snake_case($element)] = ($element === 'identifier')
                    ? $this->$element->data()
                    : $this->$element->data(snake_case($this->$element->pluckIndex()));
            }
        }

        return $this->data;
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
}
