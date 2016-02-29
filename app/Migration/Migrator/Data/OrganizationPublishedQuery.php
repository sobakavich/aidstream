<?php namespace App\Migration\Migrator\Data;

use App\Migration\ActivityData;
use Carbon\Carbon;

class OrganizationPublishedQuery extends Query
{
    public function __construct(ActivityData $activityData)
    {
        $this->activityData = $activityData;
    }

    public function executeFor(array $accountIds)
    {
        $this->initDBConnection();
        $data = [];

        foreach ($accountIds as $accountId) {
            if ($organization = getOrganizationFor($accountId)) {
                $data[] = $this->getData($organization->id, $accountId);
            }
        }

        return $data;
    }

    public function getData($organizationId, $accountId)
    {
        $organisationPublished = [];

        //fetch published activity
        $organisationPublishedData = $this->connection->table('organisation_published')
                                                      ->select('*')
                                                      ->where('publishing_org_id', '=', $accountId)
                                                      ->get();

        foreach ($organisationPublishedData as $data) {
            $organisationPublished[$data->filename] = [
                'filename'              => $data->filename,
                'published_to_register' => $data->pushed_to_registry,
                'organization_id'       => $accountId,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now()
            ];
        }

        return $organisationPublished;
    }
}