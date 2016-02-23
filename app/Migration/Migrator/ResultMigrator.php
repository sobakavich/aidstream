<?php namespace App\Migration\Migrator;

use App\Migration\Entities\ActivityResults;
use App\Models\Activity\ActivityResult as ResultModel;
use App\Migration\Migrator\Contract\MigratorContract;


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
        $accountsActivities = $this->result->getData($accountIds);

        foreach ($accountsActivities as $accountActivities) {
            foreach ($accountActivities as $activity) {
                foreach ($activity as $resultData) {
                    $result = $this->resultModel->newInstance($resultData);

                    if (!$result->save()) {
                        return 'Error during Activity Result table migration.';
                    }
                }
            }
        }

        return 'Activity Result table migrated.';
    }
}
