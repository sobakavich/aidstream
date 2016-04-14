<?php namespace App\Migration\Migrator\Data;


use App\Migration\ActivityData;


class ActivityPublishedQuery extends Query
{

    public function __construct(ActivityData $activityData)
    {
        $this->activityData = $activityData;
    }

    public function executeFor(array $accountIds)
    {
        $this->initDBConnection();
        $data    = [];
        $counter = 1;

        foreach ($accountIds as $accountId) {
            if (is_null(getOrganizationFor($accountId))) {
                $data[] = $this->getData($accountId, $counter);
            }
        }

        return $data;
    }

    public function getData($accountId, &$counter)
    {
        $activityPublished = [];

        //fetch published activity
        $activityPublishedData = $this->connection->table('published')
                                                  ->select('*')
                                                  ->where('publishing_org_id', '=', $accountId)
                                                  ->get();

        foreach ($activityPublishedData as $data) {
            $activityPublished[$data->filename] = [
                'id'                    => getLatestSequence('activity_published')->index + $counter,
                'filename'              => $data->filename,
                'published_to_register' => $data->pushed_to_registry,
                'organization_id'       => $accountId,
                'created_at'            => $data->published_date,
                'updated_at'            => $data->published_date,
            ];

            $counter++;
        }

        return $activityPublished;
    }


}