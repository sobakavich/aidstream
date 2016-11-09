<?php namespace App\Services\CsvImporter\Entities\Activity\Components;

/**
 * Class Grouping
 * @package App\Services\CsvImporter\Entities\Activity\Components
 */
class Grouping
{
    /**
     * @var array
     */
    protected $grouped = [];

    /**
     * @var array
     */
    protected $fields;

    /**
     * @var array
     */
    protected $keys;

    /**
     * @var int
     */
    protected $periodCount = 1;

    /**
     * @var array
     */
    protected $periodFrequency = [];

    /**
     * Grouping constructor.
     * @param array $fields
     * @param array $keys
     */
    public function __construct(array $fields, array $keys)
    {
        $this->fields = $fields;
        $this->keys   = $keys;
    }

    /**
     * Group rows into single Activities.
     */
    public function groupValues()
    {
        $index = - 1;
        $periodFrequency = 0;
        foreach ($this->fields[$this->keys[0]] as $i => $row) {

            if (!$this->isSameEntity($i)) {
                $index ++;
                if($index > 0){
                    $this->periodFrequency[] = $periodFrequency;
                    $periodFrequency = 0;
                }
            }
            $this->setValue($index, $i);
            $periodFrequency++;
        }
        $this->periodFrequency[] = $periodFrequency;
        $this->periodCount += $index;

        return $this->grouped;
    }

    /**
     * Check if the next row is new row or not.
     * @param $i
     * @return bool
     */
    protected function isSameEntity($i)
    {
        if ((is_null($this->fields[$this->keys[0]][$i]) || $this->fields[$this->keys[0]][$i] == '')
            && (is_null($this->fields[$this->keys[1]][$i]) || $this->fields[$this->keys[1]][$i] == '')
        ) {
            return true;
        }

        return false;
    }

    /**
     * Set the provided value to the provided key/index.
     * @param $index
     */
    protected function setValue($index, $i)
    {
        foreach ($this->fields as $row => $value) {
            if (array_key_exists($row, array_flip($this->keys))) {
                $this->grouped[$index][$row][] = $value[$i];
            }
        }
    }

    /**
     * @return int
     */
    public function periodCount()
    {
        return $this->periodCount;
    }

    /**
     * @return array
     */
    public function periodFrequency()
    {
        return $this->periodFrequency;
    }
}
