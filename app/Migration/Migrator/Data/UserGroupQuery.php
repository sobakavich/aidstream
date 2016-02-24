<?php namespace App\Migration\Migrator\Data;

use App\Migration\Entities\UserGroup;

class UserGroupQuery extends Query
{
    /**
     * @var UserGroup
     */


    /**
     * UserGroupQuery constructor.
     * @param UserGroup $userGroup
     */
    public function __construct()
    {

    }

    /**
     *
     * @param $groupIds
     * @return array
     */
    public function executeFor($groupIds)
    {
        $this->initDBConnection();

        $data = [];

        foreach ($groupIds as $groupId) {
            $data[] = $this->getData($groupId);
        }
        return $data;
    }

    /**
     * @param $groupId
     * @return array
     */

    protected function getData($groupId)
    {
        $OrgIds = [];
        //fetch user_group
        $select        = ['name', 'username', 'user_id'];
        $UserGroupInfo = $this->connection->table('user_group')
                                          ->select($select)
                                          ->where('group_id', '=', $groupId)
                                          ->first();

        $groupAccountInfo = $this->connection->table('group')
                                             ->select('account_id')
                                             ->where('group_id', '=', $groupId)
                                             ->get();

        foreach ($groupAccountInfo as $accountInfo) {
            $OrgIds[] = $accountInfo->account_id;
        }
        $groupInfo = ['group_name' => $UserGroupInfo->name, 'group_identifier' => $UserGroupInfo->username, 'user_id' => $UserGroupInfo->user_id, 'assigned_organizations' => $OrgIds];
        return $groupInfo;
    }
}