<?php namespace App\Http\Controllers\Complete\Activity;

use App\Core\V201\Requests\Activity\IatiIdentifierRequest;
use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationManager;
use App\Services\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Activity\Identifier;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ActivityController
 * @package app\Http\Controllers\Complete\Activity
 */
class ActivityController extends Controller
{
    protected $identifierForm;
    protected $activityManager;
    protected $organization_id;
    /**
     * @var SettingsManager
     */
    protected $settingsManager;
    /**
     * @var SessionManager
     */
    protected $sessionManager;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * @param SettingsManager     $settingsManager
     * @param SessionManager      $sessionManager
     * @param OrganizationManager $organizationManager
     * @param Identifier          $identifierForm
     * @param ActivityManager     $activityManager
     */
    function __construct(
        SettingsManager $settingsManager,
        SessionManager $sessionManager,
        OrganizationManager $organizationManager,
        Identifier $identifierForm,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');
        $this->settingsManager     = $settingsManager;
        $this->sessionManager      = $sessionManager;
        $this->organizationManager = $organizationManager;
        $this->identifierForm      = $identifierForm;
        $this->activityManager     = $activityManager;
        $this->organization_id     = $this->sessionManager->get('org_id');
    }


    /**
     * write brief description
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $activities = $this->activityManager->getActivities($this->organization_id);

        return view('Activity.index', compact('activities'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('add_activity');
        $form     = $this->identifierForm->create();
        $settings = $this->settingsManager->getSettings($this->organization_id);
        if (!isset($settings)) {
            return redirect('/settings');
        }
        $defaultFieldValues    = $settings->default_field_values;
        $organization          = $this->organizationManager->getOrganization($this->organization_id);
        $reportingOrganization = $organization->reporting_org;

        return view('Activity.create', compact('form', 'organization', 'reportingOrganization', 'defaultFieldValues'));
    }

    /**
     * store the activity identifier
     * @param IatiIdentifierRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(IatiIdentifierRequest $request)
    {
        $input  = $request->all();
        $result = $this->activityManager->store($input, $this->organization_id);
        if (!$result) {
            return redirect()->back();
        }

        return redirect()->route('activity.show', [$result->id]);
    }

    /**
     * show the activity details
     * @param $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $activityData               = $this->activityManager->getActivityData($id);
        $identifier                 = (array) $activityData->identifier;
        $other_identifier           = (array) $activityData->other_identifier;
        $title                      = (array) $activityData->title;
        $description                = (array) $activityData->description;
        $activity_status            = (array) $activityData->activity_status;
        $activity_date              = (array) $activityData->activity_date;
        $contact_info               = (array) $activityData->contact_info;
        $activity_scope             = (array) $activityData->activity_scope;
        $participating_organization = (array) $activityData->participating_organization;
        $recipient_country          = (array) $activityData->recipient_country;
        $recipient_region           = (array) $activityData->recipient_region;
        $location                   = (array) $activityData->location;
        $sector                     = (array) $activityData->sector;
        $country_budget_items       = (array) $activityData->country_budget_items;
        $policy_maker               = (array) $activityData->policy_maker;
        $collaboration_type         = (array) $activityData->collaboration_type;
        $default_flow_type          = (array) $activityData->default_flow_type;
        $default_finance_type       = (array) $activityData->default_finance_type;
        $default_aid_type           = (array) $activityData->default_aid_type;
        $default_tied_status        = (array) $activityData->default_tied_status;
        $budget                     = (array) $activityData->budget;
        $planned_disbursement       = (array) $activityData->planned_disbursement;
        $capital_spend              = (array) $activityData->capital_spend;
        $document_link              = (array) $activityData->document_link;
        $related_activity           = (array) $activityData->related_activity;
        $legacy_data                = (array) $activityData->legacy_data;
        $conditions                 = (array) $activityData->conditions;
        $result                     = (array) $activityData->result;
        $activity_workflow          = $activityData->activity_workflow;
        $organization_id            = $activityData->organization_id;

        return view(
            'Activity.show',
            compact(
                'id',
                'identifier',
                'other_identifier',
                'title',
                'description',
                'activity_status',
                'activity_date',
                'contact_info',
                'activity_scope',
                'participating_organization',
                'recipient_country',
                'recipient_region',
                'location',
                'sector',
                'country_budget_items',
                'policy_maker',
                'collaboration_type',
                'default_flow_type',
                'default_finance_type',
                'default_aid_type',
                'default_tied_status',
                'budget',
                'planned_disbursement',
                'capital_spend',
                'document_link',
                'related_activity',
                'legacy_data',
                'conditions',
                'result',
                'activity_workflow',
                'organization_id'
            )
        );
    }

    /**
     * Throw an unauthorized exception based on gate results.
     *
     * @param  string $ability
     * @param  array  $arguments
     * @return \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function createGateUnauthorizedException($ability, $arguments)
    {
        return new HttpException(403, 'This action is unauthorized.');
    }

    /**
     * @param         $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus($id, Request $request)
    {
        $input        = $request->all();
        $activityData = $this->activityManager->getActivityData($id);

        $this->activityManager->updateStatus($input, $activityData);

        return redirect()->back();
    }
}
