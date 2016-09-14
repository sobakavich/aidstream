<?php namespace App\Services\CsvImporter\Entities\Activity\Components;

use App\Services\CsvImporter\Entities\Row;

/**
 * Class ActivityRow
 * @package App\Services\CsvImporter\Entities\Activity\Components
 */
class ActivityRow extends Row
{
    /**
     * Base Namespace for the Activity Element classes.
     */
    const BASE_NAMESPACE = 'App\Services\CsvImporter\Entities\Activity\Components\Elements';

    /**
     * Namespace for the Transaction Element classes.
     */
    const TRANSACTION_NAMESPACE = 'App\Services\CsvImporter\Entities\Activity\Components\Elements\Transaction';

    /**
     * Number of headers for the Activity Csv.
     */
    const ACTIVITY_HEADER_COUNT = 22;

    /**
     * Number of headers for the Activity Csv with Transactions.
     */
    const TRANSACTION_HEADER_COUNT = 39;

    /**
     * Directory where the validated Csv data is written before import.
     */
    const CSV_DATA_STORAGE_PATH = 'csvImporter/tmp';

    /**
     * File in which the valida Csv data is written before import.
     */
    const VALID_CSV_FILE = 'valid.json';

    /**
     * File in which the invalid Csv data is written before import.
     */
    const INVALID_CSV_FILE = 'invalid.json';

    /**
     * Activity Elements for an Activity Row.
     * @var array
     */
    protected $activityElements = ['identifier', 'title', 'description', 'activityStatus', 'activityDate', 'participatingOrganization', 'recipientCountry', 'recipientRegion', 'sector'];

    /**
     * Transaction Elements for an Activity Row.
     * @var string
     */
    protected $transactionElement = 'transaction';

    /**
     * @var array
     */
    protected $transactionRows = [];

    /**
     * All Elements for an Activity Row.
     * @var
     */
    protected $elements;

    /**
     * @var
     */
    protected $identifier;

    /**
     * @var
     */
    protected $title;

    /**
     * @var
     */
    protected $description;

    /**
     * @var
     */
    protected $activityStatus;

    /**
     * @var
     */
    protected $activityDate;

    /**
     * @var
     */
    protected $participatingOrganisation;

    /**
     * @var
     */
    protected $recipientCountry;

    /**
     * @var
     */
    protected $recipientRegion;

    /**
     * @var
     */
    protected $sector;

    /**
     * @var array
     */
    protected $transactions = [];

    /**
     * ActivityRow constructor.
     * @param                   $fields
     */
    public function __construct($fields)
    {
        $this->fields = $fields;
        $this->init();
    }

    /**
     * Initialize the Row object.
     */
    public function init()
    {
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
     * Process the Row.
     * @return $this
     */
    public function process()
    {
        return $this;
    }

    /**
     * Validate the Row.
     * @return $this
     */
    public function validate()
    {
        $this->validateSelf($this->validateElements());

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
        foreach ($this->transactionRows as $transactionRow) {
            if (class_exists($namespace = $this->getNamespace($this->transactionElement(), self::BASE_NAMESPACE))) {
                $this->transactions[] = $this->make($namespace, $transactionRow);
            }
        }

        $this->elements[] = $this->transactionElement();

        return $this;
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
     * Validate all elements contained in the ActivityRow.
     * @return array
     */
    protected function validateElements()
    {
        $validities = [];

        foreach ($this->elements() as $element) {
            $this->$element->validate();

            $validities[] = $this->$element->isValid();
        }

        return $validities;
    }

    /**
     * Set the validity for the whole ActivityRow.
     * @param $validities
     */
    protected function validateSelf($validities)
    {
        if (in_array(false, $validities)) {
            $this->isValid = false;
        } else {
            $this->isValid = true;
        }
    }

    /**
     * Make the storage directory, if it does not exist, to store the validated Csv data before import.
     */
    protected function makeDirectoryIfNonExistent()
    {
        if (!file_exists(storage_path(self::CSV_DATA_STORAGE_PATH))) {
            mkdir(storage_path(self::CSV_DATA_STORAGE_PATH), 0777, true);
        }

        return $this;
    }

    /**
     * Get the file path for the validated Csv data to be stored before import.
     * @return string
     */
    protected function getCsvFilepath()
    {
        if ($this->isValid) {
            return storage_path(sprintf('%s/%s', self::CSV_DATA_STORAGE_PATH, self::VALID_CSV_FILE));
        }

        return storage_path(sprintf('%s/%s', self::CSV_DATA_STORAGE_PATH, self::INVALID_CSV_FILE));
    }

    /**
     * Get the data in the current ActivityRow.
     * @return array
     */
    protected function data()
    {
        $this->data = [];

        foreach ($this->elements() as $element) {
            $this->data[snake_case($element)] = ($element === 'identifier')
                ? $this->$element->data()
                : $this->$element->data(snake_case($this->$element->pluckIndex()));
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
            $currentContents[] = $this->data();

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
        file_put_contents($destinationFilePath, json_encode([$this->data()]));
    }
}
