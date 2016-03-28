<?php namespace App\Core\tz\Repositories;

use App\Models\Activity\Activity as ActivityModel;
use App\Models\ActivityPublished;

/**
 * Class Activity
 * @package app\Core\tz\Repositories
 */
class Activity
{
    protected $activity;

    /**
     * @param ActivityModel     $activity
     * @param ActivityPublished $activityPublished
     */
    public function __construct(ActivityModel $activity, ActivityPublished $activityPublished)
    {
        $this->activity          = $activity;
        $this->activityPublished = $activityPublished;
    }

    /**
     * insert activity data to database
     * @param array $activity
     * @return ActivityModel
     */
    public function store(array $activity)
    {
        return $this->activity->create($activity);
    }

    /**
     * return specific activity data
     * @param $id
     * @return ActivityModel
     */
    public function getActivityData($id)
    {
        return $this->activity->find($id);
    }

    public function update(array $activity, ActivityModel $activityData)
    {
        $activityData->fill($activity);

        return $activityData->save();
    }
}
