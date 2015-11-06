<?php namespace App\Core\V201\Traits;

/**
 * Class Transaction
 * @package App\Core\V201\Traits
 */
trait Transaction
{
    /**
     * add transaction type Form
     */
    public function addTransactionType()
    {
        return $this->addCollection('transaction_type', 'Activity\Transactions\Type');
    }

    /**
     * add transaction date form
     */
    public function addTransactionDate()
    {
        return $this->addCollection('transaction_date', 'Activity\Transactions\Date');
    }

    /**
     * add value  form
     */
    public function addValue()
    {
        return $this->addCollection('value', 'Activity\Transactions\Value');
    }

    /**
     * add description form
     * @return mixed
     */
    public function addDescription()
    {
        return $this->addCollection('description', 'Activity\Transactions\Description');
    }

    /**
     * add provider organization form
     * @return mixed
     */
    public function addProviderOrganization()
    {
        return $this->addCollection('provider_organization', 'Activity\Transactions\ProviderOrganization');
    }

    /**
     * add receiver organization form
     * @return mixed
     */
    public function addReceiverOrganization()
    {
        return $this->addCollection('receiver_organization', 'Activity\Transactions\ReceiverOrganization');
    }

    /**
     *  add DisbursementChannel form
     * @return mixed
     */
    public function addDisbursementChannel()
    {
        return $this->addCollection('disbursement_channel', 'Activity\Transactions\DisbursementChannel');
    }

    /**
     * add sector form
     * @return mixed
     */
    public function addSector()
    {
        return $this->addCollection('sector', 'Activity\Transactions\Sector');
    }

    /**
     * add recipient country form
     * @return mixed
     */
    public function addRecipientCountry()
    {
        return $this->addCollection('recipient_country', 'Activity\Transactions\RecipientCountry');
    }

    /**
     * add recipient region form
     * @return mixed
     */
    public function addRecipientRegion()
    {
        return $this->addCollection('recipient_region', 'Activity\Transactions\RecipientRegion');
    }

    /**
     * add flow type form
     * @return mixed
     */
    public function addFlowType()
    {
        return $this->addCollection('flow_type', 'Activity\Transactions\FlowType');
    }

    /**
     * add finance type form
     * @return mixed
     */
    public function addFinanceType()
    {
        return $this->addCollection('finance_type', 'Activity\Transactions\FinanceType');
    }

    /**
     * add aid type form
     * @return mixed
     */
    public function addAidType()
    {
        return $this->addCollection('aid_type', 'Activity\Transactions\AidType');
    }

    /**
     * add tied status form
     * @return mixed
     */
    public function addTiedStatus()
    {
        return $this->addCollection('tied_status', 'Activity\Transactions\TiedStatus');
    }
}
