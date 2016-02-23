<?php namespace App\Migration\Migrator;

use App\Migration\Entities\OrganizationPublished;
use App\Models\OrganizationPublished as OrganizationPublishedModel;
use App\Migration\Migrator\Contract\MigratorContract;

class OrganizationPublishedMigrator implements MigratorContract
{

    /**
     * @var OrganizationPublished
     */
    protected $organizationPublished;

    /**
     * @var OrganizationPublishedModel
     */
    protected $organizationPublishedModel;

    /**
     * OrganizationPublishedMigrator constructor.
     * @param OrganizationPublished    $organizationPublished
     * @param  OrganizationPublishedModel $organizationPublishedModel
     */
    public function __construct(OrganizationPublished $organizationPublished, OrganizationPublishedModel $organizationPublishedModel)
    {
        $this->organizationPublished      = $organizationPublished;
        $this->organizationPublishedModel = $organizationPublishedModel;
    }

    /**
     * Migrate data from old system into the new one.
     * @param $accountIds
     * @return string
     */
    public function migrate(array $accountIds)
    {
        $organizationPublished = $this->organizationPublished->getData($accountIds);

        foreach ($organizationPublished as $activitiesPublished) {
            foreach ($activitiesPublished as $activityPublished) {
                if (!empty($activityPublished)) {
                    $newActivityPublished = $this->organizationPublishedModel->newInstance($activityPublished);

                    if (!$newActivityPublished->save()) {
                        return 'Error during OrganizationPublish table migration.';
                    }
                }
            }
        }
        return 'Organization Publish table migrated.';
    }

}