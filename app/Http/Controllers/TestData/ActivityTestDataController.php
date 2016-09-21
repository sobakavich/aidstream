<?php

namespace App\Http\Controllers\TestData;

use App\Http\Controllers\TestData\Traits\BasicActivityData;
use App\Http\Controllers\TestData\Traits\TransactionData;
use App\Models\Activity\Activity;
use App\Models\Activity\Transaction;
use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Excel;

/**
 * Class ActivityTestDataController
 * @package App\Http\Controllers
 */
class ActivityTestDataController extends Controller
{

    use BasicActivityData, TransactionData;
    /**
     * @var Activity
     */
    protected $activity;
    /**
     * @var Transaction
     */
    protected $transaction;

    protected $csv;

    const NO_OF_ACTIVITIES = 10;

    protected $dataContainer = [];

    protected $index = 0;

    protected $rowIndexCount = 0;

    protected $maxRowIndex = 0;

    protected $singleHeaders = [
        'activity_identifier'                => '',
        'activity_title'                     => '',
        'activity_description_general'       => '',
        'activity_description_objectives'    => '',
        'activity_description_target_groups' => '',
        'activity_description_others'        => '',
        'activity_status'                    => '',
        'actual_start_date'                  => '',
        'actual_end_date'                    => '',
        'planned_start_date'                 => '',
        'planned_end_date'                   => '',
    ];

    protected $participatingOrganisationHeaders = [
        'participating_organisation_role'       => '',
        'participating_organisation_type'       => '',
        'participating_organisation_name'       => '',
        'participating_organisation_identifier' => '',
    ];

    protected $recipientCountryHeaders = [
        'recipient_country_code'       => '',
        'recipient_country_percentage' => '',
    ];

    protected $recipientRegionHeaders = [
        'recipient_region_code'       => '',
        'recipient_region_percentage' => '',
    ];

    protected $sectorHeaders = [
        'sector_vocabulary' => '',
        'sector_code'       => '',
        'sector_percentage' => '',
    ];

    protected $transactionHeaders = [
        'transaction_internal_reference'                        => '',
        'transaction_type'                                      => '',
        'transaction_date'                                      => '',
        'transaction_value'                                     => '',
        'transaction_value_date'                                => '',
        'transaction_description'                               => '',
        'transaction_provider_organisation_identifier'          => '',
        'transaction_provider_organisation_type'                => '',
        'transaction_provider_organisation_activity_identifier' => '',
        'transaction_provider_organisation_description'         => '',
        'transaction_receiver_organisation_identifier'          => '',
        'transaction_receiver_organisation_type'                => '',
        'transaction_receiver_organisation_activity_identifier' => '',
        'transaction_receiver_organisation_description'         => '',
        'transaction_sector_vocabulary'                         => '',
        'transaction_sector_code'                               => '',
        'transaction_recipient_country_code'                    => '',
        'transaction_recipient_region_code'                     => ''
    ];

    /**
     * ActivityTestDataController constructor.
     * @param Activity    $activity
     * @param Transaction $transaction
     * @param Excel       $csv
     */
    public function __construct(Activity $activity, Transaction $transaction, Excel $csv)
    {

        $this->activity    = $activity;
        $this->transaction = $transaction;
        $this->csv         = $csv;
    }

    /**
     *
     */
    public function generate()
    {
        $activities = $this->activity->take(self::NO_OF_ACTIVITIES)->orderBy('updated_at', 'desc')->get();
        $this->basicActivityData($activities);
        $this->generateCSV();
    }

    protected function basicActivityData($activities)
    {
        foreach ($activities as $activity) {
//            dd($activities);
            $this->getIdentifier($activity);
            $this->getTitle($activity);
            $this->getGeneralDescription($activity);
            $this->getObjectivesDescription($activity);
            $this->getTargetGroupsDescription($activity);
            $this->getOthersDescription($activity);
            $this->getActivityStatus($activity);
            $this->getActualStartDate($activity);
            $this->getActualEndDate($activity);
            $this->getPlannedStartDate($activity);
            $this->getPlannedEndDate($activity);
            $this->getParticipatingOrganisation($activity);
            $this->getRecipientCountry($activity);
            $this->getRecipientRegion($activity);
            $this->getSector($activity);


            $transactions = $this->transaction->where('activity_id', $activity->id)->get();
            $this->transactionData($transactions);

            $headers = array_merge(
                $this->singleHeaders,
                $this->participatingOrganisationHeaders,
                $this->recipientCountryHeaders,
                $this->recipientRegionHeaders,
                $this->sectorHeaders,
                $this->transactionHeaders
            );

            foreach ($this->dataContainer as $rowIndex => $activityRow) {
                foreach ($headers as $key => $value) {
                    $tempContainer[$rowIndex][$key] = (array_key_exists($key, $activityRow)) ? $activityRow[$key] : '';
                    $this->dataContainer            = $tempContainer;
                }
            }

            $this->maxRowIndex   = 0;
            $this->rowIndexCount = 0;
            $this->index ++;
        }
    }

