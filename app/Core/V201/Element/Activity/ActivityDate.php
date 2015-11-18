<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;

/**
 * Class ActivityDate
 * @package app\Core\V201\Element\Activity
 */
class ActivityDate extends BaseElement
{
    /**
     * @return description form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleActivityDate";
    }

    /**
     * @return description repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\ActivityDate');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData($activity)
    {
        $activityData = [];
        $activityDate = (array) $activity->activity_data;
        foreach ($activityDate as $ActivityDate) {
            $activityData[] = [
                '@attributes' => [
                    'type'     => $ActivityDate['type'],
                    'iso-date' => $ActivityDate['date']
                ],
                'narrative'   => $this->buildNarrative($ActivityDate['narrative'])
            ];
        }

        return $activityData;
    }
}
