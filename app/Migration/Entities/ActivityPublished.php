<?php namespace App\Migration\Entities;

use App\Migration\Migrator\Data\ActivityPublishedQuery;

class ActivityPublished 
{

    /**
     * @var ActivityPublishedQuery
     */
    protected $activityPublishedQuery;

    /**
     * Document constructor.
     * @param MigrateActivityPublished
     * $document
     */
    public function __construct(ActivityPublishedQuery $activityPublishedQuery)
    {
       $this->activityPublishedQuery = $activityPublishedQuery;
    }

    /**
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        return $this->activityPublishedQuery->executeFor($accountIds);
    }













}



