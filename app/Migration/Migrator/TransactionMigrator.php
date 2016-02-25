<?php namespace App\Migration\Migrator;

use App\Migration\Entities\Activity;
use App\Migration\Entities\ActivityTransactions;
use App\Models\Activity\Transaction as TransactionModel;
use App\Migration\Migrator\Contract\MigratorContract;
use Illuminate\Database\DatabaseManager;


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
        $database = app()->make(DatabaseManager::class);

        $activityDetails = $this->transaction->getData($accountIds);
        $counter = 0;
        $builder = $this->transactionModel->query();

        try {
            foreach ($activityDetails as $details) {
                foreach ($details as $detail) {
                    $builder->insert($detail);

                    $counter++;

                    if ($counter > 500) {
                        $database->commit();
                        $counter = 0;
                    }
                }
            }

            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();

            throw $e;
        }

        return 'Activity Transaction table migrated.';
    }
}