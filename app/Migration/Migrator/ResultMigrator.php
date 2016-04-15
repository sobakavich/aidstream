<?php namespace App\Migration\Migrator;

use App\Migration\Entities\ActivityResults;
use App\Models\Activity\ActivityResult as ResultModel;
use App\Migration\Migrator\Contract\MigratorContract;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


/**
 * Class ResultMigrator
 * @package App\Migration\Migrator
 */
class ResultMigrator implements MigratorContract
{
    /**
     * @var ActivityResults
     */
    protected $result;

    /**
     * @var ResultModel
     */
    protected $resultModel;

    /**
     * ResultMigrator constructor.
     * @param ActivityResults $result
     * @param ResultModel     $resultModel
     */
    public function __construct(ActivityResults $result, ResultModel $resultModel)
    {
        $this->result      = $result;
        $this->resultModel = $resultModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(array $accountIds)
    {
        $database = app()->make(DatabaseManager::class);

        $accountsActivities = $this->result->getData($accountIds);
        $builder            = $this->resultModel->query();

        try {
            foreach ($accountsActivities as $accountActivities) {
                foreach ($accountActivities as $activity) {
                    $builder->insert($activity);
                }
            }

            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();

            throw $e;
        }

        return 'Activity Result table migrated.';
    }

    /**
     * {@inheritdoc}
     */
    public function migrateMissingIndicators(array $accountIds)
    {
        $database = app()->make(DatabaseManager::class);

        $accountsActivities = $this->result->getData($accountIds);

        try {
            $allActivities = [];
            foreach ($accountsActivities as $accountActivities) {
                foreach ($accountActivities as $activityId => $activity) {
                    $results = [];
                    foreach ($activity as $result) {
                        $resultData    = json_decode($result['result'], true);
                        $indicatorData = $resultData['indicator'];
                        $indicators    = [];
                        foreach ($indicatorData as $indicator) {
                            $indicators[] = $indicator;
                        }
                        $results[$resultData['title'][0]['narrative'][0]['narrative']] = $indicators;
                    }
                    $allActivities[$activityId] = $results;
                }
            }

            $missedTitles = [];
            foreach ($allActivities as $activityId => $activity) {
                foreach ($activity as $title => $result) {
                    array_pop($result);
                    if ($result) {
                        $builder = $this->resultModel->query();
                        print_r($builder->whereRaw(sprintf("activity_id = $activityId and result #>> '{title,0,narrative,0,narrative}' = '%s'", pg_escape_string($title)))->toSql());
                        $resultData = $builder->whereRaw(sprintf("activity_id = $activityId and result #>> '{title,0,narrative,0,narrative}' = '%s'", pg_escape_string($title)))->first();
                        if ($resultData) {
                            $indicators               = $result;
                            $resultArray              = $resultData->result;
                            $indicators               = array_merge($indicators, $resultArray['indicator']);
                            $resultArray['indicator'] = $indicators;
                            $resultData->result       = $resultArray;
                            $resultData->save();
                        } else {
                            $missedTitles[] = $title;
                        }
                    }
                }
            }
            File::put('missedTitles.html', '<pre>' . implode("<br/>", $missedTitles) . '</pre>');

            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();

            throw $e;
        }

        return 'Missing Activity result indicators migrated.';
    }
}
