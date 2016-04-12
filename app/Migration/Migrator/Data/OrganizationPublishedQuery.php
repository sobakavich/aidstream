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
            if (is_null(getOrganizationFor($accountId))) {
                $data[] = $this->getData($accountId);
            }
        }

        return $data;
    }

    public function getData($accountId)
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
                'created_at'            => $data->published_date,
                'updated_at'            => $data->published_date
            ];
        }

        return $organisationPublished;
    }
}