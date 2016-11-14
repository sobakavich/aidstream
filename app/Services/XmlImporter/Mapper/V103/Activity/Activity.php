<?php namespace App\Services\XmlImporter\Mapper\V103\Activity;

use App\Services\XmlImporter\Mapper\XmlHelper;

/**
 * Class Activity
 * @package App\Services\XmlImporter\Mapper\V103\Activity
 */
class Activity
{
    use XmlHelper;

    /**
     * @var array
     */
    public $activityElements = [
        'iatiIdentifier'      => 'identifier',
        'otherIdentifier'     => 'other_identifier',
        'reportingOrg'        => 'identifier',
        'title'               => 'title',
        'description'         => 'description',
        'activityStatus'      => 'activity_status',
        'activityDate'        => 'activity_date',
        'contactInfo'         => 'contact_info',
        'activityScope'       => 'activity_scope',
        'participatingOrg'    => 'participating_organization',
        'recipientCountry'    => 'recipient_country',
        'recipientRegion'     => 'recipient_region',
        'location'            => 'location',
        'sector'              => 'sector',
        'countryBudgetItems'  => 'country_budget_items',
        'humanitarianScope'   => 'humanitarian_scope',
        'policyMarker'        => 'policy_marker',
        'collaborationType'   => 'collaboration_type',
        'defaultFlowType'     => 'default_flow_type',
        'defaultFinanceType'  => 'default_finance_type',
        'defaultAidType'      => 'default_aid_type',
        'defaultTiedStatus'   => 'default_tied_status',
        'budget'              => 'budget',
        'plannedDisbursement' => 'planned_disbursement',
        'capitalSpend'        => 'capital_spend',
        'documentLink'        => 'document_link',
        'relatedActivity'     => 'related_activity',
        'legacyData'          => 'legacy_data',
        'conditions'          => 'conditions',
        'defaultFieldValues'  => 'default_field_values'
    ];

    /**
     * @var array
     */
    public $activity = [];

    /**
     * @var array
     */
    public $identifier = [];


    /**
     * @var array
     */
    public $otherIdentifier = [];

    /**
     * @var array
     */
    public $title = [];

    /**
     * @var array
     */
    public $reporting = [];

    /**
     * @var array
     */
    public $description = [];

    /**
     * @var array
     */
    public $participatingOrg = [];

    /**
     * @var array
     */
    public $activityDate = [];

    /**
     * @var array
     */
    public $contactInfo = [];

    /**
     * @var array
     */
    public $sector = [];

    /**
     * @var array
     */
    public $budget = [];

    /**
     * @var array
     */
    public $recipientRegion = [];

    /**
     * @var array
     */
    public $recipientCountry = [];

    /**
     * @var array
     */
    public $location = [];

    /**
     * @var array
     */
    public $plannedDisbursement = [];

    /**
     * @var array
     */
    public $capitalSpend = [];

    /**
     * @var array
     */
    public $countryBudgetItems = [];

    /**
     * @var array
     */
    public $documentLink = [];

    /**
     * @var array
     */
    public $policyMarker = [];

    /**
     * @var array
     */
    public $conditions = [];

    /**
     * @var array
     */
    public $legacyData = [];

    /**
     * @var array
     */
    public $humanitarianScope = [];

    /**
     * @var array
     */
    public $relatedActivity = [];

    /**
     * @var int
     */
    public $index = 0;

    protected $emptyNarrative = [['narrative' => '', 'language' => '']];

    /**
     * @param array $activityData
     * @param       $template
     * @return array
     */
    public function map(array $activityData, $template)
    {
        foreach ($activityData as $index => $activity) {
            $elementName = $this->name($activity);
            $this->resetIndex($elementName);
            $this->activity[$this->activityElements[$elementName]] = $this->$elementName($activity, $template);
        }

        return $this->activity;
    }

