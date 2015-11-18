<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;

/**
 * Class RecipientCountry
 * @package app\Core\V201\Element\Activity
 */
class RecipientCountry extends BaseElement
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleRecipientCountry";
    }

    /**
     * @return recipient country repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\RecipientCountry');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData($activity)
    {
        $activityData       = [];
        $recipientCountries = (array) $activity->recipient_country;
        foreach ($recipientCountries as $recipientCountry) {
            $activityData[] = [
                '@attribute' => [
                    'code'       => $recipientCountry['country_code'],
                    'percentage' => $recipientCountry['percentage']
                ],
                'narrative'  => $this->buildNarrative($recipientCountry['narrative'])
            ];
        }

        return $activityData;
    }
}
