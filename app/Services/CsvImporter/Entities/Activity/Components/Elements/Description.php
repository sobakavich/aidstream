<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

/**
 * Class Description
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class Description
{
    /**
     * @var array
     */
    protected $narratives = [];

    /**
     * @var
     */
    protected $languages;

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @var array
     */
    protected $template = [['type' => '', 'narrative' => ['narrative' => '', 'language' => '']]];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * CSV Header of Description with their code
     */
    private $_csvHeaders = ['activity_description_general' => 1, 'activity_description_objectives' => 2, 'activity_description_target_groups' => 3, 'activity_description_others' => 4];

    /**
     * Description constructor.
     * @param $fields
     */
    public function __construct($fields)
    {
        $this->prepare($fields);
    }

    /**
     * Prepare Description Element.
     * @param $fields
     */
    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, $this->_csvHeaders)) {
                foreach ($values as $value) {
                    $this->map($key, $value);
                }
            }
        }
    }

    /**
     * Map data from CSV file into Description data format.
     * @param $key
     * @param $value
     */
    public function map($key, $value)
    {
        if (!(is_null($value) || $value == "")) {
            $type                              = $this->setType($key);
            $this->data[$type]['type']         = $type;
            $this->data[$type]['narratives'][] = $this->setNarrative($value);
        }
    }

    /**
     *
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Set type of Description.
     * @param $key
     * @return mixed
     */
    public function setType($key)
    {
        $this->types[] = $key;
        $this->types   = array_unique($this->types);

        return $this->_csvHeaders[$key];
    }

    /**
     * Set narrative of Description.N
     * @param $value
     * @return array
     */
    public function setNarrative($value)
    {
        $narrative          = ['narrative' => $value, 'language' => ''];
        $this->narratives[] = $narrative;

        return $narrative;
    }
}