    /**
     * @param $elementName
     */
    public function resetIndex($elementName)
    {
        if (!array_key_exists($this->activityElements[$elementName], $this->activity)) {
            $this->index = 0;
        }
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function iatiIdentifier($activity, $template)
    {
        $this->identifier                         = $template['identifier'];
        $this->identifier['iati_identifier_text'] = $this->value($activity);

        return $this->identifier;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function otherIdentifier($activity, $template)
    {
        $this->otherIdentifier[$this->index]                              = $template['other_identifier'];
        $this->otherIdentifier[$this->index]['reference']                 = $this->attributes($activity, 'ref');
        $this->otherIdentifier[$this->index]['type']                      = $this->attributes($activity, 'type');
        $this->otherIdentifier[$this->index]['owner_org'][0]['reference'] = $this->attributes($activity, 'ref', 'ownerOrg');
        $this->otherIdentifier[$this->index]['owner_org'][0]['narrative'] = (($narrative = $this->value($activity['value'], 'ownerOrg')) == '') ? $this->emptyNarrative : $narrative;
        $this->index ++;

        return $this->otherIdentifier;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function title($activity, $template)
    {
        foreach ($activity['value'] as $index => $value) {
            $this->title = $template['title'];
            $this->title = $this->narrative($activity);
        }

        return $this->title;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function reportingOrg($activity, $template)
    {
        $this->identifier['activity_identifier'] = substr($this->identifier['iati_identifier_text'], strlen($this->attributes($activity, 'ref')) + 1);

        return $this->identifier;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function description($activity, $template)
    {
        $type                                  = $this->attributes($activity, 'type');
        $this->description[$type]['type']      = $type;
        $this->description[$type]['narrative'] = $this->narrative($activity);

        return $this->description;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function participatingOrg($activity, $template)
    {
        $this->participatingOrg[$this->index]                      = $template['participating_organization'];
        $this->participatingOrg[$this->index]['organization_role'] = $this->attributes($activity, 'role');
        $this->participatingOrg[$this->index]['identifier']        = $this->attributes($activity, 'ref');
        $this->participatingOrg[$this->index]['organization_type'] = $this->attributes($activity, 'type');
        $this->participatingOrg[$this->index]['activity_id']       = $this->attributes($activity, 'activity-id');
        $this->participatingOrg[$this->index]['narrative']         = $this->narrative($activity);
        $this->index ++;

        return $this->participatingOrg;
    }

    /**
     * @param $activity
     * @param $template
     * @return mixed|string
     */
    public function activityStatus($activity, $template)
    {
        return $this->attributes($activity, 'code');
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function activityDate($activity, $template)
    {
        $this->activityDate[$this->index]              = $template['activity_date'];
        $this->activityDate[$this->index]['date']      = $this->attributes($activity, 'iso-date');
        $this->activityDate[$this->index]['type']      = $this->attributes($activity, 'type');
        $this->activityDate[$this->index]['narrative'] = $this->narrative($activity);
        $this->index ++;

        return $this->activityDate;
    }

    /**
     * @param $activity
     * @param $template
     * @return mixed|string
     */
    public function activityScope($activity, $template)
    {
        return $this->attributes($activity, 'code');
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function contactInfo($activity, $template)
    {
        $this->contactInfo[$this->index]                                    = $template['contact_info'];
        $this->contactInfo[$this->index]['type']                            = $this->attributes($activity, 'type');
        $this->contactInfo[$this->index]['organization'][0]['narrative']    = $this->value(getVal($activity, ['value'], []), 'organisation');
        $this->contactInfo[$this->index]['department'][0]['narrative']      = $this->value(getVal($activity, ['value'], []), 'department');
        $this->contactInfo[$this->index]['person_name'][0]['narrative']     = $this->value(getVal($activity, ['value'], []), 'personName');
        $this->contactInfo[$this->index]['job_title'][0]['narrative']       = $this->value(getVal($activity, ['value'], []), 'jobTitle');
        $this->contactInfo[$this->index]['telephone']                       = $this->filterValues(getVal($activity, ['value'], []), 'telephone');
        $this->contactInfo[$this->index]['email']                           = $this->filterValues(getVal($activity, ['value'], []), 'email');
        $this->contactInfo[$this->index]['website']                         = $this->filterValues(getVal($activity, ['value'], []), 'website');
        $this->contactInfo[$this->index]['mailing_address'][0]['narrative'] = $this->value(getVal($activity, ['value'], []), 'mailingAddress');
        $this->index ++;

        return $this->contactInfo;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function sector($activity, $template)
    {
        $this->sector[$this->index]                         = $template['sector'];
        $vocabulary                                         = $this->attributes($activity, 'vocabulary');
        $this->sector[$this->index]['sector_vocabulary']    = $vocabulary;
        $this->sector[$this->index]['vocabulary_uri']       = $this->attributes($activity, 'vocabulary_uri');
        $this->sector[$this->index]['sector_code']          = ($vocabulary == 1) ? $this->attributes($activity, 'code') : '';
        $this->sector[$this->index]['sector_category_code'] = ($vocabulary == 2) ? $this->attributes($activity, 'code') : '';
        $this->sector[$this->index]['sector_text']          = ($vocabulary != 1 && $vocabulary != 2) ? $this->attributes($activity, 'code') : '';
        $this->sector[$this->index]['percentage']           = $this->attributes($activity, 'percentage');
        $this->sector[$this->index]['narrative']            = $this->narrative($activity);
        $this->index ++;

        return $this->sector;
    }

    /**
     * @param $activity
     * @param $template
     * @return mixed|string
     */
    public function defaultFlowType($activity, $template)
    {
        return $this->attributes($activity, 'code');
    }

    /**
     * @param $activity
     * @param $template
     * @return mixed|string
     */
    public function defaultFinanceType($activity, $template)
    {
        return $this->attributes($activity, 'code');
    }

    /**
     * @param $activity
     * @param $template
     * @return mixed|string
     */
    public function defaultAidType($activity, $template)
    {
        return $this->attributes($activity, 'code');
    }

    /**
     * @param $activity
     * @param $template
     * @return mixed|string
     */
    public function defaultTiedStatus($activity, $template)
    {
        return $this->attributes($activity, 'code');
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function budget($activity, $template)
    {
        $this->budget[$this->index]                            = $template['budget'];
        $this->budget[$this->index]['budget_type']             = $this->attributes($activity, 'type');
        $this->budget[$this->index]['status']                  = $this->attributes($activity, 'status');
        $this->budget[$this->index]['period_start'][0]['date'] = $this->attributes($activity, 'iso-date', 'periodStart');
        $this->budget[$this->index]['period_end'][0]['date']   = $this->attributes($activity, 'iso-date', 'periodEnd');
        $this->budget[$this->index]['value'][0]['amount']      = $this->value(getVal($activity, ['value'], []), 'value');
        $this->budget[$this->index]['value'][0]['currency']    = $this->attributes($activity, 'currency', 'value');
        $this->budget[$this->index]['value'][0]['value_date']  = $this->attributes($activity, 'value-date', 'value');
        $this->index ++;

        return $this->budget;
    }


    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function recipientRegion($activity, $template)
    {
        $this->recipientRegion[$this->index]                      = $template['recipient_region'];
        $this->recipientRegion[$this->index]['region_code']       = $this->attributes($activity, 'code');
        $this->recipientRegion[$this->index]['region_vocabulary'] = $this->attributes($activity, 'vocabulary');
        $this->recipientRegion[$this->index]['vocabulary_uri']    = $this->attributes($activity, 'vocabulary-uri');
        $this->recipientRegion[$this->index]['percentage']        = $this->attributes($activity, 'percentage');
        $this->recipientRegion[$this->index]['narrative']         = $this->narrative($activity);
        $this->index ++;

        return $this->recipientRegion;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function recipientCountry($activity, $template)
    {
        $this->recipientCountry[$this->index]                 = $template['recipient_country'];
        $this->recipientCountry[$this->index]['country_code'] = $this->attributes($activity, 'code');
        $this->recipientCountry[$this->index]['percentage']   = $this->attributes($activity, 'percentage');
        $this->recipientCountry[$this->index]['narrative']    = $this->narrative($activity);
        $this->index ++;

        return $this->recipientCountry;
    }


    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function location($activity, $template)
    {
        $this->location[$this->index]                                         = $template['location'];
        $this->location[$this->index]['reference']                            = $this->attributes($activity, 'ref');
        $this->location[$this->index]['location_reach'][0]['code']            = $this->attributes($activity, 'code', 'locationReach');
        $this->location[$this->index]['location_id'][0]['vocabulary']         = $this->attributes($activity, 'vocabulary', 'locationId');
        $this->location[$this->index]['location_id'][0]['code']               = $this->attributes($activity, 'code', 'locationId');
        $this->location[$this->index]['name'][0]['narrative']                 = (($name = $this->value(getVal($activity, ['value'], []), 'name')) == '') ? $this->emptyNarrative : $name;
        $this->location[$this->index]['location_description'][0]['narrative'] = (($locationDesc = $this->value(
                getVal($activity, ['value'], []),
                'description'
            )) == '') ? $this->emptyNarrative : $locationDesc;
        $this->location[$this->index]['activity_description'][0]['narrative'] = (($activityDesc = $this->value(
                getVal($activity, ['value'], []),
                'activityDescription'
            )) == '') ? $this->emptyNarrative : $activityDesc;
        $this->location[$this->index]['administrative']                       = $this->filterAttributes(getVal($activity, ['value'], []), 'administrative', ['code', 'vocabulary', 'level']);
        $this->location[$this->index]['point'][0]['srs_name']                 = $this->attributes($activity, 'srsName', 'point');
        $this->location[$this->index]['point'][0]['position'][0]              = $this->latAndLong(getVal($activity, ['value'], []));
        $this->location[$this->index]['exactness'][0]['code']                 = $this->attributes($activity, 'code', 'exactness');
        $this->location[$this->index]['location_class'][0]['code']            = $this->attributes($activity, 'code', 'locationClass');
        $this->location[$this->index]['feature_designation'][0]['code']       = $this->attributes($activity, 'code', 'featureDesignation');
        $this->index ++;

        return $this->location;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function plannedDisbursement($activity, $template)
    {
        $this->plannedDisbursement[$this->index]                                   = $template['planned_disbursement'];
        $this->plannedDisbursement[$this->index]['planned_disbursement_type']      = $this->attributes($activity, 'type');
        $this->plannedDisbursement[$this->index]['period_start'][0]['date']        = $this->attributes($activity, 'iso-date', 'periodStart');
        $this->plannedDisbursement[$this->index]['period_end'][0]['date']          = $this->attributes($activity, 'iso-date', 'periodEnd');
        $this->plannedDisbursement[$this->index]['value'][0]['amount']             = $this->value(getVal($activity, ['value'], []), 'value');
        $this->plannedDisbursement[$this->index]['value'][0]['currency']           = $this->attributes($activity, 'currency', 'value');
        $this->plannedDisbursement[$this->index]['value'][0]['value_date']         = $this->attributes($activity, 'value-date', 'value');
        $this->plannedDisbursement[$this->index]['provider_org'][0]['ref']         = $this->attributes($activity, 'ref', 'providerOrg');
        $this->plannedDisbursement[$this->index]['provider_org'][0]['activity_id'] = $this->attributes($activity, 'provider-activity-id', 'providerOrg');
        $this->plannedDisbursement[$this->index]['provider_org'][0]['type']        = $this->attributes($activity, 'type', 'providerOrg');
        $this->plannedDisbursement[$this->index]['provider_org'][0]['narrative']   = (($providerOrg = $this->value(
                getVal($activity, ['value'], []),
                'providerOrg'
            )) == '') ? $this->emptyNarrative : $providerOrg;
        $this->plannedDisbursement[$this->index]['receiver_org'][0]['ref']         = $this->attributes($activity, 'ref', 'receiverOrg');
        $this->plannedDisbursement[$this->index]['receiver_org'][0]['activity_id'] = $this->attributes($activity, 'receiver-activity-id', 'receiverOrg');
        $this->plannedDisbursement[$this->index]['receiver_org'][0]['type']        = $this->attributes($activity, 'type', 'receiverOrg');
        $this->plannedDisbursement[$this->index]['receiver_org'][0]['narrative']   = (($receiverOrg = $this->value(
                getVal($activity, ['value'], []),
                'receiverOrg'
            )) == '') ? $this->emptyNarrative : $receiverOrg;
        $this->index ++;

        return $this->plannedDisbursement;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function countryBudgetItems($activity, $template)
    {
        $this->countryBudgetItems[$this->index]               = $template['country_budget_items'];
        $this->countryBudgetItems[$this->index]['vocabulary'] = $vocabulary = $this->attributes($activity, 'vocabulary');
        foreach (getVal($activity, ['value'], []) as $index => $budgetItem) {
            $this->countryBudgetItems[$this->index]['budget_item'][$index]['code']                        = ($vocabulary == 1) ? $this->attributes($budgetItem, 'code') : "";
            $this->countryBudgetItems[$this->index]['budget_item'][$index]['code_text']                   = ($vocabulary != 1) ? $this->attributes($budgetItem, 'vocabulary') : "";
            $this->countryBudgetItems[$this->index]['budget_item'][$index]['percentage']                  = $this->attributes($budgetItem, 'percentage');
            $this->countryBudgetItems[$this->index]['budget_item'][$index]['description'][0]['narrative'] = (($desc = $this->value(
                    getVal($budgetItem, ['value'], []),
                    'description'
                )) == '') ? $this->emptyNarrative : $desc;
        }
        $this->index ++;

        return $this->countryBudgetItems;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function documentLink($activity, $template)
    {
        $this->documentLink[$this->index]                             = $template['document_link'];
        $this->documentLink[$this->index]['url']                      = $this->attributes($activity, 'url');
        $this->documentLink[$this->index]['format']                   = $this->attributes($activity, 'format');
        $this->documentLink[$this->index]['title'][0]['narrative']    = (($title = $this->value(getVal($activity, ['value'], []), 'title')) == '') ? $this->emptyNarrative : $title;
        $this->documentLink[$this->index]['category'][0]['code']      = $this->attributes($activity, 'code', 'category');
        $this->documentLink[$this->index]['language'][0]['language']  = $this->attributes($activity, 'code', 'language');
        $this->documentLink[$this->index]['document_date'][0]['date'] = $this->attributes($activity, 'iso-date', 'documentDate');
        $this->index ++;

        return $this->documentLink;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function policyMarker($activity, $template)
    {
        $this->policyMarker[$this->index]                   = $template['policy_marker'];
        $this->policyMarker[$this->index]['vocabulary']     = $this->attributes($activity, 'vocabulary');
        $this->policyMarker[$this->index]['vocabulary_uri'] = $this->attributes($activity, 'vocabulary-uri');
        $this->policyMarker[$this->index]['policy_marker']  = $this->attributes($activity, 'code');
        $this->policyMarker[$this->index]['significance']   = $this->attributes($activity, 'significance');
        $this->policyMarker[$this->index]['narrative']      = $this->narrative($activity);
        $this->index ++;

        return $this->policyMarker;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function conditions($activity, $template)
    {
        $this->conditions                       = $template['conditions'];
        $this->conditions['condition_attached'] = $this->attributes($activity, 'attached');
        foreach (getVal($activity, ['value'], []) as $index => $condition) {
            $this->conditions['condition'][$index]['condition_type'] = $this->attributes($condition, 'type');
            $this->conditions['condition'][$index]['narrative']      = $this->narrative($condition);
        }
        $this->index ++;

        return $this->conditions;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function legacyData($activity, $template)
    {
        $this->legacyData[$this->index]                    = $template['legacy_data'];
        $this->legacyData[$this->index]['name']            = $this->attributes($activity, 'name');
        $this->legacyData[$this->index]['value']           = $this->attributes($activity, 'value');
        $this->legacyData[$this->index]['iati_equivalent'] = $this->attributes($activity, 'iati-equivalent');
        $this->index ++;

        return $this->legacyData;
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function humanitarianScope($activity, $template)
    {
        $this->humanitarianScope[$this->index]                   = $template['humanitarian_scope'];
        $this->humanitarianScope[$this->index]['type']           = $this->attributes($activity, 'type');
        $this->humanitarianScope[$this->index]['vocabulary']     = $this->attributes($activity, 'vocabulary');
        $this->humanitarianScope[$this->index]['vocabulary_uri'] = $this->attributes($activity, 'vocabulary-uri');
        $this->humanitarianScope[$this->index]['code']           = $this->attributes($activity, 'code');
        $this->humanitarianScope[$this->index]['narrative']      = $this->narrative($activity);
        $this->index ++;

        return $this->humanitarianScope;
    }

    /**
     * @param $activity
     * @param $template
     * @return mixed|string
     */
    public function collaborationType($activity, $template)
    {
        return $this->attributes($activity, 'code');
    }

    /**
     * @param $activity
     * @param $template
     * @return mixed|string
     */
    public function capitalSpend($activity, $template)
    {
        return $this->attributes($activity, 'percentage');
    }

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function relatedActivity($activity, $template)
    {
        $this->relatedActivity[$this->index]                        = $template['related_activity'];
        $this->relatedActivity[$this->index]['relationship_type']   = $this->attributes($activity, 'type');
        $this->relatedActivity[$this->index]['activity_identifier'] = $this->attributes($activity, 'ref');
        $this->index ++;

        return $this->relatedActivity;
    }

    /**
     * Returns lat and long for location field.
     * @param $values
     * @return array
     */
//    public function latAndLong($values)
//    {
//        $narrative = $this->value($values, 'point');
//        $data      = ['latitude' => '', 'longitude' => ''];
//        foreach ($narrative as $latLong) {
//            $narrative = $latLong['narrative'];
//            if ($narrative != "") {
//                $text = explode(" ", $latLong['narrative']);
//                if (count($text) == 2) {
//                    $data['latitude']  = $text[0];
//                    $data['longitude'] = $text[1];
//                }
//            }
//        }
//
//        return $data;
//    }

    /**
     * Filter the provided key and groups the values in array.
     * $values = data['value']
     * @param      $values
     * @param null $key
     * @return array
     */
//    public function filterValues($values, $key = null)
//    {
//        $index = 0;
//        $data  = [[$key => '']];
//        foreach ($values as $value) {
//            if ($this->name($value['name']) == $key) {
//                $data[$index][$key] = $this->value($value);
//                $index ++;
//            }
//        }
//
//        return $data;
//    }

    /**
     *  Filter the provided key, Convert the provided template to array and groups the attributes.
     * @param       $values
     * @param null  $key
     * @param array $template
     * @return array
     */
//    public function filterAttributes($values, $key = null, array $template)
//    {
//        $index = 0;
//        $data  = $this->templateToArray($template);
//
//        foreach ($values as $value) {
//            if ($this->name($value['name']) == $key) {
//                foreach ($value['attributes'] as $attributeKey => $attribute) {
//                    $data[$index][$attributeKey] = $attribute;
//                }
//                $index ++;
//            }
//        }

//        return $data;
//    }

    /**
     * Converts the provided template into key empty value pairs.
     * @param array $template
     * @return array
     */
//    public function templateToArray(array $template)
//    {
//        if (is_array($template)) {
//            $data = [array_flip($template)];
//            foreach ($data as $index => $values) {
//                foreach ($values as $key => $value) {
//                    $data[$index][$key] = "";
//                }
//            }
//
//            return $data;
//        }
//
//        return [];
//    }

    /**
     * Get the value from the array.
     * If key is provided then the value is fetched from the value field of the data.
     * If key is provided then the $fields = $data['value'] else $fields = $data.
     * If the value is array then narrative is returned else only the value is returned.
     * @param array $fields
     * @param null  $key
     * @return array|mixed|string
     */
//    public function value(array $fields, $key = null)
//    {
//        if (!$key) {
//            return getVal($fields, ['value'], '');
//        }
//        foreach ($fields as $field) {
//            if ($this->name($field['name']) == $key) {
//                if (is_array($field['value'])) {
//                    return $this->narrative($field);
//                }
//
//                return getVal($field, ['value'], '');
//            }
//        }
//
//        return [['narrative' => '', 'language' => '']];
//    }

    /**
     * Returns the all narrative present in the provided $subElement.
     * @param $subElement
     * @return mixed
     * @internal param $field
     */
//    public function narrative($subElement)
//    {
//        $field = [['narrative' => '', 'language' => '']];
//        if (is_array(getVal((array) $subElement, ['value'], []))) {
//            foreach (getVal((array) $subElement, ['value'], []) as $index => $value) {
//                $field[$index] = [
//                    'narrative' => trim(getVal($value, ['value'], '')),
//                    'language'  => $this->attributes($value, 'language')
//                ];
//            }
//
//            return $field;
//        } else {
//            $field = [
//                'narrative' => trim(getVal($subElement, ['value'], '')),
//                'language'  => $this->attributes($subElement, 'language')
//            ];
//
//            return $field;
//        }
//    }

    /**
     * @param      $element
     * @param bool $snakeCase
     * @return string
     */
//    public function name($element, $snakeCase = false)
//    {
//        if (is_array($element)) {
//            $camelCaseString = camel_case(str_replace('{}', '', $element['name']));
//
//            return $snakeCase ? snake_case($camelCaseString) : $camelCaseString;
//        }
//
//        $camelCaseString = camel_case(str_replace('{}', '', $element));
//
//        return $snakeCase ? snake_case($camelCaseString) : $camelCaseString;
//    }

    /**
     * Returns the attributes of the provided element.
     * If key is provided then the attribute equal to the key is returned.
     * If fieldName and key both are provided then the attributes inside value is returned.
     * @param array $element
     * @param null  $key
     * @param null  $fieldName
     * @return mixed|string
     */
//    public function attributes(array $element, $key = null, $fieldName = null)
//    {
//        if (!$key) {
//            return getVal($element, ['attributes'], []);
//        }
//
//        if ($fieldName && $key) {
//            $value = "";
//            foreach ($element['value'] as $value) {
//                if ($fieldName == $this->name($value['name'])) {
//                    return $this->attributes($value, $key);
//                } else {
//                    $value = "";
//                }
//            }
//
//            return $value;
//        }
//
//        $value = getVal($element, ['attributes'], []);
//
//        if ($value) {
//            if ($key == 'language') {
//                foreach ($value as $key => $item) {
//                    if ($key == $this->name($key)) {
//                        return $item;
//                    }
//                }
//                $code = array_first(
//                    $value,
//                    function () {
//                        return true;
//                    }
//                );

//            }
//
//            return getVal($element, ['attributes', $key], '');
//        }
//
//        return '';
//    }
}
