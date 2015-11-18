<?php namespace App\Core\V201\Element\Activity;

/**
 * Class LegacyData
 * @package App\Core\V201\Element\Activity
 */
class LegacyData
{
    /**
     * @return legacyData form
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\LegacyDatas';
    }

    /**
     * @return legacyData repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\LegacyData');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData($activity)
    {
        $activityData = [];
        $legacyDatas  = (array) $activity->legacy_data;
        foreach ($legacyDatas as $legacyData) {
            $activityData[] = [
                '@attributes' => [
                    'name'            => $legacyData['name'],
                    'value'           => $legacyData['value'],
                    'iati-equivalent' => $legacyData['iati_equivalent']
                ]
            ];
        }

        return $activityData;
    }
}
