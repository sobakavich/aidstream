<?php namespace App\Services\CsvImporter;

use App\Core\V201\Repositories\Activity\ActivityRepository;
use App\Core\V201\Repositories\Activity\Transaction;
use App\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Models\Activity\Activity;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Session\SessionManager;
use Maatwebsite\Excel\Excel;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Services\CsvImporter\Queue\Contracts\ProcessorInterface;

/**
 * Class ImportManager
 * @package App\Services\CsvImporter
 */
class ImportManager
{
    /**
     * Directory where the validated Csv data is written before import.
     */
    const CSV_DATA_STORAGE_PATH = 'csvImporter/tmp';

    /**
     * File in which the valida Csv data is written before import.
     */
    const VALID_CSV_FILE = 'valid.json';

    /**
     * File in which the invalid Csv data is written before import.
     */
    const INVALID_CSV_FILE = 'invalid.json';

    /**
     * @var Excel
     */
    protected $excel;

    /**
     * @var ProcessorInterface
     */
    protected $processor;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var SessionManager
     */
    protected $sessionManager;
    /**
     * @var ActivityRepository
     */
    protected $activityRepo;
    /**
     * @var OrganizationRepository
     */
    protected $organizationRepo;
    /**
     * @var Transaction
     */
    protected $transactionRepo;

    /**
     * Current User's id.
     * @var
     */
    protected $userId;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * File names for the invalid activities.
     * @var array
     */
    protected $invalidActivityFileNames = ['invalid.json', 'invalid-temp.json'];

    /**
     * ImportManager constructor.
     * @param Excel                  $excel
     * @param ProcessorInterface     $processor
     * @param LoggerInterface        $logger
     * @param SessionManager         $sessionManager
     * @param ActivityRepository     $activityRepo
     * @param OrganizationRepository $organizationRepo
     * @param Transaction            $transactionRepo
     * @param Filesystem             $filesystem
     */
    public function __construct(
        Excel $excel,
        ProcessorInterface $processor,
        LoggerInterface $logger,
        SessionManager $sessionManager,
        ActivityRepository $activityRepo,
        OrganizationRepository $organizationRepo,
        Transaction $transactionRepo,
        Filesystem $filesystem
    ) {
        $this->excel            = $excel;
        $this->processor        = $processor;
        $this->logger           = $logger;
        $this->sessionManager   = $sessionManager;
        $this->activityRepo     = $activityRepo;
        $this->organizationRepo = $organizationRepo;
        $this->transactionRepo  = $transactionRepo;
        $this->userId           = $this->getUserId();
        $this->filesystem       = $filesystem;
    }

    /**
     * Process the uploaded CSV file.
     * @param UploadedFile $file
     * @return null
     */
    public function process(UploadedFile $file)
    {
        try {
            $csv = $this->excel->load($file)->toArray();

            $this->processor->pushIntoQueue($csv);
        } catch (Exception $exception) {
            $this->logger->error(
                $exception->getMessage(),
                [
                    'user'  => auth()->user()->getNameAttribute(),
                    'trace' => $exception->getTraceAsString()
                ]
            );

            return null;
        }
    }

    /**
     * Create Valid activities.
     * @param $activities
     * @param $contents
     */
    public function createActivity($activities, $contents)
    {
        $organizationId         = $this->sessionManager->get('org_id');
        $importedActivities     = [];
        $organizationIdentifier = getVal(
            $this->organizationRepo->getOrganization($organizationId)->toArray(),
            ['reporting_org', 0, 'reporting_organization_identifier']
        );

        foreach ($activities as $key => $activity) {
            $activity                                               = $contents[$activity];
            $activity['data']['organization_id']                    = $organizationId;
            $importedActivities[$key]                               = $activity['data'];
            $iati_identifier_text                                   = $organizationIdentifier . '-' . $activity['data']['identifier']['activity_identifier'];
            $activity['data']['identifier']['iati_identifier_text'] = $iati_identifier_text;

            $createdActivity = $this->activityRepo->createActivity($activity['data']);

            if (array_key_exists('transaction', $activity['data'])) {
                $this->createTransaction(getVal($activity['data'], ['transaction'], []), $createdActivity->id);
            }
        }
        $this->activityImportStatus($activities);
    }

