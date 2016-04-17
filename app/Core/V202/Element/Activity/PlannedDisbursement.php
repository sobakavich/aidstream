<?php namespace App\Core\V202\Element\Activity;

use App\Core\V201\Element\Activity\PlannedDisbursement as V201PlannedDisbursement;
use App\Models\Activity\Activity;

/**
 * Class PlannedDisbursement
 * @package App\Core\V202\Element\Activity
 */
class PlannedDisbursement extends V201PlannedDisbursement
{
    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData         = [];
        $plannedDisbursements = (array) $activity->planned_disbursement;
        foreach ($plannedDisbursements as $plannedDisbursement) {
            $activityData[] = [
                '@attributes'  => [
                    'type' => $plannedDisbursement['planned_disbursement_type']
                ],
                'period-start' => [
                    '@attributes' => [
                        'iso-date' => $plannedDisbursement['period_start'][0]['date']
                    ]
                ],
                'period-end'   => [
                    '@attributes' => [
                        'iso-date' => $plannedDisbursement['period_end'][0]['date']
                    ]
                ],
                'value'        => [
                    '@attributes' => [
                        'currency'   => $plannedDisbursement['value'][0]['currency'],
                        'value-date' => $plannedDisbursement['value'][0]['value_date']
                    ],
                    '@value'      => $plannedDisbursement['value'][0]['amount']
                ],
                'provider-org' => [
                    '@attributes' => [
                        'ref'                  => getVal($plannedDisbursement, ['provider_org', 0, 'ref']),
                        'provider-activity-id' => getVal($plannedDisbursement, ['provider_org', 0, 'activity_id']),
                        'type'                 => getVal($plannedDisbursement, ['provider_org', 0, 'type'])
                    ],
                    'narrative'   => $this->buildNarrative($plannedDisbursement['provider_org'][0]['narrative'])
                ],
                'receiver-org' => [
                    '@attributes' => [
                        'ref'                  => getVal($plannedDisbursement, ['receiver_org', 0, 'ref']),
                        'receiver-activity-id' => getVal($plannedDisbursement, ['receiver_org', 0, 'activity_id']),
                        'type'                 => getVal($plannedDisbursement, ['receiver_org', 0, 'type'])
                    ],
                    'narrative'   => $this->buildNarrative($plannedDisbursement['receiver_org'][0]['narrative'])
                ]
            ];
        }

        return $activityData;
    }
}
