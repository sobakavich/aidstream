<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;


class ActivityDate
{

    protected $template = ['type' => '', 'date' => '', 'narrative' => ['narrative' => '', 'language' => '']];

    protected $data = [];

    protected $types = [];

    protected $narratives;

    protected $dates;

    private $_csvHeader = ['actual_start_date' => 2, 'actual_end_date' => 4, 'planned_start_date' => 1, 'planned_end_date' => 3];

    public function __construct($fields)
    {
        $this->prepare($fields);
    }

    public function prepare($fields)
    {
        $index = 0;
        foreach ($fields as $key => $values) {
            if (!is_null($values)) {
                foreach ($values as $value) {
                    $this->fillValues($key, $value, $index);
                    $index ++;
                }
            }
        }
    }

    public function fillValues($key, $value, $index)
    {
        if (array_key_exists($key, $this->_csvHeader)) {
            $type                             = $this->setType($key);
            $this->data[$index]['date']       = $this->setDate($value);
            $this->data[$index]['type']       = $type;
            $this->data[$index]['narratives'] = $this->setNarrative($value);
        }
    }

    public function setType($key)
    {
        $this->types[] = $key;
        $this->types   = array_unique($this->types);

        return $this->_csvHeader[$key];
    }

    public function setDate($value)
    {
        $this->dates[] = $value;

        return $value;
    }

    public function setNarrative($value)
    {
        $narrative          = ['narrative' => '', 'language' => ''];
        $this->narratives[] = $narrative;

        return $narrative;
    }

    public function data()
    {
        return $this->data;
    }
}