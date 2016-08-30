<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use Exception;

class Identifier
{
    /**
     * @var array
     */
    protected $template = [['activity_identifier' => '', 'iati_identifier_text' => '']];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * CSV Header of Description with their code
     */
    private $_csvHeader = ['activity_identifier'];

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
        try {
            foreach ($fields as $key => $values) {
                if (!is_null($values) || is_array($values)) {
                    foreach ($values as $value) {
                        if (array_key_exists($key, array_flip($this->_csvHeader))) {
                            $this->fillValue($value);
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    public function fillValue($value)
    {
        $this->data['activity_identifier']  = $value;
        $this->data['iati_identifier_text'] = '';
    }

    /**
     *
     */
    public function data()
    {
        $this->data = json_encode($this->data);
    }
}