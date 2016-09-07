<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;


/**
 * Class RecipientCountry
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class RecipientCountry
{
    /**
     * @var array
     */
    protected $countries = [];

    /**
     * @var array
     */
    protected $percentage = [];

    /**
     * @var array
     */
    protected $template = [['country_code' => '', 'percentage' => '', 'narrative' => ['narrative' => '', 'language' => '']]];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * CSV Header of Description with their code
     */
    private $_csvHeaders = ['recipient_country_code', 'recipient_country_percentage'];

    /**
     * Description constructor.
     * @param $fields
     */
    public function __construct($fields)
    {
        $this->prepare($fields);
    }

    /**
     * Prepare RecipientCountry Element.
     * @param $fields
     */
    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, array_flip($this->_csvHeaders))) {
                foreach ($values as $index => $value) {
                    $this->map($key, $value, $index);
                }
            }
        }
    }

    /**
     * Map data from CSV file into RecipientCountry data format.
     * @param $key
     * @param $value
     * @param $index
     */
    public function map($key, $value, $index)
    {
        if (!(is_null($value) || $value == "")) {
            $this->setCountry($key, $value, $index);
            $this->setPercentage($key, $value, $index);
            $this->setNarrative($index);
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
     * Set Country for RecipientCountry.
     * @param $key
     * @param $value
     * @param $index
     * @return mixed
     * @internal param $key
     *
     */
    protected function setCountry($key, $value, $index)
    {
        if (!isset($this->data[$index]['country_code'])) {
            $this->data[$index]['country_code'] = '';
        }

        if ($key == $this->_csvHeaders[0] && (!is_null($value))) {
            $this->countries[] = $value;
            $this->countries   = array_unique($this->countries);

            $this->data[$index]['country_code'] = $value;
        }
    }

    /**
     * Set Percentage for RecipientCountry Element.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setPercentage($key, $value, $index)
    {
        if (!isset($this->data[$index]['percentage'])) {
            $this->data[$index]['percentage'] = '';
        }

        if ($key == $this->_csvHeaders[1] && (!is_null($value))) {
            $this->percentage[] = $value;

            $this->data[$index]['percentage'] = $value;
        }
    }

    /**
     * Set Narrative for RecipientCountry Element.
     * @param $index
     * @return array
     */
    protected function setNarrative($index)
    {
        $narrative = ['narrative' => '', 'language' => ''];

        $this->data[$index]['narrative'] = $narrative;
    }
}