<?php namespace App\Services\Queue;

use App\Exceptions\Aidstream\Import\HeaderMisMatchException;
use App\Jobs\ImportTransactionCsv;
use App\Models\Activity\Activity;
use App\Services\Activity\UploadTransactionManager;
use App\Services\Queue\Processor\QueueProcessor;
use App\Services\RequestManager\Activity\CsvImportValidator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Queue;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class CsvQueueManager
 * @package App\Services\Queue
 */
class CsvQueueManager
{
    use DispatchesJobs;

    /**
     * @var Excel
     */
    protected $excel;

    /**
     * @var QueueProcessor
     */
    protected $queueProcessor;

    /**
     * @var UploadTransactionManager
     */
    protected $transactionUploadManager;

    /**
     * CSV type.
     */
    const TRANSACTION_CSV = 'transaction';

    /**
     * CsvQueueManager constructor.
     * @param Excel                    $excel
     * @param QueueProcessor           $queueProcessor
     * @param UploadTransactionManager $transactionUploadManager
     */
    public function __construct(Excel $excel, QueueProcessor $queueProcessor, UploadTransactionManager $transactionUploadManager)
    {
        $this->excel                    = $excel;
        $this->queueProcessor           = $queueProcessor;
        $this->transactionUploadManager = $transactionUploadManager;
    }

    /**
     * Push the job into queue.
     * @param Activity $activity
     * @param File     $file
     * @param          $fileType
     * @throws HeaderMisMatchException
     */
    public function pushIntoQueue(Activity $activity, File $file, $fileType)
    {
        try {
            $filePath = sprintf('%s%s/%s', config('filesystems.queuedFilePath'), $activity->id, $file->getFilename());
            $filename = $file->getFilename();
            $rows     = $this->excel->load($filePath)->get();
            $version  = session('version');
            import($activity, $rows, $filePath, $filename, $version);

            if ($fileType == self::TRANSACTION_CSV) {
                Queue::push(
                    function ($job) use ($activity, $rows, $filePath, $filename, $version) {
                        import($activity, $rows, $filePath, $filename, $version);

                        $job->delete();
                    }
                );
            }

            $csvImportValidator    = $this->getCsvImportValidator();
//            $transactionRepository = $this->transactionRepository();
//            $validator             = $this->validatorByCsvType($file, $rows, $csvImportValidator);

//            $this->dispatch(new ImportTransactionCsv($activity, $rows, $file));
        } catch (HeaderMisMatchException $exception) {
            throw $exception;
        }
    }

    /**
     * Returns a CsvImportValidator Instance.
     * @return mixed
     */
    protected function getCsvImportValidator()
    {
        return app()->make(CsvImportValidator::class);
    }

    /**
     * Returns an UploadTransaction instance according to the current IATI Version.
     * @return mixed
     */
    protected function transactionRepository()
    {
        return app()->make(sprintf('%s%s\Repositories\Activity\UploadTransaction', "App\\Core\\", session('version')));
    }

    /**
     * Returns a Validator for the current Csv Type.
     * @param                    $file
     * @param                    $data
     * @param CsvImportValidator $csvImportValidator
     * @return mixed
     * @throws HeaderMisMatchException
     */
    protected function validatorByCsvType($file, $data, CsvImportValidator $csvImportValidator)
    {
        try {
            if ($this->transactionUploadManager->isSimpleCsv($file)) {
                return $csvImportValidator->getTransactionImportValidator()->getSimpleCsvValidator($file, $data);
            }

            return $csvImportValidator->getTransactionImportValidator()->getDetailedCsvValidator($file, $data);
        } catch (HeaderMisMatchException $exception) {
            throw $exception;
        }
    }
}