    protected function transactionData($transactions)
    {
        $this->rowIndexCount = 0;
        foreach ($transactions as $transaction) {
            $transaction                                                                                = $transaction['transaction'];
            $this->dataContainer[$this->index]['transaction_internal_reference']                        = $this->getTransactionInternalReference($transaction);
            $this->dataContainer[$this->index]['transaction_type']                                      = $this->getTransactionType($transaction);
            $this->dataContainer[$this->index]['transaction_date']                                      = $this->getTransactionDate($transaction);
            $this->dataContainer[$this->index]['transaction_value']                                     = $this->getTransactionValue($transaction);
            $this->dataContainer[$this->index]['transaction_value_date']                                = $this->getTransactionValueDate($transaction);
            $this->dataContainer[$this->index]['transaction_description']                               = $this->getTransactionDescription($transaction);
            $this->dataContainer[$this->index]['transaction_provider_organisation_identifier']          = $this->getProviderOrganisationIdentifier($transaction);
            $this->dataContainer[$this->index]['transaction_provider_organisation_type']                = $this->getProviderOrganisationType($transaction);
            $this->dataContainer[$this->index]['transaction_provider_organisation_activity_identifier'] = $this->getProviderOrganisationActivityIdentifier($transaction);
            $this->dataContainer[$this->index]['transaction_provider_organisation_description']         = $this->getProviderOrganisationDescription($transaction);
            $this->dataContainer[$this->index]['transaction_receiver_organisation_identifier']          = $this->getReceiverOrganisationIdentifier($transaction);
            $this->dataContainer[$this->index]['transaction_receiver_organisation_type']                = $this->getReceiverOrganisationType($transaction);
            $this->dataContainer[$this->index]['transaction_receiver_organisation_activity_identifier'] = $this->getReceiverOrganisationActivityIdentifier($transaction);
            $this->dataContainer[$this->index]['transaction_receiver_organisation_description']         = $this->getReceiverOrganisationDescription($transaction);
            $this->dataContainer[$this->index]['transaction_sector_vocabulary']                         = $this->getTransactionSectorVocabulary($transaction);
            $this->dataContainer[$this->index]['transaction_sector_code']                               = $this->getTransactionSectorCode($transaction);
            $this->dataContainer[$this->index]['transaction_recipient_country_code']                    = $this->getTransactionRecipientCountryCode($transaction);
            $this->dataContainer[$this->index]['transaction_recipient_region_code']                     = $this->getTransactionRecipientRegionCode($transaction);
            $this->index ++;
            $this->rowIndexCount ++;
        }
        $this->maxRowIndex = ($this->rowIndexCount > $this->maxRowIndex) ? $this->rowIndexCount : $this->maxRowIndex;
        $this->index       = ($this->index > $this->maxRowIndex) ? $this->index : $this->maxRowIndex - 1;
    }

    protected function generateCSV()
    {
        $data       = $this->dataContainer;
        $mapHeaders = [
            'activity_identifier'                                   => 'Activity Identifier',
            'activity_title'                                        => 'Activity Title',
            'activity_description_general'                          => 'Activity Description (General)',
            'activity_description_objectives'                       => 'Activity Description (Objectives)',
            'activity_description_target_groups'                    => 'Activity Description (Target Groups)',
            'activity_description_others'                           => 'Activity Description (Others)',
            'activity_status'                                       => 'Activity Status',
            'actual_start_date'                                     => 'Actual Start Date',
            'actual_end_date'                                       => 'Actual End Date',
            'planned_start_date'                                    => 'Planned Start Date',
            'planned_end_date'                                      => 'Planned End Date',
            'participating_organisation_role'                       => 'Participating Organisation Role',
            'participating_organisation_type'                       => 'Participating Organisation Type',
            'participating_organisation_name'                       => 'Participating Organisation Name',
            'participating_organisation_identifier'                 => 'Participating Organisation Identifier',
            'recipient_country_code'                                => 'Recipient Country Code',
            'recipient_country_percentage'                          => 'Recipient Country Percentage',
            'recipient_region_code'                                 => 'Recipient Region Code',
            'recipient_region_percentage'                           => 'Recipient Region Percentage',
            'sector_vocabulary'                                     => 'Sector Vocabulary',
            'sector_code'                                           => 'Sector Code',
            'sector_percentage'                                     => 'Sector Percentage',
            'transaction_internal_reference'                        => 'Transaction Internal Reference',
            'transaction_type'                                      => 'Transaction Type',
            'transaction_date'                                      => 'Transaction Date',
            'transaction_value'                                     => 'Transaction Value',
            'transaction_value_date'                                => 'Transaction Value Date',
            'transaction_description'                               => 'Transaction Description',
            'transaction_provider_organisation_identifier'          => 'Transaction Provider Organisation Identifier',
            'transaction_provider_organisation_type'                => 'Transaction Provider Organisation Type',
            'transaction_provider_organisation_activity_identifier' => 'Transaction Provider Organisation Activity Identifier',
            'transaction_provider_organisation_description'         => 'Transaction Provider Organisation Description',
            'transaction_receiver_organisation_identifier'          => 'Transaction Receiver Organisation Identifier',
            'transaction_receiver_organisation_type'                => 'Transaction Receiver Organisation Type',
            'transaction_receiver_organisation_activity_identifier' => 'Transaction Receiver Organisation Activity Identifier',
            'transaction_receiver_organisation_description'         => 'Transaction Receiver Organisation Description',
            'transaction_sector_vocabulary'                         => 'Transaction Sector Vocabulary',
            'transaction_sector_code'                               => 'Transaction Sector Code',
            'transaction_recipient_country_code'                    => 'Transaction Recipient Country Code',
            'transaction_recipient_region_code'                     => 'Transaction Recipient Region Code'
        ];

        $headers          = array_flip($mapHeaders);
        $arrayWithHeaders = array_merge([$headers], $data);

        $this->csv->create(
            'ActivityWithTransaction',
            function ($excel) use ($arrayWithHeaders) {
                $excel->sheet(
                    'Activities With Transaction',
                    function ($sheet) use ($arrayWithHeaders) {
                        $sheet->fromArray($arrayWithHeaders);
                    }
                );
            }
        )->export('csv');
    }
}
