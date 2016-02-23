<?php namespace App\Migration\Migrator;
use App\Migration\Entities\ActivityPublished;
use App\Models\ActivityPublished as ActivityPublishedModel;
use App\Migration\Migrator\Contract\MigratorContract;

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
     * Migrate data from old system into the new one.
     * @param $accountIds
     * @return string
     */
    public function migrate(array $accountIds)
    {
        $organizationActivityPublished = $this->activityPublished->getData($accountIds);

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
        return 'Activity Publish table migrated';
    }
}