<?php namespace App\Migration\Entities;

use App\Migration\Migrator\Data\ActivityResultsQuery;

/**
 * Class ActivityResults
 * @package App\Migration\Entities
 */
class ActivityResults
{
    /**
     * @var ActivityResultsQuery
     */
    protected $resultsQuery;

    /**
     * ActivityResults constructor.
     * @param ActivityResultsQuery $resultsQuery
     */
    public function __construct(ActivityResultsQuery $resultsQuery)
    {
        $this->resultsQuery = $resultsQuery;
    }

    /**
     * Gets Activity results data from old database.
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        return $this->resultsQuery->executeFor($accountIds);
    }
}