<?php namespace App\Migration\Migrator\Data;

use App\Migration\ActivityData;
use Carbon\Carbon;

class ActivityTransactionsQuery extends Query
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var ActivityData
     */
    protected $activityData;

    /**
     * ActivityTransactionsQuery constructor.
     * @param ActivityData $activityData
     * @internal param array $data
     */
    public function __construct(ActivityData $activityData)
    {
        $this->activityData = $activityData;
    }


    /**
     * @param array $accountIds
     * @return array
     */
    public function executeFor(array $accountIds)
    {
        $data = [];
        $this->initDBConnection();

        foreach ($accountIds as $accountId) {
            if ($organization = getOrganizationFor($accountId)) {
                $data[] = $this->getTransaction($organization->id);
            }
        }

        return $data;
    }

    /**
     * get transactions for activities
     * @param $organizationId
     * @return array
     */
    protected function getTransaction($organizationId)
    {
        $transactionData = [];
        $activities      = $this->activityData->getActivitiesFor($organizationId);

        foreach ($activities as $activity) {
            $transactions = $this->connection->table('iati_transaction')
                                             ->select('*', '@ref as ref')
                                             ->where('activity_id', '=', $activity->id)
                                             ->get();

            foreach ($transactions as $transaction) {
                $transactionData[$activity->id][] = $this->getDataFor($transaction, $activity->id);
            }
        }

        return $transactionData;
    }

    /**
     * @param $transaction
     * @param $activityId
     * @return array
     */
    public function getDataFor($transaction, $activityId)
    {
        $this->data = [];
        $this->fetchTransaction($transaction, $activityId);

        return $this->data;
    }

    protected function fetchTransaction($transaction, $activityId)
    {
        $transactionId = $transaction->id;

        $transactionData = [
            'reference'             => $transaction->ref,
            'transaction_type'      => [['transaction_type_code' => $this->getTransactionElementFrom('iati_transaction/transaction_type', $transactionId, 'TransactionType')]],
            'transaction_date'      => [['date' => $this->getTransactionDate($transactionId)]],
            'value'                 => [$this->getTransactionValue($transactionId)],
            'description'           => [['narrative' => $this->getTransactionDescription($transactionId)]],
            'provider_organization' => [$this->getTransactionProviderOrganization($transactionId)],
            'receiver_organization' => [$this->getTransactionReceiverOrganization($transactionId)],
            'disbursement_channel'  => [['disbursement_channel_code' => $this->getTransactionElementFrom('iati_transaction/disbursement_channel', $transactionId, 'DisbursementChannel')]],
            'sector'                => [$this->getTransactionSector($transactionId)],
            'recipient_country'     => [$this->getTransactionRecipientCountry($transactionId)],
            'recipient_region'      => [$this->getTransactionRecipientRegion($transactionId)],
            'flow_type'             => [['flow_type' => $this->getTransactionElementFrom('iati_transaction/flow_type', $transactionId, 'FlowType')]],
            'finance_type'          => [['finance_type' => $this->getTransactionElementFrom('iati_transaction/finance_type', $transactionId, 'FinanceType')]],
            'aid_type'              => [['aid_type' => $this->getTransactionElementFrom('iati_transaction/aid_type', $transactionId, 'AidType')]],
            'tied_status'           => [['tied_status_code' => $this->getTransactionElementFrom('iati_transaction/tied_status', $transactionId, 'TiedStatus')]]
        ];

        $this->data['activity_id'] = $activityId;
        $this->data['transaction'] = json_encode($transactionData);
        $this->data['created_at']  = Carbon::now();
        $this->data['updated_at']  = Carbon::now();

        return $this;
    }

    /**
     * get transaction elements only with column @code
     * @param $table
     * @param $transactionId
     * @param $codeTable
     * @return string
     */
    protected function getTransactionElementFrom($table, $transactionId, $codeTable)
    {
        $code                   = '';
        $transactionElementData = fetchDataWithCodeFrom($table, 'transaction_id', $transactionId);

        if ($transactionElementData) {
            $code = fetchCode($transactionElementData[0]->code, $codeTable, '');
        }

        return $code;
    }

    /**
     * get transaction date
     * @param $transactionId
     * @return string
     */
    protected function getTransactionDate($transactionId)
    {
        $date            = '';
        $transactionDate = $this->connection->table('iati_transaction/transaction_date')
                                            ->select('@iso_date as date')
                                            ->where('transaction_id', '=', $transactionId)
                                            ->get();

        if ($transactionDate) {
            $date = $transactionDate[0]->date;
        }

        return $date;

    }

    /**
     * get transaction value element
     * @param $transactionId
     * @return array
     */
    protected function getTransactionValue($transactionId)
    {
        $amount = $date = $currency = '';

        $transactionValue = $this->connection->table('iati_transaction/value')
                                             ->select('@value_date as date', '@currency as currency', 'text')
                                             ->where('transaction_id', '=', $transactionId)
                                             ->get();

        if ($transactionValue) {
            $amount   = $transactionValue[0]->text;
            $date     = $transactionValue[0]->date;
            $currency = fetchCode($transactionValue[0]->currency, 'Currency');
        }

        return [
            'amount'   => $amount,
            'date'     => $date,
            'currency' => $currency
        ];
    }

    /**
     * get transaction description
     * @param $transactionId
     * @return array
     */
    protected function getTransactionDescription($transactionId)
    {
        $narrativeData         = [['narrative' => "", 'language' => ""]];
        $descriptionNarratives = getBuilderFor('id', 'iati_transaction/description', 'transaction_id', $transactionId)->first();
        if ($descriptionNarratives) {
            $narratives    = fetchNarratives($descriptionNarratives->id, 'iati_transaction/description/narrative', 'description_id');
            $narrativeData = fetchAnyNarratives($narratives);
        }

        return $narrativeData;
    }

    /**
     * get transaction provider organization data
     * @param $transactionId
     * @return array
     */
    protected function getTransactionProviderOrganization($transactionId)
    {
        $narrativeData           = [['narrative' => '', 'language' => '']];
        $providerOrganizationRef = $providerOrgActivityId = '';

        $providerOrganization = $this->connection->table('iati_transaction/provider_org')
                                                 ->select('@ref as ref', '@provider_activity_id as providerActivityId', 'id')
                                                 ->where('transaction_id', '=', $transactionId)
                                                 ->get();
        if ($providerOrganization) {
            $narratives              = fetchNarratives($providerOrganization[0]->id, 'iati_transaction/provider_org/narrative', 'provider_org_id');
            $narrativeData           = fetchAnyNarratives($narratives);
            $providerOrganizationRef = $providerOrganization[0]->ref;
            $providerOrgActivityId   = $providerOrganization[0]->providerActivityId;
        }

        return ['organization_identifier_code' => $providerOrganizationRef, 'provider_activity_id' => $providerOrgActivityId, 'narrative' => $narrativeData];
    }

    /**
     * get transaction element receiver organization data
     * @param $transactionId
     * @return array
     */
    protected function getTransactionReceiverOrganization($transactionId)
    {
        $narrativeData        = [['narrative' => '', 'language' => '']];
        $receiverOrgRef       = $receiverOrgActivityId = '';
        $receiverOrganization = $this->connection->table('iati_transaction/receiver_org')
                                                 ->select('@ref as ref', '@receiver_activity_id as providerActivityId', 'id')
                                                 ->where('transaction_id', '=', $transactionId)
                                                 ->get();
        if ($receiverOrganization) {
            $narratives            = fetchNarratives($receiverOrganization[0]->id, 'iati_transaction/receiver_org/narrative', 'receiver_org_id');
            $narrativeData         = fetchAnyNarratives($narratives);
            $receiverOrgRef        = $receiverOrganization[0]->ref;
            $receiverOrgActivityId = $receiverOrganization[0]->providerActivityId;
        }

        return ['organization_identifier_code' => $receiverOrgRef, 'receiver_activity_id' => $receiverOrgActivityId, 'narrative' => $narrativeData];
    }


    /**
     * get transaction sector data
     * @param $transactionId
     * @return array
     */
    protected function getTransactionSector($transactionId)
    {
        $vocabCode     = $sectorCode = $sectorCategoryCode = $sectorText = '';
        $narrativeData = [['narrative' => '', 'language' => '']];
        $sector        = $this->connection->table('iati_transaction/sector')
                                          ->select('@code as code', '@vocabulary as vocabulary', 'id')
                                          ->where('transaction_id', '=', $transactionId)
                                          ->get();
        if ($sector) {
            $narratives    = fetchNarratives($sector[0]->id, 'iati_transaction/sector/narrative', 'sector_id');
            $narrativeData = fetchAnyNarratives($narratives);

            $vocabCode = fetchCode($sector[0]->vocabulary, 'SectorVocabulary', $transactionId);
            if ($vocabCode == "1") {
                $sectorCode = fetchCode($sector[0]->code, 'Sector', $transactionId);
            } elseif ($vocabCode == "2") {
                $sectorCategoryCode = fetchCode($sector[0]->code, 'SectorDacThree', $transactionId);
            } else {
                $sectorText = $sector[0]->code;
            }
        }

        return [
            'sector_vocabulary'    => $vocabCode,
            'sector_code'          => $sectorCode,
            'sector_category_code' => $sectorCategoryCode,
            'sector_text'          => $sectorText,
            'narrative'            => $narrativeData
        ];
    }

    /**
     * get transaction recipient country data
     * @param $transactionId
     * @return array
     */
    protected function getTransactionRecipientCountry($transactionId)
    {
        $recipientCountry = fetchDataWithCodeFrom('iati_transaction/recipient_country', 'transaction_id', $transactionId);
        $countryCode      = '';
        $narrativeData    = [['narrative' => '', 'language' => '']];

        if ($recipientCountry) {
            $narratives    = fetchNarratives($recipientCountry[0]->id, 'iati_transaction/recipient_country/narrative', 'recipient_country_id');
            $narrativeData = fetchAnyNarratives($narratives);
            $countryCode   = $recipientCountry[0]->code;
        }

        return ['country_code' => $countryCode, 'narrative' => $narrativeData];
    }

    /**
     * get transaction recipient region data
     * @param $transactionId
     * @return array
     */
    protected function getTransactionRecipientRegion($transactionId)
    {
        $recipientRegion = $this->connection->table('iati_transaction/recipient_region')
                                            ->select('@code as code', '@vocabulary as vocabulary', 'id')
                                            ->where('transaction_id', '=', $transactionId)
                                            ->get();
        $regionCode      = $vocabulary = '';
        $narrativeData   = [['narrative' => '', 'language' => '']];

        if ($recipientRegion) {
            $narratives    = fetchNarratives($recipientRegion[0]->id, 'iati_transaction/recipient_region/narrative', 'recipient_region_id');
            $narrativeData = fetchAnyNarratives($narratives);
            $regionCode    = $recipientRegion[0]->code;
            $vocabulary    = $recipientRegion[0]->vocabulary;
        }

        return ['region_code' => $regionCode, 'vocabulary' => $vocabulary, 'narrative' => $narrativeData];
    }
}
