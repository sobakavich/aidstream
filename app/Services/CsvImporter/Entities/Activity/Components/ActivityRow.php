<?php namespace App\Services\CsvImporter\Entities\Activity\Components;

use App\Services\CsvImporter\Entities\Row;

/**
 * Class ActivityRow
 * @package App\Services\CsvImporter\Entities\Activity\Components
 */
class ActivityRow extends Row
{
    /**
     * Elements for an Activity Row.
     * @var array
     */
    protected $elements = ['identifier', 'title', 'description', 'activityStatus', 'activityDate', 'participatingOrganisation', 'recipientCountry', 'recipientRegion', 'sector'];

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


    /**
     * Base Namespace for the Element classes.
     */
    const BASE_NAMESPACE = 'App\Services\CsvImporter\Entities\Activity\Components\Elements';

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
        foreach ($this->elements as $element) {
            $this->$element = $this->make($element, $this->fields(), self::BASE_NAMESPACE);
        }
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
}
