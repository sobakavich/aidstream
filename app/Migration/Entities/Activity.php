<?php namespace App\Migration\Entities;

use App\Migration\MigrateActivity;

/**
 * Class Activity
 * @package App\Migration\Entities
 */
class Activity
{
    /**
     * @var MigrateActivity
     */
    protected $activityData;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Activity constructor.
     * @param MigrateActivity $activityData
     */
    public function __construct(MigrateActivity $activityData)
    {
        $this->activityData     = $activityData;
    }


    /**
     * Gets Activities data from old database.
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        foreach ($accountIds as $accountId) {
            $organization = getOrganizationFor($accountId);

            if ($organization) {
                $this->data[] = $this->activityData->fetchActivityData($organization->id, $accountId);
            }
        }

        return $this->data;
    }
}
