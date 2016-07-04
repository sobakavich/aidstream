<?php namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Activity\Activity;
use App\Services\Activity\UploadTransactionManager;
use App\Services\RequestManager\Activity\CsvImportValidator;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Collections\RowCollection;

/**
 * Class ImportTransactionCsv
 * @package App\Jobs\Import
 */
class ImportTransactionCsv extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Activity
     */
    protected $activity;

    /**
     * @var UploadTransactionManager
     */
    protected $transactionManager;

    /**
     * @var CsvImportValidator
     */
    protected $csvImportValidator;

    /**
     * @var
     */
    protected $file;

    /**
     * @var RowCollection
     */
    protected $rowCollection;

    /**
     * Create a new job instance.
     *
     * @param Activity $activity
     * @param          $rowCollection
     * @param          $file
     */
    public function __construct(Activity $activity, $rowCollection, $file)
    {
        $this->activity      = $activity;
        $this->rowCollection = $rowCollection;
        $this->file          = $file;
    }

    /**
     * Execute the job.
     *
     * @param CsvImportValidator $csvImportValidator
     */
    public function handle(CsvImportValidator $csvImportValidator)
    {
        $validator = $csvImportValidator->getTransactionImportValidator()->getDetailedCsvValidator($this->file, $this->rowCollection);
    }
}
