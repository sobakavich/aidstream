<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class Transaction
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class Transaction extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds Transaction
     */
    public function buildForm()
    {
        $this
            ->add('reference', 'text')
            ->addCollection('transaction_type', 'Activity\Transactions\Type')
            ->addCollection('transaction_date', 'Activity\Transactions\Date')
            ->addCollection('value', 'Activity\Transactions\Value')
            ->addCollection('description', 'Activity\Transactions\Description')
            ->addCollection('provider_organization', 'Activity\Transactions\ProviderOrganization')
            ->addCollection('receiver_organization', 'Activity\Transactions\ReceiverOrganization')
            ->addCollection('disbursement_channel', 'Activity\Transactions\DisbursementChannel')
            ->addCollection('sector', 'Activity\Transactions\Sector')
            ->addCollection('recipient_country', 'Activity\Transactions\RecipientCountry')
            ->addCollection('recipient_region', 'Activity\Transactions\RecipientRegion')
            ->addCollection('flow_type', 'Activity\Transactions\FlowType')
            ->addCollection('finance_type', 'Activity\Transactions\FinanceType')
            ->addCollection('aid_type', 'Activity\Transactions\AidType')
            ->addCollection('Tied_status', 'Activity\Transactions\TiedStatus');
    }
}
