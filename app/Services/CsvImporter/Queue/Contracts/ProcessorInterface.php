<?php namespace App\Services\CsvImporter\Queue\Contracts;


use App\Services\CsvImporter\Queue\Exceptions\HeaderMismatchException;

interface ProcessorInterface
{
    /**
     * Push a CSV file's data for processing into Queue.
     * @param $csv
     */
    public function pushIntoQueue($csv);

    /**
     * Check if the headers are correct according to the provided template.
     * @param $csv
     * @return bool
     * @throws HeaderMismatchException
     */
    public function isCorrectCsv($csv);
}
