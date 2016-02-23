<?php namespace App\Migration\Migrator;

use App\Migration\Entities\OrganizationData;
use App\Models\Organization\OrganizationData as OrganizationDataModel;
use App\Migration\Migrator\Contract\MigratorContract;
use Illuminate\Database\DatabaseManager;

class OrganizationDataMigrator implements MigratorContract
{
    /**
     * @var OrganizationData
     */
    protected $organization;

    /**
     * @var OrganizationDataModel
     */
    protected $organizationDataModel;

    public function __construct(OrganizationData $organization, OrganizationDataModel $organizationDataModel)
    {
        $this->organization          = $organization;
        $this->organizationDataModel = $organizationDataModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(array $accountIds)
    {
        $database = app()->make(DatabaseManager::class);

        $organizationDataDetails = $this->organization->getData($accountIds);

        try {
            foreach ($organizationDataDetails as $organizationDetail) {
                $this->organizationDataModel->query()->insert($organizationDetail);
//                foreach ($organizationDetail as $detail) {
//                    $newOrganizationData = $this->organizationDataModel->newInstance($detail);
//
//                    if (!$newOrganizationData->save()) {
//                        return 'Error during OrganizationData table migration.';
//                    }
//                }
            }

            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();

            throw $e;
        }


        return 'OrganizationData table migrated.';
    }
}
