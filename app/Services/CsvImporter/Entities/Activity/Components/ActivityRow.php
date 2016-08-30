<?php namespace App\Services\CsvImporter\Entities\Activity\Components;

use App\Services\CsvImporter\Entities\Row;

class ActivityRow extends Row
{
    protected $elements = ['identifier', 'title', 'description', 'activityStatus', 'activityDate'];

    protected $identifier;

    protected $title;

    protected $description;

    protected $activityStatus;

    protected $activityDate;

    const BASE_NAMESPACE = 'App\Services\CsvImporter\Entities\Activity\Components\Elements';

    public function __construct($data)
    {
        $this->fields = $data;
        $this->init();
    }

    public function init()
    {
        foreach ($this->elements as $element) {
            $this->$element = $this->make($element, $this->fields());
        }
    }

    protected function make($element, $fields)
    {
        if (class_exists($class = sprintf('%s\%s', self::BASE_NAMESPACE, ucfirst($element)))) {
            return app()->make($class, [$fields]);
        }
    }

    public function process()
    {
        foreach ($this->elements as $element) {
            $this->$element->prepare($element);
        }

        // TODO: Implement process() method.

        return $this;
    }

    public function validate()
    {
        // TODO: Validate each row.

        return $this;
    }

    public function keep()
    {
        // TODO: Implement keep() method.
        // TODO: Write validated activity into JSON.
    }
}
