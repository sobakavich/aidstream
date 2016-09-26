<?php namespace App\Services\CsvImporter\Queue\Jobs;

use App\Jobs\Job;
use App\Services\CsvImporter\CsvProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ImportActivity
 * @package App\Services\CsvImporter\Queue\Jobs
 */
class ImportActivity extends Job implements ShouldQueue
{
    /**
     * @var CsvProcessor
     */
    protected $csvProcessor;

    /**
     * Current Organization's Id.
     * @var
     */
    protected $organizationId;

    /**
     * Current User's Id.
     * @var
     */
    protected $userId;

    /**
     * Create a new job instance.
     *
     * @param CsvProcessor $csvProcessor
     */
    public function __construct(CsvProcessor $csvProcessor)
    {
        $this->csvProcessor   = $csvProcessor;
        $this->organizationId = session('org_id');
        $this->userId         = $this->getUserId();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->csvProcessor->handle($this->organizationId, $this->userId);
        file_put_contents(storage_path(sprintf('%s/%s/%s/%s', 'csvImporter/tmp/', $this->organizationId, $this->userId, 'status.json')), json_encode(['status' => 'Complete']));

        $this->delete();
    }

    /**
     * Get the current User's id.
     * @return mixed
     */
    protected function getUserId()
    {
        if (auth()->check()) {
            return auth()->user()->id;
        }
    }
}
