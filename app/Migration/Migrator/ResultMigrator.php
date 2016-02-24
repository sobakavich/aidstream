<?php namespace App\Migration\Migrator;

use App\Migration\Entities\ActivityResults;
use App\Models\Activity\ActivityResult as ResultModel;
use App\Migration\Migrator\Contract\MigratorContract;
use Illuminate\Database\DatabaseManager;


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
        $builder = $this->resultModel->query();

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
}
