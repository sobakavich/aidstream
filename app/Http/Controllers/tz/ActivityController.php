<?php namespace App\Http\Controllers\tz;

use App\Core\tz\Requests\Activity as ActivityRequest;
use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationManager;
use App\Services\SettingsManager;
use App\Services\tz\FormCreator\Activity;
use App\Services\tz\Manager\Activity as ActivityManager;

/**
 * Class ActivityController
 * @package app\Http\Controllers\Complete\Activity
 */
class ActivityController extends Controller
{
    /**
     * @var OrganizationManager
     */
    private $organizationManager;
    /**
     * @var SettingsManager
     */
    private $settingsManager;
    /**
     * @var Activity
     */
    private $activityForm;
    /**
     * @var ActivityManager
     */
    private $activityManager;

    /**
     * @param OrganizationManager $organizationManager
     * @param SettingsManager     $settingsManager
     * @param Activity            $activityForm
     * @param ActivityManager     $activityManager
     */
    function __construct(OrganizationManager $organizationManager, SettingsManager $settingsManager, Activity $activityForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->organizationManager = $organizationManager;
        $this->settingsManager     = $settingsManager;
        $this->orgId               = session('org_id');
        $this->activityForm        = $activityForm;
        $this->activityManager     = $activityManager;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('add_activity');
        $organization          = $this->organizationManager->getOrganization($this->orgId);
        $form                  = $this->activityForm->create();
        $settings              = $this->settingsManager->getSettings($this->orgId);
        $reportingOrganization = $organization->reporting_org;

        if (!isset($reportingOrganization[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'activity']]];

            return redirect('/settings')->withResponse($response);
        }

        $identifier = $reportingOrganization[0]['reporting_organization_identifier'];

        return view('tz.Activity.create', compact('form', 'identifier'));
    }

    public function store(ActivityRequest $request)
    {
        $this->authorize('add_activity');
        $settings                         = $this->settingsManager->getSettings($this->orgId);
        $defaultFieldValues               = $settings->default_field_values;
        $activity                         = $request->get('activity')[0];
        $activity['default_field_values'] = $defaultFieldValues;
        $result                           = $this->activityManager->store($activity, $this->orgId);

        if (!$result) {
            $response = ['type' => 'danger', 'code' => ['save_failed', ['name' => 'activity']]];

            return redirect()->back()->withResponse($response);
        }

        $response = ['type' => 'success', 'code' => ['created', ['name' => 'Activity']]];

        return redirect()->route('activity.show', [$result->id])->withResponse($response);

    }

    /**
     * display activity view
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $activityData = $this->activityManager->getActivityData($id);
        $activity     = $this->activityManager->getActivity($id);

        return view('tz.Activity.show', compact('activityData', 'activity', 'id'));
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $this->authorize('edit_activity');
        $activity              = $this->activityManager->getActivity($id);
        $form                  = $this->activityForm->edit($activity, $id);
        $organization          = $this->organizationManager->getOrganization($this->orgId);
        $reportingOrganization = $organization->reporting_org;

        $identifier = $reportingOrganization[0]['reporting_organization_identifier'];

        return view('tz.Activity.create', compact('form', 'identifier'));
    }

    public function update($id, ActivityRequest $request)
    {
        $this->authorize('edit_activity');
        $activity     = $request->get('activity')[0];
        $activityData = $this->activityManager->getActivityData($id);
        $result       = $this->activityManager->update($activity, $activityData);

        if (!$result) {
            $response = ['type' => 'danger', 'code' => ['save_failed', ['name' => 'activity']]];

            return redirect()->back()->withResponse($response);
        }

        $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Activity']]];

        return redirect()->route('activity.show', [$id])->withResponse($response);

    }
}
