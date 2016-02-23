<?php namespace App\Migration\Migrator;

use App\Migration\Entities\OrganizationPublished;
use App\Models\OrganizationPublished as OrganizationPublishedModel;
use App\Migration\Migrator\Contract\MigratorContract;
use Illuminate\Database\DatabaseManager;

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
     * {@inheritdoc}
     */
    public function migrate(array $accountIds)
    {
        $database = app()->make(DatabaseManager::class);

        $organizationPublished = $this->organizationPublished->getData($accountIds);

        try {
            $database->beginTransaction();

            foreach ($organizationPublished as $org) {
                $this->organizationPublishedModel->query()->insert($org);
            }

//            foreach ($organizationPublished as $activitiesPublished) {
//                foreach ($activitiesPublished as $activityPublished) {
//                    if (!empty($activityPublished)) {
//                        $newActivityPublished = $this->organizationPublishedModel->newInstance($activityPublished);
//
//                        if (!$newActivityPublished->save()) {
//                            return 'Error during OrganizationPublish table migration.';
//                        }
//                    }
//                }
//            }


            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();

            throw $e;
        }

        return 'Organization Publish table migrated.';
    }

}