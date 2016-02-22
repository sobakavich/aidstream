<?php namespace App\Migration\Migrator;

use App\Migration\Entities\Activity;
use App\Migration\Entities\ActivityTransactions;
use App\Models\Activity\Transaction as TransactionModel;
use App\Migration\Migrator\Contract\MigratorContract;


/**
 * Class TransactionMigrator
 * @package App\Migration\Migrator
 */
class TransactionMigrator implements MigratorContract
{
    /**
     * @var ActivityTransactions
     */
    protected $transaction;

    /**
     * @var TransactionModel
     */
    protected $transactionModel;

    /**
     * TransactionMigrator constructor.
     * @param ActivityTransactions $transaction
     * @param TransactionModel     $transactionModel
     */
    public function __construct(ActivityTransactions $transaction, TransactionModel $transactionModel)
    {
        $this->transaction      = $transaction;
        $this->transactionModel = $transactionModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(array $accountIds)
    {
        $activityDetails = $this->transaction->getData($accountIds);

        foreach ($activityDetails as $details) {
            foreach ($details as $detail) {
                $this->transactionModel->query()->insert($detail);
            }
        }

        return 'Activity Transaction table migrated.';
    }
}
