<?php namespace App\Services\Activity;

use App\Core\V201\Parser\Result;
use App\Core\Version;
use App\Services\Organization\OrganizationManager;
use Exception;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\DatabaseManager;

/**
 * Class ImportResult
 * @package App\Services\Activity
 */
class ImportResult
{
    /**
     * @var Result/false
     */
    protected $template;
    /**
     * @var Version
     */
    protected $version;
    /**
     * @var Log
     */
    protected $logger;
    /*
     * @var array
     * */
    protected $importedResults;
    /**
     * @var Result
     */
    protected $resultParser;
    /**
     * @var OrganizationManager
     */
    protected $orgManager;

    /**
     * @param Version             $version
     * @param Log                 $logger
     * @param OrganizationManager $orgManager
     */
    public function __construct(Version $version, Log $logger, OrganizationManager $orgManager)
    {
        $this->resultParser = $version->getActivityElement()->getResultParser();
        $this->version      = $version;
        $this->logger       = $logger;
        $this->orgManager   = $orgManager;
    }

    /**
     * return result rows from csv with errors from parser of respective template
     * @param $csvFile
     * @return array
     */
    public function getResults($csvFile)
    {
        if (!isset($csvFile)) {
            return session('results');
        }
        $csvData = $this->getCsvData($csvFile);

        if (!$csvData->get()->count()) {
            return [];
        }
        $firstData = $csvData->toArray()[0];
        $this->setTemplate($firstData);

        if ($this->template) {
            $results = $this->template->getVerifiedResults($csvData);
            session()->put('results', $results);

            return $results;
        }

        return false;
    }

    /**
     * return csv data
     * @param $csvFile
     * @return \Maatwebsite\Excel\Readers\LaravelExcelReader
     */
    protected function getCsvData($csvFile)
    {
        return $this->version->getExcel()->load($csvFile);
    }

    /**
     * set respective template
     * @param array $firstData
     */
    protected function setTemplate(array $firstData)
    {
        $this->template ?: $this->template = $this->resultParser->getTemplate($firstData);
    }

    /**
     * import selected results
     * @param array $results
     * @return bool
     */
    public function importResults(array $results)
    {
        $database = app(DatabaseManager::class);
        try {
            $database->beginTransaction();
            $organization = $this->orgManager->getOrganization(session('org_id'));
            $this->template ?: $this->setTemplate(json_decode($results[0], true));
            $this->importedResults = $this->template->save($results);
            $database->commit();

            $this->logger->activity(
                "activity.result_uploaded",
                [
                    'organization'    => $organization->name,
                    'organization_id' => $organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $database->rollback();
            $this->logger->error($exception, ['results' => $results]);
        }

        return false;
    }

    /**
     * return imported result links
     * @return array|string
     */
    public function getImportedResults()
    {
        $resultLinks = [];
        foreach ($this->importedResults as $result) {
            $resultLinks[] = sprintf(
                '<a href="%s" target="_blank">%s</a>',
                route('result.show', [$result->id]),
                getVal($result->title, [0, 'narrative', 0, 'narrative'], 'No Title')
            );
        }

        return $resultLinks;
    }
}
