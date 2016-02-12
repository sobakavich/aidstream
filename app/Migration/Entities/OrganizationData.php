<?php namespace App\Migration\Entities;


use App\Migration\MigrateOrganizationData;

/**
 * Class OrganizationData
 * @package App\Migration\Entities
 */
class OrganizationData
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var MigrateOrganizationData
     */
    protected $organizationData;

    /**
     * OrganizationData constructor.
     * @param MigrateOrganizationData $organizationData
     */
    public function __construct(MigrateOrganizationData $organizationData)
    {
        $this->organizationData = $organizationData;
    }

    /**
     * Gets OrganizationData data from old database.
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        foreach ($accountIds as $accountId) {
            $organization = getOrganizationFor($accountId);

            if ($organization) {
                $this->data[] = $this->organizationData->OrganizationDataFetch($organization->id, $accountId);
            }
        }

        return $this->data;
    }
}
