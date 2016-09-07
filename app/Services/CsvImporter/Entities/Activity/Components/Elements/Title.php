<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;


/**
 * Class Title
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class Title
{
    /**
     * @var
     */
    protected $narratives;

    /**
     * @var
     */
    protected $languages;

    /**
     * @var array
     */
    protected $template = [['narrative' => '', 'language' => '']];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    private $csvHeader = ['activity_title'];

    /**
     * Title constructor.
     * @param $fields
     */
    public function __construct($fields)
    {
        $this->prepare($fields);
    }

    /**
     * Prepare Title Element.
     * @param $fields
     */
    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, array_flip($this->csvHeader))) {
                foreach ($values as $value) {
                    $this->map($value);
                }
            }
        }
    }

    /**
     * Map data from CSV file into Title data format.
     * @param $value
     */
    public function map($value)
    {
        if (!(is_null($value) || $value == "")) {
            $this->data[] = $this->setNarrative($value);
        }
    }

    /**
     * Set Narrative of Title.
     * @param $value
     * @return array
     */
    public function setNarrative($value)
    {
        $narrative          = ['narrative' => $value, 'language' => ''];
        $this->narratives[] = $narrative;

        return $narrative;
    }


    /**
     * Set Language of Title.
     * @return mixed
     */
    public function language()
    {
        return $this->languages;
    }

    /**
     * Set Template of Title.
     * @return array
     */
    public function template()
    {
        return $this->template;
    }

    /**
     * @return array
     */
    public function data()
    {
        return $this->data;
    }
}
