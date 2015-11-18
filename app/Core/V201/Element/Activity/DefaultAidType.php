<?php namespace App\Core\V201\Element\Activity;

/**
 * Class DefaultAidType
 * @package app\Core\V201\Element\Activity
 */
class DefaultAidType
{
    /**
     * @return default aid type form path
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\DefaultAidType';
    }

    /**
     * @return default aid type repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\DefaultAidType');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData($activity)
    {
        $activityData = [
            '@attributes' => [
                'code' => $activity['default_aid_type']
            ]
        ];

        return $activityData;
    }
}
