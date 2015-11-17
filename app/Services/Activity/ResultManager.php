<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Activity\ActivityResult;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;

/**
 * Class ResultManager
 * @package App\Services\Activity
 */
class ResultManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Version
     */
    protected $version;
    /**
     * @var DatabaseManager
     */
    protected $database;
    /**
     * @var DbLogger
     */
    protected $dbLogger;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Version         $version
     * @param Guard           $auth
     * @param DatabaseManager $database
     * @param DbLogger        $dbLogger
     * @param Logger          $logger
     */
    public function __construct(Version $version, Guard $auth, DatabaseManager $database, DbLogger $dbLogger, Logger $logger)
    {
        $this->auth       = $auth;
        $this->dbLogger   = $dbLogger;
        $this->database   = $database;
        $this->resultRepo = $version->getActivityElement()->getResult()->getRepository();
        $this->logger     = $logger;
    }

    /**
     * Store Activity Result
     * @param array          $resultData
     * @param ActivityResult $activityResult
     * @return bool
     */
    public function store(array $resultData, ActivityResult $activityResult)
    {
        try {
            $this->database->beginTransaction();
            $this->resultRepo->store($resultData, $activityResult);
            $this->database->commit();
            $this->logger->info('Activity Result created!', ['for' => $activityResult->result]);
            $this->dbLogger->activity(
                "activityResult.result",
                [
                    'result'      => $resultData['result'],
                    'activity_id' => $activityResult->activity_id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(
                sprintf('Activity Result could not be created due to %s', $exception->getMessage()),
                [
                    'result' => $resultData,
                    'trace'  => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * Update Activity Result
     * @param array          $resultData
     * @param ActivityResult $activityResult
     * @return bool
     */
    public function update(array $resultData, ActivityResult $activityResult)
    {
        try {
            $this->database->beginTransaction();
            $this->resultRepo->update($resultData, $activityResult);
            $this->database->commit();
            $this->logger->info('Activity Result updated!', ['for' => $activityResult->result]);
            $this->dbLogger->activity(
                "activityResult.result",
                [
                    'result'      => $resultData['result'],
                    'activity_id' => $activityResult->activity_id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(
                sprintf('Activity Result could not be updated due to %s', $exception->getMessage()),
                [
                    'result' => $resultData,
                    'trace'  => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getResults($activityId)
    {
        return $this->resultRepo->getResults($activityId);
    }

    /**
     * @param $id
     * @return model
     */
    public function getResult($id, $activityId)
    {
        return $this->resultRepo->getResult($id, $activityId);
    }
}
