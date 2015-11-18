<?php namespace App\Core\V201\Element\Activity;

use App\Helpers\ArrayToXml;
use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Settings;

/**
 * Class XmlGenerator
 * @package App\Core\V201\Element\Activity
 */
class XmlGenerator
{

    protected $titleElem;
    protected $arrayToXml;
    protected $descriptionElem;
    protected $activityStatusElem;

    /**
     * @param ArrayToXml        $arrayToXml
     * @param ActivityPublished $activityPublished
     */
    public function __construct(ArrayToXml $arrayToXml, ActivityPublished $activityPublished)
    {
        $this->arrayToXml        = $arrayToXml;
        $this->activityPublished = $activityPublished;
    }

    /**
     * @param $activityElement
     */
    public function setElements($activityElement)
    {
        $this->titleElem          = $activityElement->getTitle();
        $this->descriptionElem    = $activityElement->getDescription();
        $this->activityStatusElem = $activityElement->getActivityStatus();
    }

    /**
     * @param Activity $activity
     * @param Settings $settings
     * @param          $activityElement
     */
    public function generateXml(Activity $activity, Settings $settings, $activityElement)
    {
        $xml               = $this->getXml($activity, $settings, $activityElement);
        $publishedActivity = $activity->identifier['iati_identifier_text'] . '.xml';
        $result            = $xml->save(public_path('uploads/files/activity/' . $publishedActivity));
        if ($result) {
            $published = $this->activityPublished->firstOrNew(['filename' => 'Activities.xml', 'organization_id' => $activity->organization_id]);
            $published->touch();
            $publishedActivities = (array) $published->published_activities;
            array_push($publishedActivities, $publishedActivity);
            $published->published_activities = $publishedActivities;
            $published->save();
        }
    }

    /**
     * @param Activity $activity
     * @param Settings $settings
     * @param          $activityElement
     * @return \DomDocument
     */
    public function getXml(Activity $activity, Settings $settings, $activityElement)
    {
        $this->setElements($activityElement);
        $xmlData                                 = [];
        $xmlData['@attributes']                  = [
            'version'            => $settings->version,
            'generated-datetime' => gmdate('c')
        ];
        $xmlData['iati-activity']                = $this->getXmlData($activity);
        $xmlData['iati-activity']['@attributes'] = [
            'last-updated-datetime' => gmdate('c', time($settings->updated_at)),
            'xml:lang'              => $settings->default_field_values[0]['default_language'],
            'default-currency'      => $settings->default_field_values[0]['default_currency']
        ];

        return $this->arrayToXml->createXML('iati-activity', $xmlData);
    }

    /**
     * @param Activity $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $xmlActivity                        = [];
        $xmlActivity['activity-identifier'] = $activity->identifier['iati_identifier_text'];
        $xmlActivity['title']               = $this->titleElem->getXmlData($activity);
        $xmlActivity['description']         = $this->descriptionElem->getXmlData($activity);
        $xmlActivity['activity-status']     = $this->activityStatusElem->getXmlData($activity);

        return array_filter(
            $xmlActivity,
            function ($value) {
                return $value;
            }
        );
    }
}
