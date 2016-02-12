<?php namespace App\Migration\Entities;


use App\Migration\MigrateUser;

/**
 * Class User
 * @package App\Migration\Entities
 */
class User
{
    /**
     * @var MigrateUser
     */
    protected $migrateUser;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * User constructor.
     * @param MigrateUser $migrateUser
     */
    public function __construct(MigrateUser $migrateUser)
    {
        $this->migrateUser = $migrateUser;
    }

    /**
     * Gets Users data from old database.
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        foreach ($accountIds as $accountId) {
            $users = $this->migrateUser->getUsersFor($accountId);

            foreach ($users as $user) {
                $this->data[] = $this->migrateUser->userDataFetch($user);
            }
        }

        return $this->data;
    }
}
