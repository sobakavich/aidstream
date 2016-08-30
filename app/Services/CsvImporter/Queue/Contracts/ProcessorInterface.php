<?php namespace App\Services\CsvImporter\Queue\Contracts;


interface ProcessorInterface
{
    /**
     * Push a CSV file's data for processing into Queue.
     * @param $csv
     */
    public function pushIntoQueue($csv);
}
