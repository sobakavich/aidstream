<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use ClassesWithParents\E;

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
    const CSV_HEADER = ['activity_identifier'];

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
                    if (array_key_exists($key, array_flip(self::CSV_HEADER))) {
                        $this->fillValue($value);
                    }
                }
            }
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