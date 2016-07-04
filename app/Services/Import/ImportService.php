<?php namespace App\Services\Import;

use App\Exceptions\Aidstream\Import\HeaderMisMatchException;
use App\Models\Activity\Activity;
use App\Services\Activity\TransactionManager;
use App\Services\Import\Traits\ProvidesCsvMetaData;
use App\Services\Import\Traits\UploadsFile;
use App\Services\Queue\CsvQueueManager;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Class ImportService
 * @package App\Services\Importer
 */
class ImportService
{
    use UploadsFile, ProvidesCsvMetaData;

    /**
     * @var CsvQueueManager
     */
    protected $csvQueueManager;

    /**
     * @var TransactionManager
     */
    protected $transactionManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Csv Meta data filename.
     */
    const TRANSACTION_CSV_METADATA_FILENAME = "csvMetaData.json";

    /**
     * ImportService constructor.
     * @param CsvQueueManager    $csvQueueManager
     * @param TransactionManager $transactionManager
     * @param LoggerInterface    $logger
     */
    public function __construct(CsvQueueManager $csvQueueManager, TransactionManager $transactionManager, LoggerInterface $logger)
    {
        $this->csvQueueManager    = $csvQueueManager;
        $this->transactionManager = $transactionManager;
        $this->logger             = $logger;
    }

    /**
     * Import Csv data into the database.
     * @param Activity     $activity
     * @param UploadedFile $file
     * @return null
     */
    public function import(Activity $activity, UploadedFile $file)
    {
        try {
            $file = $this->upload(sprintf('%s/%s', config('filesystems.queuedFilePath'), $activity->id), $file);
            $this->csvQueueManager->pushIntoQueue($activity, $file, 'transaction');

            $this->logger->info(
                'Transaction Csv successfully queued for import',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return true;
        } catch (HeaderMisMatchException $exception) {
            return $exception->getMessage();
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Import failed due to %s', $exception->getMessage()),
                [
                    'trace' => $exception->getTraceAsString(),
                    'user'  => auth()->user()->getNameAttribute()
                ]
            );

            return null;
        }
    }

    /**
     * Get the uploaded transaction rows.
     * @param $filename
     * @return array
     */
    public function getUploadedTransactionRows($filename)
    {
        $filePath = sprintf('%s%s', config('filesystems.queuedFileMetaDataPath'), 'csvMetaData.json');

        if (file_exists($filePath)) {
            $contents     = (file_get_contents($filePath));
            $transactions = json_decode($contents, true);

            if (array_key_exists($filename, $transactions)) {
                return $transactions[$filename];
            }

            return [];
        }

        return response('Done');
    }

    /**
     * Save validated transaction rows into the database.
     * @param Activity $activity
     * @param          $transactionDetails
     * @return bool|null
     */
    public function saveValidatedTransactions(Activity $activity, $transactionDetails)
    {
        try {
            $transactions = $this->fetchTransactions($transactionDetails);

            foreach ($transactions as $transaction) {
                $details = ['transaction' => [$transaction]];
                $this->transactionManager->save($details, $activity);
            }

            $this->logger->info(
                'Transactions successfully saved to the database',
                [
                    'byUser' => auth()->user()->getNameAttribute(),
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Transactions could not be saved into the database due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttribute(),
                    'trace'  => $exception->getTraceAsString()
                ]
            );

            return null;
        }
    }

    /**
     * Get the transaction details from the uploaded Csv file. 
     * @param $transactionDetails
     * @return array
     */
    protected function fetchTransactions($transactionDetails)
    {
        $selectedTransactions = $this->selectedTransactions($transactionDetails);
        $csvFilename          = getVal($transactionDetails, ['filename']);
        $filePath             = $this->getMetaDataFilePath();
        $fileContents         = $this->getMetaData($filePath);
        $details              = getVal($fileContents, [$csvFilename], []);

//        $this->updateMetaData();
//        unset($fileContents[$csvFilename]);
//        file_put_contents($filePath, json_encode($fileContents));

        return $this->selectedTransactionData($details, $selectedTransactions);
    }
}
