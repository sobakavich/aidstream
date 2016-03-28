<?php namespace App\Services\tz\Manager;

use App\Core\tz\Repositories\TransactionRepository;
use App\Models\Activity\Activity;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;


/**
 * Class TransactionManager
 * @package App\Services\tz\Manager
 */
class TransactionManager
{

    /**
     * @var Guard
     */
    protected $auth;

    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var DbLogger
     */
    protected $dbLogger;

    /**
     * @var TransactionRepository
     */
    protected $transactionRepo;

    /**
     * TransactionManager constructor.
     * @param Guard                 $auth
     * @param DbLogger              $dbLogger
     * @param Logger                $logger
     * @param TransactionRepository $transactionRepo
     */
    public function __construct(Guard $auth, DbLogger $dbLogger, Logger $logger, TransactionRepository $transactionRepo)
    {
        $this->auth            = $auth;
        $this->logger          = $logger;
        $this->dbLogger        = $dbLogger;
        $this->transactionRepo = $transactionRepo;
    }

    /**
     * @param array    $transactionDetails
     * @param Activity $activity
     * @param null     $transactionId
     * @return bool
     */
    public function save(array $transactionDetails, Activity $activity, $transactionId = null)
    {
        try {
            ($transactionId) ? $this->transactionRepo->update($transactionDetails, $transactionId) : $this->transactionRepo->create($transactionDetails, $activity);
            $this->logger->info(($transactionId) ? 'Activity Transaction Updated' : 'Activity Transaction added');
            $dbLoggerData = ['activity_id' => $activity->id];
            (!$transactionId) ?: $dbLoggerData['transaction_id'] = $transactionId;
            $this->dbLogger->activity(($transactionId) ? "activity.transaction_updated" : "activity.transaction_added", $dbLoggerData);

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf(
                    'Transaction could not be %s due to %s',
                    ($transactionId) ? 'updated' : 'added',
                    $exception->getMessage()
                ),
                [
                    'transaction' => $transactionDetails,
                    'trace'       => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $transactionId
     * @return mixed
     */
    public function getTransaction($transactionId)
    {
        return $this->transactionRepo->getTransaction($transactionId);
    }

    /**
     * @param $activityId
     * @param $code
     * @return array
     */
    public function getTransactions($activityId, $code)
    {
        $transactions = $this->transactionRepo->getTransactionTypeData($activityId, $code);

        $data = [];

        foreach ($transactions as $transaction) {
            $id          = $transaction->id;
            $transaction = json_decode($transaction->transaction, true);

            $transactionDetail['id']        = $id;
            $transactionDetail['reference'] = $transaction['reference'];
            $transactionDetail['date']      = $transaction['transaction_date'][0]['date'];
            $transactionDetail['amount']    = $transaction['value'][0]['amount'];
            $transactionDetail['narrative'] = $transaction['description'][0]['narrative'][0]['narrative'];
            $data[]                         = $transactionDetail;
        }

        return $data;
    }


    /**
     * @param          $transactions
     * @param Activity $activity
     * @param          $code
     */
    public function saveTransactions($transactions, Activity $activity, $code)
    {
        foreach ($transactions as $transaction) {
            $id                                                          = $transaction['id'];
            $transaction                                                 = $this->formatData($transaction);
            $transaction['transaction_type'][0]['transaction_type_code'] = $code;
            $this->save($transaction, $activity, $id);
        }
    }


    /**
     * @param $transactionDetail
     * @return mixed
     */
    public function formatData($transactionDetail)
    {
        $dataTemplate                                                       = file_get_contents(app_path('Core/tz/DataTemplates/transaction.json'));
        $transactionTemplate                                                = json_decode($dataTemplate, true);
        $transactionTemplate['reference']                                   = $transactionDetail['reference'];
        $transactionTemplate['transaction_date'][0]['date']                 = $transactionDetail['date'];
        $transactionTemplate['value'][0]['amount']                          = $transactionDetail['amount'];
        $transactionTemplate['description'][0]['narrative'][0]['narrative'] = $transactionDetail['narrative'];

        return $transactionTemplate;
    }
}
