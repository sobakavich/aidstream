<?php namespace App\Services\CsvImporter\Queue\Jobs;

use App\Jobs\Job;
use App\Services\CsvImporter\CsvProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportActivity extends Job implements ShouldQueue
{
    /**
     * @var CsvProcessor
     */
    protected $csvProcessor;

    /**
     * Create a new job instance.
     *
     * @param CsvProcessor $csvProcessor
     */
    public function __construct(CsvProcessor $csvProcessor)
    {
        $this->csvProcessor = $csvProcessor;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->csvProcessor->handle();

    }
}
