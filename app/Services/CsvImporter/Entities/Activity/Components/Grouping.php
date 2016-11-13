<?php namespace App\Services\CsvImporter\Entities\Activity\Components;

class Grouping
{

    protected $grouped = [];
    protected $fields;
    protected $keys;

    public function __construct(array $fields, array $keys)
    {
        $this->fields = $fields;
        $this->keys   = $keys;
//        dd($fields, $keys);

    }

    /**
     * Group rows into single Activities.
     */
    public function groupValues()
    {
        $index = - 1;
        foreach ($this->fields[$this->keys[0]] as $i => $row) {

            if (!$this->isSameEntity($i)) {
                $index ++;
            }
            $this->setValue($index, $i);
        }
        return $this->grouped;
    }

    /**
     * Check if the next row is new row or not.
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
}