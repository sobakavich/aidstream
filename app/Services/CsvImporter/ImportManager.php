<?php namespace App\Services\CsvImporter;

use App\Core\V201\Repositories\Activity\ActivityRepository;
use App\Core\V201\Repositories\Activity\Transaction;
use App\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Models\Activity\Activity;
use Exception;
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
     * ImportManager constructor.
     * @param Excel                  $excel
     * @param ProcessorInterface     $processor
     * @param LoggerInterface        $logger
     * @param SessionManager         $sessionManager
     * @param ActivityRepository     $activityRepo
     * @param OrganizationRepository $organizationRepo
     * @param Transaction            $transactionRepo
     */
    public function __construct(
        Excel $excel,
        ProcessorInterface $processor,
        LoggerInterface $logger,
        SessionManager $sessionManager,
        ActivityRepository $activityRepo,
        OrganizationRepository $organizationRepo,
        Transaction $transactionRepo
    ) {
        $this->excel            = $excel;
        $this->processor        = $processor;
        $this->logger           = $logger;
        $this->sessionManager   = $sessionManager;
        $this->activityRepo     = $activityRepo;
        $this->organizationRepo = $organizationRepo;
        $this->transactionRepo  = $transactionRepo;
    }

    /**
     * Process the uploaded CSV file.
     * @param UploadedFile $file
     * @return bool|null
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

            return $exception->getMessage();
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

    public function startImport()
    {
        $this->sessionManager->put(['import-status' => 'importing']);
    }

    /**
     * Get the filepath to the file in which the Csv data is written after processing for import.
     * @param $isValid
     * @return string
     */
    public function getFilePath($isValid)
    {
        if ($isValid) {
            return storage_path(sprintf('%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), self::VALID_CSV_FILE));
        }

        return storage_path(sprintf('%s/%s/%s', self::CSV_DATA_STORAGE_PATH, session('org_id'), self::INVALID_CSV_FILE));
    }
}
