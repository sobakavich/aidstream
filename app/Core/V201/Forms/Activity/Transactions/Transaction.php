<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Transaction as TransactionTrait;

/**
 * Class Transaction
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class Transaction extends BaseForm
{
    use TransactionTrait;
    protected $showFieldErrors = true;

    /**
     * builds Transaction
     */
    public function buildForm()
    {
        $this
            ->add('reference', 'text')
            ->addTransactionType()
            ->addTransactionDate()
            ->addValue()
            ->addDescription()
            ->addProviderOrganization()
            ->addReceiverOrganization()
            ->addDisbursementChannel()
            ->addSector()
            ->addRecipientCountry()
            ->addRecipientRegion()
            ->addFlowType()
            ->addFinanceType()
            ->addAidType()
            ->addTiedStatus();
    }
}
