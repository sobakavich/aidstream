<?php namespace App\Services\CsvImporter;

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
     * ImportManager constructor.
     * @param Excel              $excel
     * @param ProcessorInterface $processor
     * @param LoggerInterface    $logger
     * @param SessionManager     $sessionManager
     */
    public function __construct(Excel $excel, ProcessorInterface $processor, LoggerInterface $logger, SessionManager $sessionManager)
    {
        $this->excel          = $excel;
        $this->processor      = $processor;
        $this->logger         = $logger;
        $this->sessionManager = $sessionManager;
    }

    /**
     * Process the uploaded CSV file.
     * @param UploadedFile $file
     */
    public function process(UploadedFile $file)
    {
        try {
            $csv = $this->excel->load($file)->toArray();

//            $csvProcessor = new CsvProcessor($csv);
//            $csvProcessor->handle();

            $this->processor->pushIntoQueue($csv);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    public function createActivity($activities, $contents)
    {
        // TODO: Session managementee
        $activityModel      = app(Activity::class);
        $organizationId     = $this->sessionManager->get('org_id');
        $importedActivities = [];

        foreach ($activities as $key => $activity) {
            $activity                    = $contents[$activity];
            $activity['data']['organization_id'] = $organizationId;
            $importedActivities[$key]    = $activity['data'];

            $activityModel->newInstance($activity['data'])->save();
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
            return storage_path(sprintf('%s/%s', self::CSV_DATA_STORAGE_PATH, self::VALID_CSV_FILE));
        }

        return storage_path(sprintf('%s/%s', self::CSV_DATA_STORAGE_PATH, self::INVALID_CSV_FILE));
    }
}
