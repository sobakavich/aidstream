<?php namespace App\Migration\Migrator;
use App\Migration\Entities\ActivityPublished;
use App\Models\ActivityPublished as ActivityPublishedModel;
use App\Migration\Migrator\Contract\MigratorContract;
use Illuminate\Database\DatabaseManager;

class ActivityPublishedMigrator implements MigratorContract
{
    /**
     * @var ActivityPublished
     */
    protected $activityPublished;

    /**
     * @var ActivityPublishedModel
     */
    protected $activityPublishedModel;

    /**
     * ActivityPublishedMigrator constructor.
     * @param ActivityPublished    $activityPublished
     * @param  ActivityPublishedModel $activityPublishedModel
     */
    public function __construct(ActivityPublished $activityPublished, ActivityPublishedModel $activityPublishedModel)
    {
        $this->activityPublished      = $activityPublished;
        $this->activityPublishedModel = $activityPublishedModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(array $accountIds)
    {
        $database = app()->make(DatabaseManager::class);

        $organizationActivityPublished = $this->activityPublished->getData($accountIds);

        try {
            foreach ($organizationActivityPublished as $activitiesPublished) {
                foreach ($activitiesPublished as $activityPublished) {
                    if (!empty($activityPublished)) {
                        $newActivityPublished = $this->activityPublishedModel->newInstance($activityPublished);

                        if (!$newActivityPublished->save()) {
                            return 'Error during ActivityPublish table migration.';
                        }
                    }
                }
            }

            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();

            throw $e;
        }

        return 'Activity Publish table migrated';
    }
}