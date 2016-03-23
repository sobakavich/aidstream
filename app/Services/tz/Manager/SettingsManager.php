<?php namespace App\Services\tz\Manager;

use App;
use App\Core\tz\Repositories\SettingsRepository;
use App\Services\Organization\OrganizationManager;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Auth\Guard;
use Psr\Log\LoggerInterface;
use Illuminate\Contracts\Logging\Log;
use Session;

/**
 * Class SettingsManager
 * @package App\Services\tz
 */
class SettingsManager
{

    /**
     * settings repository
     */
    protected $repo;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;
    /**
     * @var Log
     */
    protected $dbLogger;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var DatabaseManager
     */
    protected $dbManager;
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * @var App\Services\Organization\model
     */
    protected $organization;
    /**
     * @var mixed
     */
    protected $settings;
    protected $org_id;


    /**
     * SettingsManager constructor.
     * @param SettingsRepository  $settingsRepo
     * @param OrganizationManager $organizationManager
     * @param Guard               $auth
     * @param Log                 $dbLogger
     * @param LoggerInterface     $logger
     * @param DatabaseManager     $dbManager
     */
    function __construct(
        SettingsRepository $settingsRepo,
        OrganizationManager $organizationManager,
        Guard $auth,
        Log $dbLogger,
        LoggerInterface $logger,
        DatabaseManager $dbManager
    ) {
        $this->repo                = $settingsRepo;
        $this->organizationManager = $organizationManager;
        $this->dbLogger            = $dbLogger;
        $this->logger              = $logger;
        $this->auth                = $auth;
        $this->org_id              = Session::get('org_id');
        $this->settings            = $this->getSettings($this->org_id);
        $this->organization        = $organizationManager->getOrganization($this->org_id);
        $this->dbManager           = $dbManager;
    }

    /**
     * return settings
     * @param $id
     * @return mixed
     */
    public function getSettings($id)
    {
        return $this->repo->getSettings($id);
    }


    /**
     * @param $databaseManager
     * @return array
     */
    public function getSettingsOfOrganization($databaseManager)
    {
        $model                         = [];
        $model['registry_info']        = $this->settings->registry_info;
        $model['default_field_values'] = $this->settings->default_field_values;
        if (isset($this->organization)) {
            $model['reporting_organization_info'] = $this->organization->reporting_org;
        };

        return $model;
    }

    /**
     * update settings
     * @param $input
     * @param $organization
     * @param $settings
     * @return bool
     */
    public function updateSettings($input, $organization, $settings)
    {
        try {
            $this->dbManager->beginTransaction();
            $this->repo->updateSettings($input, $organization, $settings);
            $this->logger->info('Settings Updated Successfully.');
            $this->dbLogger->activity(
                "activity.settings_updated",
                [
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );
            $this->dbManager->commit();

            return true;
        } catch (Exception $e) {
            $this->dbManager->rollback();
            $this->logger->error(
                sprintf('Settings could no be updated due to %s', $e->getMessage()),
                [
                    'settings' => $input,
                    'trace'    => $e->getTraceAsString()
                ]
            );
        }

        return false;
    }

}
