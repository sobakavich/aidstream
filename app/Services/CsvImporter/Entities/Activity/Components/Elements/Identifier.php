<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

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
     * Prepare Identifier Element.
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
     * Map data from CSV file into Identifier data format.
     * @param $value
     */
    public function map($value)
    {
        if (!is_null($value)) {
            $this->data['activity_identifier']  = $value;
            $this->data['iati_identifier_text'] = '';
        }
    }

    /**
     *
     */
    public function data()
    {
        $this->data = json_encode($this->data);
    }
}