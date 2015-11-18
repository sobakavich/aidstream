<?php namespace App\Core\V201\Element\Activity;

/**
 * Class DefaultFinanceType
 * @package app\Core\V201\Element\Activity
 */
class DefaultFinanceType
{
    /**
     * @return default finance type form path
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\DefaultFinanceType';
    }

    /**
     * @return default finance type repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\DefaultFinanceType');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData($activity)
    {
        $activityData = [
            '@attributes' => [
                'code' => $activity['default_finance_type']
            ]
        ];

        return $activityData;
    }
}
