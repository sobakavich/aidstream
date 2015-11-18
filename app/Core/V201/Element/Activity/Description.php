<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;

/**
 * Class Description
 * return description form and description repository
 * @package app\Core\V201\Element\Activity
 */
class Description extends BaseElement
{
    /**
     * @return description form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleDescription";
    }

    /**
     * @return description repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Description');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData($activity)
    {
        $activityData = [];
        $descriptions = (array) $activity->description;
        foreach ($descriptions as $description) {
            $activityData[] = [
                '@attribute' => [
                    'type' => $description['vocabulary']
                ],
                'narrative'  => $this->buildNarrative($description['narrative'])
            ];
        }

        return $activityData;
    }
}
