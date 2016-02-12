<?php namespace App\Migration\Entities;

use App\Migration\MigrateDocuments;

/**
 * Class Document
 * @package App\Migration\Entities
 */
class Document
{
    /**
     * @var MigrateDocuments
     */
    protected $document;

    /**
     * Document constructor.
     * @param MigrateDocuments $document
     */
    public function __construct(MigrateDocuments $document)
    {
        $this->document = $document;
    }

    /**
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        $docData = [];

        foreach ($accountIds as $accountId) {
            $organization = getOrganizationFor($accountId);

            if ($organization) {
                $docData[]    = $this->document->docDataFetch($organization->id, $accountId);
            }
        }

        return $docData;
    }
}
