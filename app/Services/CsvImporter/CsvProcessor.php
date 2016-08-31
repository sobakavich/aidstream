<?php namespace App\Services\CsvImporter;

use App\Services\CsvImporter\Entities\Activity\Activity;
use App\Services\CsvImporter\Entities\Activity\Components\ActivityRow;

class CsvProcessor
{
    protected $csv;

    protected $data;

    public $model;

    public function __construct($csv)
    {
        $this->csv = $csv;
    }

    public function handle()
    {
        $this->make('App\Services\CsvImporter\Entities\Activity\Activity');

        $this->model->process()
                    ->validate()
                    ->keep();

    }

    protected function make($class)
    {
        if (class_exists($class)) {
            $this->model = app()->make($class, [$this->data]);
        }
    }
}
