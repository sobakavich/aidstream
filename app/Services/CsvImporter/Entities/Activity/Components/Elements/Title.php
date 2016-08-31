<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;


class Title
{
//[{"narrative":"Elimination of LF programme","language":""}]

    protected $narratives;

    protected $languages;

    protected $template = [['narrative' => '', 'language' => '']];

    protected $data = [];

    const CSV_HEADER = 'activity_title';

    public function __construct($fields)
    {
        if (array_key_exists(self::CSV_HEADER, $fields)) {
            $this->narratives = $fields[self::CSV_HEADER];
        }
    }

    public function prepare()
    {
        foreach ($this->narratives() as $narrative) {
            $this->data[] = $this->fillValues($narrative);
        }

        return $this;
    }

    public function narratives()
    {
        return $this->narratives;
    }

    public function language()
    {
        return $this->languages;
    }

    public function template()
    {
        return $this->template;
    }

    protected function fillValues($narrative)
    {
//        $data = $this->template();

//        $data['narrative'] = $narrative;

        return ['narrative' => $narrative, 'language' => ''];
    }

    public function data()
    {
        return json_encode($this->data);
    }
}
