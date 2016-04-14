<?php namespace App\Migration\Migrator;

use App\Migration\Entities\User;
use App\Models\Organization\Organization;
use App\User as UserModel;
use App\Migration\Migrator\Contract\MigratorContract;
use Illuminate\Database\DatabaseManager;

/**
 * Class UserMigrator
 * @package App\Migration\Migrator
 */
class UserMigrator implements MigratorContract
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var UserModel
     */
    protected $userModel;

    /**
     * UserMigrator constructor.
     * @param User      $user
     * @param UserModel $userModel
     */
    public function __construct(User $user, UserModel $userModel)
    {
        $this->user      = $user;
        $this->userModel = $userModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(array $accountIds)
    {
        $database = app()->make(DatabaseManager::class);

        $oldUserData = $this->user->getData($accountIds);
        $unmigrated = $this->check($accountIds);

        try {
            foreach ($oldUserData as $userData) {
                if (in_array($userData['org_id'], $unmigrated)) {
                    $newUser = $this->userModel->newInstance($userData);

                    if (!$newUser->save()) {
                        return 'Error during User table migration.';
                    }
                }
            }

            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();

            throw $e;
        }


        return 'Users table migrated.';
    }

    protected function check($accountIds)
    {
        $unmigratedAccounts = [];

        foreach ($accountIds as $accountId) {
            $organization = null;
            $organization = app()->make(Organization::class)->query()->select('*')->where('id', '=', $accountId)->first();

            if ($organization === null) {
                $unmigratedAccounts[] = $accountId;
            }
        }

        return $unmigratedAccounts;
    }
}
