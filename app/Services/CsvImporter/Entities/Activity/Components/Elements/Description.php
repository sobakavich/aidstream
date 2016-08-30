<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;


use ClassesWithParents\E;

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
    private $_csvHeader = ['activity_description_general' => 1, 'activity_description_objectives' => 2, 'activity_description_target_groups' => 3, 'activity_description_others' => 4];

    /**
     * Description constructor.
     * @param $fields
     */
    public function __construct($fields)
    {
//        if (array_key_exists(self::CSV_HEADER, $fields)) {
//            $this->narratives = $fields[self::CSV_HEADER];
        $this->prepare($fields);
//        $this->formatData();;
    }

    /**
     * @param $fields
     */
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
        if (array_key_exists($key, $this->_csvHeader)) {
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
     * @param $key
     * @return mixed
     */
    public function setType($key)
    {
        $this->types[] = $key;
        $this->types   = array_unique($this->types);

        return $this->_csvHeader[$key];
    }

    /**
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
