<?php namespace App\Migration\Migrator;

use App\Migration\Entities\UserGroup;
use App\Migration\Migrator\Contract\MigratorContract;
use App\Models\SuperAdmin\UserGroup as UserGroupModel;
use App\User as UserModel;
use Illuminate\Database\DatabaseManager;


class UserGroupMigrator implements MigratorContract
{
    /**
     * @var UserGroup
     */
    protected $userGroup;

    /**
     * @var UserGroupModel
     */
    protected $userGroupModel;

    protected $userModel;

    /**
     * UserGroupMigrator constructor.
     * @param UserGroup       $userGroup
     * @param  UserGroupModel $userGroupModel
     */
    public function __construct(UserGroup $userGroup, UserGroupModel $userGroupModel, UserModel $userModel)
    {
        $this->userGroup      = $userGroup;
        $this->userGroupModel = $userGroupModel;
        $this->userModel      = $userModel;
    }

    /**
     * Migrate data from old system into the new one.
     * @param $accountIds
     * @return string
     */
    public function migrate(array $accountIds)
    {
        $groupIdArray = [];
        // TODO: Implement migrate() method.
        $db                = app()->make(DatabaseManager::class)->connection('mysql');
        $groupIdCollection = $db->table('user_group')
                                ->select('group_id')
                                ->get();

        $userIdCollection = $db->table('user_group')
                               ->select('user_id')
                               ->get();

        foreach ($userIdCollection as $userIdObj) {
            $select    = ['user_id', 'email', 'user_name', 'password', 'account_id'];
            $user_id   = $userIdObj->user_id;
            $userTable = $db->table('user')
                            ->select($select)
                            ->where('user_id', '=', $user_id)
                            ->first();

            $userProfile = $db->table('profile')
                              ->select('last_name', 'first_name')
                              ->where('user_id', $user_id)
                              ->first();

            $email     = $userTable->email;
            $username  = $userTable->user_name;
            $password  = $userTable->password;
            $lastName  = $userProfile->last_name;
            $firstName = $userProfile->first_name;
            $userArray = ['id' => $user_id, 'first_name' => $firstName, 'last_name' => $lastName, 'email' => $email, 'username' => $username, 'password' => $password];

            $newUser = $this->userModel->newInstance($userArray);
            $newUser->save();
        }

        foreach ($groupIdCollection as $groupIdObj) {
            $groupId        = $groupIdObj->group_id;
            $groupIdArray[] = $groupId;
        }

        $oldUserGroup = $this->userGroup->getData($groupIdArray);

        foreach ($oldUserGroup as $userGroup) {
            $newUserGroup = $this->userGroupModel->newInstance($userGroup);

            if (!$newUserGroup->save()) {
                return 'Error during User Group table migration.';
            }
        }

        return 'User Group table migrated.';
    }
}
