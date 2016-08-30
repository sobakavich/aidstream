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
     * @param $fields
     */
    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values)) {
                foreach ($values as $value) {
                    if (array_key_exists($key, array_flip($this->_csvHeader))) {
                        $this->fillValues($value);
                    }
                }
            }
        }
    }

    public function fillValues($value)
    {
        $this->data[] = $value;
    }

    /**
     *
     */
    public function data()
    {
        $this->data = json_encode($this->data);
    }
}
