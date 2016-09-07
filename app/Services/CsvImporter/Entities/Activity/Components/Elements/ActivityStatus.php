<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

/**
 * Class ActivityStatus
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class ActivityStatus
{
    /**
     * @var array
     */
    protected $template = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * CSV Header of Description with their code
     */
    private $_csvHeader = ['activity_status'];

    /**
     * Description constructor.
     * @param $fields
     */
    public function __construct($fields)
    {
        $this->prepare($fields);
    }

    /**
     * Prepare ActivityStatus Element.
     * @param $fields
     */
    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, array_flip($this->_csvHeader))) {
                foreach ($values as $value) {
                    $this->map($value);
                }
            }
        }
    }

    /**
     * Map data from CSV into ActivityStatus data format.
     * @param $value
     */
    public function map($value)
    {
        if (!(is_null($value) || $value == "")) {
            $this->data[] = $value;
        }
    }

    /**
     *
     */
    public function data()
    {
        return $this->data;
    }
}
