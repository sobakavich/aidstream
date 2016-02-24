<?php namespace App\Migration\Entities;

use App\Migration\Migrator\Data\UserGroupQuery;
class UserGroup 
{
   protected $userGroupQuery;

    /**
     * UserGroup constructor.
     * @param $userGroupQuery
     */
    public function __construct(UserGroupQuery $userGroupQuery)
    {
        $this->userGroupQuery = $userGroupQuery;
    }

    /**
     * @param $accountIds
     * @return array
     */
    public function getData($groupIds)
    {
        return $this->userGroupQuery->executeFor($groupIds);
    }
}