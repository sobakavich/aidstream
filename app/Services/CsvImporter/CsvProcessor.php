<?php namespace App\Services\CsvImporter;

use App\Services\CsvImporter\Entities\Activity\Activity;
use App\Services\CsvImporter\Entities\Activity\Components\ActivityRow;

class CsvProcessor
{
    protected $csv;

    protected $activity;

    public function __construct($csv)
    {
        $this->csv      = $csv;
//        $this->activity = new Activity(new ActivityRow($csv));
    }

    public function handle()
    {

    }
}
