<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityResult;

/**
 * Class Result
 * @package App\Core\V201\Repositories\Activity
 */
class Result
{
    /**
     * @var Activity
     */
    protected $activity;
    /**
     * @var ActivityResult
     */
    protected $activityResult;

    /**
     * @param Activity       $activity
     * @param ActivityResult $activityResult
     */
    function __construct(Activity $activity, ActivityResult $activityResult)
    {
        $this->activity       = $activity;
        $this->activityResult = $activityResult;
    }

    /**
     * Store Result
     * @param array          $resultData
     * @param ActivityResult $activityResult
     * @return bool
     */
    public function store(array $resultData, ActivityResult $activityResult)
    {
        $activityResult->result = $resultData['result'];

        return $activityResult->save();
    }

    /**
     * Store Result
     * @param array          $resultData
     * @param ActivityResult $activityResult
     * @return bool
     */
    public function update(array $resultData, ActivityResult $activityResult)
    {
        $activityResult->result = $resultData['result'];

        return $activityResult->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getResults($activityId)
    {
        return $this->activityResult->where('activity_id', $activityId)->get();
    }

    /**
     * @param $id
     * @return array
     */
    public function getResult($id, $activityId)
    {
        return $this->activityResult->firstOrNew(['id' => $id, 'activity_id' => $activityId]);
    }
}
