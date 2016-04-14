<?php namespace App\Migration\Migrator;

use App\Migration\Entities\Organization;
use App\Migration\Migrator\Contract\MigratorContract;
use App\Models\Organization\Organization as OrganizationModel;
use Exception;
use Illuminate\Database\DatabaseManager;

/**
 * Class OrganizationMigrator
 * @package App\Migration\Migrator
 */
class OrganizationMigrator implements MigratorContract
{
    /**
     * @var OrganizationModel
     */
    protected $organizationModel;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * OrganizationMigrator constructor.
     * @param Organization      $organization
     * @param OrganizationModel $organizationModel
     */
    public function __construct(Organization $organization, OrganizationModel $organizationModel)
    {
        $this->organization      = $organization;
        $this->organizationModel = $organizationModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(array $accountIds)
    {
        $database = app()->make(DatabaseManager::class);

        $organizationDetail = $this->organization->getData($accountIds);
        $unmigratedAccounts = $this->check($accountIds);

        try {
            foreach ($organizationDetail as $detail) {
                if (in_array($detail['id'], $unmigratedAccounts)) {
                    $organization = $this->organizationModel->newInstance($detail);

                    if (!$organization->save()) {
                        return 'Error during Organization table migration.';
                    }
                }
            }

            $database->commit();

            return 'Organizations table migrated.';
        } catch (Exception $exception) {
            $database->rollback();

            throw $exception;
        }

    }

    protected function check($accountIds)
    {
        $unmigratedAccounts = [];

        foreach ($accountIds as $accountId) {
            $organization = null;
            $organization = app()->make(OrganizationModel::class)->query()->select('*')->where('id', '=', $accountId)->first();

            if ($organization === null) {
                $unmigratedAccounts[] = $accountId;
            }
        }

        return $unmigratedAccounts;
    }
}
