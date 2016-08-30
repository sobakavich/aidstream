<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;


class Title
{
//[{"narrative":"Elimination of LF programme","language":""}]

    protected $narratives;

    protected $languages;

    protected $template = [['narrative' => '', 'language' => '']];

    protected $data = [];

    private $csvHeader = ['activity_title'];

    public function __construct($fields)
    {
        $this->prepare($fields);
    }

    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values)) {
                foreach ($values as $value) {
                    $this->fillValues($key, $value);
                }
            }
        }
    }

    public function fillValues($key, $value)
    {
        if (array_key_exists($key, array_flip($this->csvHeader))) {
            $this->data[] = $this->setNarrative($value);
        }
    }

    public function setNarrative($value)
    {
        $narrative          = ['narrative' => $value, 'language' => ''];
        $this->narratives[] = $narrative;

        return $narrative;
    }


    public function language()
    {
        return $this->languages;
    }

    public function template()
    {
        return $this->template;
    }

    public function data()
    {
        return $this->data;
    }
}
