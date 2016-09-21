<?php namespace App\Http\Controllers\TestData\Traits;


trait TransactionData
{
    protected function getTransactionInternalReference($transaction)
    {
        return getVal($transaction, ['reference']);
    }

    protected function getTransactionType($transaction)
    {
        return getVal($transaction, ['transaction_type', 0, 'transaction_type_code']);
    }

    protected function getTransactionDate($transaction)
    {
        return getVal($transaction, ['transaction_date', 0, 'date']);
    }

    protected function getTransactionValue($transaction)
    {
        return getVal($transaction, ['value', 0, 'amount']);
    }

    protected function getTransactionValueDate($transaction)
    {
        return getVal($transaction, ['value', 0, 'date']);
    }

    protected function getTransactionDescription($transaction)
    {
        return getVal($transaction, ['description', 0, 'narrative', 0, 'narrative']);
    }

    protected function getProviderOrganisationIdentifier($transaction)
    {
        return getVal($transaction, ['provider_organization', 0, 'organization_identifier_code']);
    }

    protected function getProviderOrganisationType($transaction)
    {
        return getVal($transaction, ['provider_organization', 0, 'type']);
    }

    protected function getProviderOrganisationActivityIdentifier($transaction)
    {
        return getVal($transaction, ['provider_organization', 0, 'provider_activity_id']);
    }

    protected function getProviderOrganisationDescription($transaction)
    {
        return getVal($transaction, ['provider_organization', 0, 'narrative', 0, 'narrative']);
    }

    protected function getReceiverOrganisationIdentifier($transaction)
    {
        return getVal($transaction, ['receiver_organization', 0, 'organization_identifier_code']);
    }

    protected function getReceiverOrganisationType($transaction)
    {
        return getVal($transaction, ['receiver_organization', 0, 'type']);
    }

    protected function getReceiverOrganisationActivityIdentifier($transaction)
    {
        return getVal($transaction, ['receiver_organization', 0, 'receiver_activity_id']);
    }

    protected function getReceiverOrganisationDescription($transaction)
    {
        return getVal($transaction, ['receiver_organization', 0, 'narrative', 0, 'narrative']);
    }

    protected function getTransactionSectorVocabulary($transaction)
    {
        return getVal($transaction, ['sector', 0, 'sector_vocabulary']);
    }

    protected function getTransactionSectorCode($transaction)
    {
        return getVal($transaction, ['sector', 0, 'sector_code']);
    }

    protected function getTransactionRecipientCountryCode($transaction)
    {
        return getVal($transaction, ['recipient_country', 'country_code']);
    }

    protected function getTransactionRecipientRegionCode($transaction)
    {
        return getVal($transaction, ['recipient_region', 'region_code']);
    }
}