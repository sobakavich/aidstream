<?php namespace App\Http\Controllers\tz;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\OtherIdentifierManager;
use App\Services\RequestManager\Organization\SettingsRequestManager;
use App;
use App\Services\tz\Manager\SettingsManager;
use App\Services\Organization\OrganizationManager;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Services\tz\FormCreator\SettingsFormCreator as FormBuilder;

/**
 * Class SettingsController
 * @package App\Http\Controllers\tz
 */
class SettingsController extends Controller
{

    /**
     * @var SettingsManager
     */
    protected $settingsManager;
    /**
     * @var mixed
     */
    protected $settings;
    /**
     * @var App\Services\Organization\model
     */
    protected $organization;
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var OtherIdentifierManager
     */
    protected $otherIdentifierManager;

    /**
     * @param SettingsManager        $settingsManager
     * @param OrganizationManager    $organizationManager
     * @param ActivityManager        $activityManager
     * @param OtherIdentifierManager $otherIdentifierManager
     */
    function __construct(
        SettingsManager $settingsManager,
        OrganizationManager $organizationManager,
        ActivityManager $activityManager,
        OtherIdentifierManager $otherIdentifierManager
    ) {
        $this->middleware('auth');
        $this->settingsManager        = $settingsManager;
        $org_id                       = Session::get('org_id');
        $this->settings               = $settingsManager->getSettings($org_id);
        $this->organization           = $organizationManager->getOrganization($org_id);
        $this->activityManager        = $activityManager;
        $this->otherIdentifierManager = $otherIdentifierManager;
    }

    /**
     * Display settings
     *
     * @param FormBuilder     $formBuilder
     * @param DatabaseManager $databaseManager
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(FormBuilder $formBuilder, DatabaseManager $databaseManager)
    {
        $formOptions = $this->settingsManager->getSettingsOfOrganization($databaseManager);
        $form        = $formBuilder->editForm($formOptions);

        return view('tz.settings', compact('form'));
    }

    /**
     * Update settings
     *
     * @param  int                   $id
     * @param SettingsRequestManager $request
     */
    public function update($id, SettingsRequestManager $request)
    {
        $input = Input::all();

        $oldIdentifier          = $this->organization->reporting_org[0]['reporting_organization_identifier'];
        $settings               = $this->settingsManager->getSettings($this->organization->id);
        $activities             = $this->activityManager->getActivities($this->organization->id);
        $reportingOrgIdentifier = $input['reporting_organization_info'][0]['reporting_organization_identifier'];
        foreach ($activities as $activity) {
            $status          = $activity['published_to_registry'];
            $otherIdentifier = (array)$activity->other_identifier;
            if ($status == 1 && !in_array(["reference" => $oldIdentifier, "type" => "B1", 'owner_org' => []], $otherIdentifier) && ($oldIdentifier !== $reportingOrgIdentifier)) {
                $otherIdentifier[] = ['reference' => $oldIdentifier, 'type' => 'B1', 'owner_org' => []];
                $this->otherIdentifierManager->update(['other_identifier' => $otherIdentifier], $activity);
            }
        }
        $result = $this->settingsManager->updateSettings($input, $this->organization, $this->settings);
        if (!$result) {
            $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Settings']]];

            return redirect()->back()->withResponse($response);
        }
        $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Settings']]];

        return redirect()->to(config('app.admin_dashboard'))->withResponse($response);
    }

}
