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
     * Activity Elements for an Activity Row.
     * @var array
     */
    protected $activityElements = ['identifier', 'title', 'description', 'activityStatus', 'activityDate', 'participatingOrganisation', 'recipientCountry', 'recipientRegion', 'sector'];

    /**
     * Transaction Elements for an Activity Row.
     * @var string
     */
    protected $transactionElement = 'transaction';

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

    protected $participatingOrganisation;

    protected $recipientCountry;

    protected $recipientRegion;

    protected $sector;

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
        foreach ($this->elements() as $element) {
            $this->$element->validate();
        }

        return $this;
    }

    /**
     * Store the Row in a temporary JSON File for further usage.
     */
    public function keep()
    {
        // TODO: Implement keep() method.
        // TODO: Write validated activity into JSON.
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
        // TODO: Map transaction data.

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
}