    /**
     * Create Transaction of Valid Activities
     * @param $transactions
     * @param $activityId
     */
    public function createTransaction($transactions, $activityId)
    {
        foreach ($transactions as $transaction) {
            $this->transactionRepo->createTransaction($transaction, $activityId);
        }
    }

    /**
     * Check the status of the csv activities being imported.
     * @param $activities
     */
    protected function activityImportStatus($activities)
    {
        if (session('importing') && $this->checkStatusFile()) {
            $this->removeImportedActivity($activities);
        }

        if ($this->checkStatusFile() && is_null(session('importing'))) {
            $this->removeImportDirectory();
        }
    }

    /**
     * Remove the imported activity if the csv is still being processed.
     * @param $checkedActivities
     */
    protected function removeImportedActivity($checkedActivities)
    {
        $validActivities = json_decode(file_get_contents($this->getFilePath(true)), true);
        foreach ($checkedActivities as $key => $activity) {
            unset($validActivities[$key]);
        }

        json_encode(file_put_contents($this->getFilePath(true), $validActivities));
    }

    /**
     * Check if the status.json file is present.
     * @return bool
     */
    protected function checkStatusFile()
    {
        return file_exists(storage_path(sprintf('%s/%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), $this->userId, 'status.json')));
    }

    /**
     * Remove the user folder containing valid, invalid and status json.
     */
    protected function removeImportDirectory()
    {
        $dir = storage_path(sprintf('%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), $this->userId));
        $this->filesystem->deleteDirectory($dir);

    }

    /**
     * Set the key to specify that import process has started for the current User.
     */
    public function startImport()
    {
        $this->sessionManager->put(['import-status' => 'importing']);
    }

    /**
     * Remove the import-status key from the User's current session.
     */
    public function endImport()
    {
        $this->sessionManager->forget('import-status');
    }

    /**
     * Get the filepath to the file in which the Csv data is written after processing for import.
     * @param bool $isValid
     * @return string
     */
    public function getFilePath($isValid)
    {
        if ($isValid) {
            return storage_path(sprintf('%s/%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), $this->userId, self::VALID_CSV_FILE));
        }

        return storage_path(sprintf('%s/%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), $this->userId, self::INVALID_CSV_FILE));
    }

    /**
     * Check if the headers in the uploaded Csv file are as per the provided template.
     * @param $file
     * @return bool|string
     */
    public function verifyHeaders($file)
    {
        try {
            $csv = $this->excel->load($file)->toArray();

            if ($this->processor->isCorrectCsv($csv)) {
                return true;
            }
        } catch (Exception $exception) {
            $this->logger->error(
                $exception->getMessage(),
                [
                    'user'  => auth()->user()->getNameAttribute(),
                    'trace' => $exception->getTraceAsString()
                ]
            );

            return $exception->getMessage();
        }
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

    /**
     * Clear all invalid activities.
     * @return bool|null
     */
    public function clearInvalidActivities()
    {
        try {
            list($file, $temporaryFile) = [$this->getFilePath(false), $this->getTemporaryFilepath('invalid-temp.json')];

            if (file_exists($file)) {
                unlink($file);
            }

            if (file_exists($temporaryFile)) {
                unlink($temporaryFile);
            }

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error clearing invalid Activities due to [%s]', $exception->getMessage()),
                [
                    'trace'           => $exception->getTraceAsString(),
                    'user_id'         => $this->userId,
                    'organization_id' => session('org_id')
                ]
            );

            return null;
        }
    }

    /**
     * Get the filepath for the temporary files used by the import process.
     * @param $filename
     * @return string
     */
    public function getTemporaryFilepath($filename)
    {
        return storage_path(sprintf('%s/%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), $this->userId, $filename));
    }
}
