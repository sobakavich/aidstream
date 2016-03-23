<?php namespace App\Core\tz\Repositories;

use App\Models\Organization\OrganizationData;
use App\Models\Settings;
use Illuminate\Session\SessionManager;

class SettingsRepository
{
    /**
     * @var OrganizationData
     */
    protected $organizationData;
    /**
     * @var SessionManager
     */
    protected $sessionManager;
    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @param Settings         $settings
     * @param OrganizationData $organizationData
     * @param SessionManager   $sessionManager
     */
    function __construct(Settings $settings, OrganizationData $organizationData, SessionManager $sessionManager)
    {

        $this->organizationData = $organizationData;
        $this->sessionManager   = $sessionManager;
        $this->settings         = $settings;
    }

    /**
     * @param $organization_id
     * @return mixed
     */
    public function getSettings($organization_id)
    {
        return $this->settings->where('organization_id', $organization_id)->first();
    }

    /**
     * @param $input
     * @param $organization
     * @param $settings
     * @return bool
     */
    public function updateSettings($input, $organization, $settings)
    {
        $organization->reporting_org = $input['reporting_organization_info'];
        $organization->save();

        $settings->registry_info        = $input['registry_info'];
        $settings->default_field_values = $input['default_field_values'];
        $settings->organization_id      = $organization->id;
        $settings->save();

        $this->organizationData->firstOrCreate(['organization_id' => $organization->id,]);
    }
}
