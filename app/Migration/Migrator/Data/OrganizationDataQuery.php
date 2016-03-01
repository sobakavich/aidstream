<?php namespace App\Migration\Migrator\Data;


use App\Migration\Elements\DocumentLink;
use App\Migration\Elements\OrganizationData\Name;
use Carbon\Carbon;

/**
 * Class OrganizationDataQuery
 * @package App\Migration\Migrator\Data
 */
class OrganizationDataQuery extends Query
{
    /**
     * @var Name
     */
    protected $name;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var DocumentLink
     */
    protected $documentLink;

    /**
     * OrganizationDataQuery constructor.
     * @param Name         $name
     * @param DocumentLink $documentLink
     */
    public function __construct(Name $name, DocumentLink $documentLink)
    {
        $this->name         = $name;
        $this->documentLink = $documentLink;
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
                $data[] = $this->getData($organization->id, $accountId);
            }
        }

        return $data;
    }

    /**
     * @param $organizationId
     * @param $accountId
     * @return array
     */
    protected function getData($organizationId, $accountId)
    {
        $this->data = [];
        $this->fetchName($organizationId, $accountId)
             ->fetchStatus($organizationId, $accountId)
             ->fetchTotalBudget($organizationId)
             ->fetchRecipientOrgBudget($organizationId)
             ->fetchRecipientCountryBudget($organizationId)
             ->fetchDocumentLink($organizationId);

        return $this->data;
    }

    /**
     * @param $organizationId
     * @param $accountId
     * @return $this
     */
    protected function fetchName($organizationId, $accountId)
    {
        $dataName           = [];
        $fetchNameInstances = $this->connection->table('iati_organisation/name')
                                               ->select('*')
                                               ->where('organisation_id', '=', $organizationId)
                                               ->get();

        foreach ($fetchNameInstances as $eachName) {
            $id             = $eachName->id;
            $nameNarratives = fetchNarratives($id, 'iati_organisation/name/narrative', 'name_id');

            $dataName[] = $this->name->format($nameNarratives);
        }

        $timestamp = ($org = $this->connection->table('iati_organisation')
                                              ->select('@last_updated_datetime as time')
                                              ->where('account_id', '=', $accountId)
                                              ->first()) ? $org->time : '';

        $this->data[$organizationId]['name']            = json_encode($dataName);
        $this->data[$organizationId]['organization_id'] = (int) $accountId;
        $this->data[$organizationId]['created_at']      = $timestamp;
        $this->data[$organizationId]['updated_at']      = $timestamp;

        return $this;
    }

    /**
     * @param $organizationId
     * @param $accountId
     * @return $this
     */
    protected function fetchStatus($organizationId, $accountId)
    {
        $status       = 0;
        $fetchStateId = getBuilderFor('state_id', 'iati_organisation', 'account_id', $accountId)->first();

        if (!is_null($fetchStateId)) {
            $status = $fetchStateId->state_id - 1;
        }

        $this->data[$organizationId]['status'] = $status;

        return $this;
    }

    /**
     * @param $organizationId
     * @return $this
     */
    protected function fetchTotalBudget($organizationId)
    {
        $table           = 'iati_organisation/total_budget';
        $totalBudgets    = getBuilderFor('id', $table, 'organisation_id', $organizationId)->get();
        $totalBudgetData = [];

        foreach ($totalBudgets as $totalBudget) {
            $totalBudgetId = $totalBudget->id;
            $childColumn   = 'total_budget_id';
            $periodStart   = fetchPeriodStart($table, $childColumn, $totalBudgetId);
            $periodEnd     = fetchPeriodEnd($table, $childColumn, $totalBudgetId);
            $value         = fetchValue($table, $childColumn, $totalBudgetId);
            $budgetLine    = fetchBudgetLine($table, $childColumn, $totalBudgetId);

            $totalBudgetData[] = [
                'period_start' => $periodStart,
                'period_end'   => $periodEnd,
                'value'        => $value,
                'budget_line'  => $budgetLine
            ];
        }
        $this->data[$organizationId]['total_budget'] = json_encode($totalBudgetData);

        return $this;
    }

    /**
     * @param $organizationId
     * @return array
     */
    protected function fetchDocumentLink($organizationId)
    {
        $documentLinks    = $this->connection->table('iati_organisation/document_link')
                                             ->select('*', '@url as url', '@format as format')
                                             ->where('organisation_id', '=', $organizationId)
                                             ->get();
        $documentLinkData = null;

        foreach ($documentLinks as $documentLink) {
            $documentLinkId = $documentLink->id;
            $url            = $documentLink->url;
            $format         = fetchCode($documentLink->format, 'FileFormat', '');

            $categories         = fetchDataWithCodeFrom('iati_organisation/document_link/category', 'document_link_id', $documentLinkId);
            $languages          = fetchDataWithCodeFrom('iati_organisation/document_link/language', 'document_link_id', $documentLinkId);
            $recipientCountries = fetchDataWithCodeFrom('iati_organisation/document_link/recipient_country', 'document_link_id', $documentLinkId);
            $titleNarratives    = getBuilderFor('id', 'iati_organisation/document_link/title', 'document_link_id', $documentLinkId)->first();

            $documentLinkData[] = $this->documentLink->format($url, $format, $titleNarratives, $categories, $languages, $recipientCountries);
        }

        $this->data[$organizationId]['document_link'] = json_encode($documentLinkData);

        return $this;
    }

    /**
     * @param $organizationId
     * @return $this
     */
    protected function fetchRecipientOrgBudget($organizationId)
    {
        $table                  = 'iati_organisation/recipient_org_budget';
        $recipientOrgBudgets    = getBuilderFor('id', $table, 'organisation_id', $organizationId)->get();
        $recipientOrgBudgetData = [];

        foreach ($recipientOrgBudgets as $recipientOrgBudget) {
            $recipientOrgBudgetId = $recipientOrgBudget->id;
            $childColumn          = 'recipient_org_budget_id';
            $recipientOrg         = $this->fetchRecipientOrg($table, $childColumn, $recipientOrgBudgetId);
            $periodStart          = fetchPeriodStart($table, $childColumn, $recipientOrgBudgetId);
            $periodEnd            = fetchPeriodEnd($table, $childColumn, $recipientOrgBudgetId);
            $value                = fetchValue($table, $childColumn, $recipientOrgBudgetId);
            $budgetLine           = fetchBudgetLine($table, $childColumn, $recipientOrgBudgetId);

            $recipientOrgBudgetData[] = [
                'recipient_organization' => $recipientOrg,
                'period_start'           => $periodStart,
                'period_end'             => $periodEnd,
                'value'                  => $value,
                'budget_line'            => $budgetLine
            ];
        }

        $this->data[$organizationId]['recipient_organization_budget'] = json_encode($recipientOrgBudgetData);

        return $this;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $parentId
     * @return array
     */
    protected function fetchRecipientOrg($parentTable, $column, $parentId)
    {
        $table        = $parentTable . '/recipient_org';
        $recipientOrg = getBuilderFor('@ref as ref', $table, $column, $parentId)->first();
        $narrative    = fetchNarrative($table, 'recipient_org_id', $parentId, '/nar');
        $recipientOrg = [["ref" => $recipientOrg->ref, 'narrative' => $narrative]];

        return $recipientOrg;
    }

    /**
     * @param $organizationId
     * @return $this
     */
    protected function fetchRecipientCountryBudget($organizationId)
    {
        $table                      = 'iati_organisation/recipient_country_budget';
        $recipientCountryBudgets    = getBuilderFor('id', $table, 'organisation_id', $organizationId)->get();
        $recipientCountryBudgetData = [];

        foreach ($recipientCountryBudgets as $recipientCountryBudget) {
            $recipientCountryBudgetId = $recipientCountryBudget->id;
            $childColumn              = 'recipient_country_budget_id';
            $recipientCountry         = $this->fetchRecipientCountry($table, $childColumn, $recipientCountryBudgetId);
            $periodStart              = fetchPeriodStart($table, $childColumn, $recipientCountryBudgetId);
            $periodEnd                = fetchPeriodEnd($table, $childColumn, $recipientCountryBudgetId);
            $value                    = fetchValue($table, $childColumn, $recipientCountryBudgetId);
            $budgetLine               = fetchBudgetLine($table, $childColumn, $recipientCountryBudgetId);

            $recipientCountryBudgetData[] = [
                'recipient_country' => $recipientCountry,
                'period_start'      => $periodStart,
                'period_end'        => $periodEnd,
                'value'             => $value,
                'budget_line'       => $budgetLine
            ];
        }

        $this->data[$organizationId]['recipient_country_budget'] = json_encode($recipientCountryBudgetData);
        $this->data[$organizationId]['created_at']               = Carbon::now();
        $this->data[$organizationId]['updated_at']               = Carbon::now();

        return $this;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $parentId
     * @return array
     */
    protected function fetchRecipientCountry($parentTable, $column, $parentId)
    {
        $table        = $parentTable . '/recipient_country';
        $recipientOrg = getBuilderFor('@code as code', $table, $column, $parentId)->first();
        $narrative    = fetchNarrative($table, 'recipient_country_id', $parentId, '/nar');
        $recipientOrg = [["code" => fetchCode($recipientOrg->code, 'Country'), 'narrative' => $narrative]];

        return $recipientOrg;
    }
}
