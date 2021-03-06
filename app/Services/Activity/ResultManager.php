<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\ActivityResult;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Illuminate\Database\Eloquent\Collection;
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
     * Update Activity Result
     * @param array          $resultData
     * @param ActivityResult $activityResult
     * @return bool
     */
    public function update(array $resultData, ActivityResult $activityResult)
    {
        try {
            $this->database->beginTransaction();
            $resultExists = $activityResult->exists;
            $this->resultRepo->update($resultData, $activityResult);
            $this->database->commit();
            $this->logger->info(
                sprintf('Activity Result %s!', $resultExists ? 'updated' : 'saved'),
                ['for' => $activityResult->result]
            );
            $this->dbLogger->activity(
                sprintf("activity.result_%s", $resultExists ? 'updated' : 'saved'),
                [
                    'activity_id'     => $activityResult->activity_id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error($exception, ['result' => $resultData]);
        }

        return false;
    }

    /**
     * @param $activityId
     * @return Collection
     */
    public function getResults($activityId)
    {
        return $this->resultRepo->getResults($activityId);
    }

    /**
     * @param $id
     * @param $activityId
     * @return ActivityResult
     */
    public function getResult($id, $activityId)
    {
        return $this->resultRepo->getResult($id, $activityId);
    }

    /**
     * @param ActivityResult $activityResult
     * @return bool
     */
    public function deleteResult(ActivityResult $activityResult)
    {
        try {
            $this->resultRepo->deleteResult($activityResult);

            $this->dbLogger->activity(
                "activity.activity_result_deleted",
                [
                    'result_id'   => $activityResult->id,
                    'activity_id' => $activityResult->activity_id
                ],
                $activityResult->toArray()
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception, ['result' => $activityResult]);
        }

        return false;
    }
}
