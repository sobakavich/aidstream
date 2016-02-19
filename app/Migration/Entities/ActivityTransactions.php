<?php namespace App\Migration\Entities;

use App\Migration\Migrator\Data\ActivityTransactionsQuery;

/**
 * Class ActivityTransactions
 * @package App\Migration\Entities
 */
class ActivityTransactions
{
    /**
     * @var ActivityTransactionsQuery
     */
    protected $transactionsQuery;

    /**
     * ActivityTransactions constructor.
     * @param ActivityTransactionsQuery $transactionsQuery
     */
    public function __construct(ActivityTransactionsQuery $transactionsQuery)
    {
        $this->transactionsQuery = $transactionsQuery;
    }

    /**
     * Gets Activity transactions data from old database.
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        return $this->transactionsQuery->executeFor($accountIds);
    }
}