<?php namespace App\Migration\Entities;


use App\Migration\Migrator\Data\OrganizationPublishedQuery;

class OrganizationPublished
{
    protected $organisationPublished;

    /**
     * @var OrganizationPublishedQuery
     */
    protected $organisationPublishedQuery;

    /**
     * OrganizationPublished constructor.
     * @param OrganizationPublished
     * OrganizationPublished
     */
    public function __construct(OrganizationPublishedQuery $organisationPublishedQuery)
    {
        $this->organisationPublishedQuery = $organisationPublishedQuery;
    }

    /**
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        return $this->organisationPublishedQuery->executeFor($accountIds);
    }

}