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
     * @var
     */
    protected $organizationId;

    /**
     * Create a new job instance.
     *
     * @param CsvProcessor $csvProcessor
     */
    public function __construct(CsvProcessor $csvProcessor)
    {
        $this->csvProcessor   = $csvProcessor;
        $this->organizationId = session('org_id');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->csvProcessor->handle($this->organizationId);
        file_put_contents(storage_path(sprintf('%s/%s/%s', 'csvImporter/tmp/', $this->organizationId, 'status.json')), json_encode(['status' => 'Complete']));

        $this->delete();
    }
}
