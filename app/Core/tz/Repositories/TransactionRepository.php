<?php namespace App\Core\tz\Repositories;

use App\Models\Activity\Activity;
use App\Models\Activity\Transaction as TransactionModel;
use DB;

/**
 * Class TransactionRepository
 * @package App\Core\tz\Repositories
 */
class TransactionRepository
{

    /**
     * @var TransactionModel
     */
    protected $transaction;

    /**
     * TransactionRepository constructor.
     * @param TransactionModel $transaction
     */
    function __construct(TransactionModel $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param array    $transactionDetails
     * @param Activity $activity
     */
    public function create(array $transactionDetails, Activity $activity)
    {
        $transaction = $this->transaction->newInstance(['transaction' => $transactionDetails]);
        $activity->transactions()->save($transaction);
    }

    /**
     * @param array $transactionDetails
     * @param       $transactionId
     */
    public function update(array $transactionDetails, $transactionId)
    {
        $transactions              = $this->getTransaction($transactionId);
        $transactions->transaction = $transactionDetails;
        $transactions->save();
    }

    /**
     * @param $transactionId
     * @return mixed
     */
    public function getTransaction($transactionId)
    {
        return $this->transaction->findOrFail($transactionId);
    }

    /**
     * @param $activityId
     * @return mixed
     */
    public function getTransactionData($activityId)
    {
        return $this->transaction->where('activity_id', $activityId)->get();
    }

    /**
     * @param $activityId
     * @param $code
     * @return mixed
     */
    public function getTransactionTypeData($activityId, $code)
    {
        $transaction = DB::select("select * from activity_transactions where activity_id = ? and transaction #>> '{transaction_type,0,transaction_type_code}' = ?", [$activityId, $code]);

        return $transaction;
    }
}
