<?php namespace App\Services\Import\Validators\Transaction;

use App\Core\V201\Traits\GetCodes;
use App\Services\Activity\TransactionManager;
use Illuminate\Support\Facades\Validator;

class DetailedTransactionValidator
{
    use GetCodes;

    protected $transactionManager;

    public function __construct(TransactionManager $transactionManager)
    {
        $this->transactionManager = $transactionManager;
    }

    public function validate($row, $activityId)
    {
        $transactionTypeCodes           = implode(',', $this->getCodes('TransactionType', 'Activity'));
        $disbursementChannelCodes       = implode(',', $this->getCodes('DisbursementChannel', 'Activity'));
        $sectorVocabularyCodes          = implode(',', $this->getCodes('SectorVocabulary', 'Activity'));
        $recipientCountryCodes          = implode(',', $this->getCodes('Country', 'Organization'));
        $recipientRegionCodes           = implode(',', $this->getCodes('Region', 'Activity'));
        $recipientRegionVocabularyCodes = implode(',', $this->getCodes('RegionVocabulary', 'Activity'));
        $flowTypeCodes                  = implode(',', $this->getCodes('FlowType', 'Activity'));
        $financeTypeCodes               = implode(',', $this->getCodes('FinanceType', 'Activity'));
        $aidTypeCodes                   = implode(',', $this->getCodes('AidType', 'Activity'));
        $tiedStatusCodes                = implode(',', $this->getCodes('TiedStatus', 'Activity'));
        $existingReferences             = implode(',', $this->getExistingReferences($activityId));

        $rules = [];

        $rules = array_merge(
            $rules,
            [
                "transaction_ref"             => 'not_in:' . $existingReferences,
                "transactiontype_code"        => 'required|in:' . $transactionTypeCodes,
                "transactiondate_iso_date"    => 'required|date',
                "transactionvalue_value_date" => 'required|date',
                "transactionvalue_text"       => 'required|numeric',
                "description_text"            => 'required',
                "disbursementchannel_code"    => 'in:' . $disbursementChannelCodes,
                "sector_vocabulary"           => 'in:' . $sectorVocabularyCodes,
                "recipientcountry_code"       => 'in:' . $recipientCountryCodes,
                "recipientregion_code"        => 'in:' . $recipientRegionCodes,
                "recipientregion_vocabulary"  => 'in:' . $recipientRegionVocabularyCodes,
                "flowtype_code"               => 'in:' . $flowTypeCodes,
                "financetype_code"            => 'in:' . $financeTypeCodes,
                "aidtype_code"                => 'in:' . $aidTypeCodes,
                "tiedstatus_code"             => 'in:' . $tiedStatusCodes,
            ]
        );

        $messages = [];

        $messages = array_merge(
            $messages,
            [
//                "transaction_ref.required"             => sprintf('At row Transaction-ref is required'),
                "transaction_ref.unique_validation"    => sprintf('At row Transaction-ref should be unique'),
                "transactiontype_code.required"        => sprintf('At row TransactionType-code is required'),
                "transactiontype_code.in"              => sprintf('At row TransactionType-code is invalid'),
                "transactiondate_iso_date.required"    => sprintf('At row TransactionDate-iso_date is required'),
                "transactiondate_iso_date.date"        => sprintf('At row TransactionDate-iso_date is invalid'),
                "transactionvalue_value_date.required" => sprintf('At row TransactionValue-value_date is required'),
                "transactionvalue_value_date.date"     => sprintf('At row TransactionValue-value_date is invalid'),
                "transactionvalue_text.required"       => sprintf('At row TransactionValue-text is required'),
                "transactionvalue_text.numeric"        => sprintf('At row TransactionValue-text should ne numeric'),
                "description_text.required"            => sprintf('At row Description-text is required'),
                "disbursementchannel_code.in"          => sprintf('At row DisbursementChannel-code is invalid'),
                "sector_vocabulary.in"                 => sprintf('At row Sector-vocabulary is invalid'),
                "recipientcountry_code.in"             => sprintf('At row RecipientCountry-code is invalid'),
                "recipientregion_code.in"              => sprintf('At row RecipientRegion-code is invalid'),
                "recipientregion_vocabulary.in"        => sprintf('At row RecipientRegion-code is invalid'),
                "flowtype_code.in"                     => sprintf('At row FlowType-code is invalid'),
                "financetype_code.in"                  => sprintf('At row FinanceType-code is invalid'),
                "aidtype_code.in"                      => sprintf('At row AidType-code is invalid'),
                "tiedstatus_code.in"                   => sprintf('At row TiedStatus-code is invalid')
            ]
        );

        $sectorVocabulary = $row['sector_vocabulary'];
        if ($sectorVocabulary == 1) {
            $sectorCodes                = implode(',', $this->getCodes('Sector', 'Activity'));
            $rules["sector_code"]       = 'in:' . $sectorCodes;
            $messages["sector_code.in"] = sprintf('At row %s 5 digit Sector-code is invalid');
        } elseif ($sectorVocabulary == 2) {
            $sectorCodes                = implode(',', $this->getCodes('SectorCategory', 'Activity'));
            $rules["sector_code"]       = 'in:' . $sectorCodes;
            $messages["sector_code.in"] = sprintf('At row %s 3 digit Sector-code is invalid');
        }

        return Validator::make($row->toArray(), $rules, $messages);
    }

    protected function getExistingReferences($activityId)
    {
        $transactions = $this->transactionManager->getTransactions($activityId);
        $references   = [];

        foreach ($transactions as $transaction) {
            $references[] = $transaction->transaction['reference'];
        }

        return $references;
    }
}
