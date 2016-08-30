<?php namespace App\Services\CsvImporter\Queue;

use App\Services\CsvImporter\CsvProcessor;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Services\CsvImporter\Queue\Jobs\ImportActivity;
use App\Services\CsvImporter\Queue\Contracts\ProcessorInterface;

/**
 * Class Processor
 * @package App\Services\CsvImporter\Queue
 */
class Processor implements ProcessorInterface
{
    use DispatchesJobs;

    /**
     * @var ImportActivity
     */
    protected $importActivity;

    /**
     * Processor constructor.
     */
    public function __construct()
    {

    }

    /**
     * Push a CSV file's data for processing into Queue.
     * @param $csv
     */
    public function pushIntoQueue($csv)
    {
        $this->dispatch(
            new ImportActivity(new CsvProcessor($csv))
        );
    }
}
