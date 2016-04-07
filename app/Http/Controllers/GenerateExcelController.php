<?php namespace App\Http\Controllers;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityResult;
use App\Models\Activity\Transaction;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;
use App\Models\UserActivity;
use App\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\DatabaseManager;

class GenerateExcelController extends Controller
{

    /**
     * @var Activity
     */
    protected $activity;
    /**
     * @var Organization
     */
    protected $organization;
    /**
     * @var UserActivity
     */
    protected $userActivity;
    /**
     * @var User
     */
    protected $user;
    protected $mysqlConn;

    function __construct(Activity $activity, Organization $organization, UserActivity $userActivity, User $user)
    {

        $this->activity     = $activity;
        $this->organization = $organization;
        $this->userActivity = $userActivity;
        $this->user         = $user;
    }

    protected function initDBConnection($connection)
    {
        $this->mysqlConn = app()->make(DatabaseManager::class)->connection($connection);
    }

    public function generateActivities()
    {
        Excel::create(
            'Activities',
            function ($excel) {

                $excel->sheet(
                    'Activities',
                    function ($sheet) {

                        $sheet->fromArray($this->getActivityData());

                    }
                );

            }
        )->export('xls');
    }

    protected function getActivityData()
    {
        $organizations = $this->organization->all();

        $data = [];
        foreach ($organizations as $organization) {
            $activities = $organization->activities;
            foreach ($activities as $activity) {
                $activityInfo                      = [];
                $activityInfo['Organization Name'] = $organization->name;
                $activityInfo['Activity ID']       = $activity->id;
                $activityInfo['Activity Title']    = $activity->title[0]['narrative'];
                $data[]                            = $activityInfo;
            }
        }

        return $data;
    }

    public function generateUpdatedActivities()
    {
        Excel::create(
            'UpdatedActivities',
            function ($excel) {

                $excel->sheet(
                    'UpdatedActivities',
                    function ($sheet) {

                        $sheet->fromArray($this->getUpdatedActivityData());

                    }
                );

            }
        )->export('xls');
    }

    protected function getUpdatedActivityData()
    {
        $data           = [];
        $userActivities = \DB::select(
            "select distinct user_id, param->>'activity_id' as activity_id from user_activities where updated_at > '2016-04-05 00:00:00' and  updated_at < '2016-04-06 00:00:00' and (param->'activity_id') is not null"
        );

        $activities = [];
        foreach ($userActivities as $userActivity) {
            $remarks      = [];
            $userId       = $userActivity->user_id;
            $activityId   = $userActivity->activity_id;
            $activity     = $this->activity->find($activityId);
            $user         = $this->user->find($userId);
            $activities[] = $activity;

            $activityInfo                        = [];
            $activityInfo['User']                = $userId;
            $activityInfo['Updated Activity ID'] = $activityId;
            if ($activity) {
                $activityInfo['Organization Name'] = $activity->organization->name;
                $activityInfo['Activity Title']    = $activity->title[0]['narrative'];
                $activityInfo['Updated Date Time'] = $activity->updated_at;

                if ($activity->organization->id != $user->organization->id) {
                    $remarks[] = sprintf('The user belongs to other organization (%s)', $user->organization->name);
                }
            } else {
                $activityInfo['Organization Name'] = '';
                $activityInfo['Activity Title']    = '';
                $activityInfo['Updated Date Time'] = '';
                $remarks[]                         = 'Activity has been deleted.';
            }

            $activityInfo['Remarks'] = implode('; ', $remarks);
            $data[]                  = $activityInfo;
        }

        return $data;
    }

    public function generateOrganizations()
    {
        Excel::create(
            'Organizations',
            function ($excel) {

                $excel->sheet(
                    'Organizations',
                    function ($sheet) {

                        $sheet->fromArray($this->getOrganizationsData());

                    }
                );

            }
        )->export('xls');
    }

    protected function getOrganizationsData()
    {
        $data          = [];
        $organizations = $this->organization->all();

        foreach ($organizations as $organization) {
            $orgData = [];

            $email = $organization->users->count() == 0 ? 'No User' : $organization->users->where('role_id', 1)->first()->email;

            $orgData['Organization Name']        = $organization->name;
            $orgData['Associated Email']         = $email;
            $orgData['No. of Activity']          = $organization->activities->count();
            $orgData['Date of Account Creation'] = $organization->created_at;
            $data[]                              = $orgData;
        }

        return $data;
    }

    public function generateActivitiesWithDocumentLinks()
    {
        Excel::create(
            'DocumentLinks',
            function ($excel) {

                $excel->sheet(
                    'DocumentLinks',
                    function ($sheet) {

                        $sheet->fromArray($this->getDocumentLinks());

                    }
                );

            }
        )->export('xls');
    }

    protected function getDocumentLinks()
    {
        $this->initDBConnection('mysql');
        $data = [];

        $activities = $this->mysqlConn->table('iati_document_link')
                                      ->join('iati_activity', 'iati_document_link.activity_id', '=', 'iati_activity.id')
                                      ->join('iati_identifier', 'iati_identifier.activity_id', '=', 'iati_activity.id')
                                      ->join('iati_activities', 'iati_activity.activities_id', '=', 'iati_activities.id')
                                      ->join('account', 'iati_activities.account_id', '=', 'account.id')
                                      ->select('iati_activity.id as activity_id', 'iati_identifier.activity_identifier', 'account.name as organization_name')
                                      ->groupBy('iati_document_link.activity_id')
                                      ->orderBy('account.name')
                                      ->get();

        foreach ($activities as $activity) {
            $activity_data                        = [];
            $activity_data['Organization Name']   = $activity->organization_name;
            $activity_data['Activity Identifier'] = $activity->activity_identifier;
            $activity_data['Activity ID']         = $activity->activity_id;
            $data[]                               = $activity_data;
        }

        return $data;
    }

    public function generateActivitiesWithMultipleResultIndicators()
    {
        Excel::create(
            'ActivitiesWithIndicators',
            function ($excel) {

                $excel->sheet(
                    'ActivitiesWithIndicators',
                    function ($sheet) {

                        $sheet->fromArray($this->getIndicators());

                    }
                );

            }
        )->export('xls');
    }

    protected function getIndicators()
    {
        $this->initDBConnection('mysql');
        $data = [];

        $activities = $this->mysqlConn->table('iati_result/indicator')
                                      ->join('iati_result', 'iati_result.id', '=', 'iati_result/indicator.result_id')
                                      ->join('iati_activity', 'iati_result.activity_id', '=', 'iati_activity.id')
                                      ->join('iati_identifier', 'iati_identifier.activity_id', '=', 'iati_activity.id')
                                      ->join('iati_activities', 'iati_activity.activities_id', '=', 'iati_activities.id')
                                      ->join('account', 'iati_activities.account_id', '=', 'account.id')
                                      ->select('iati_activity.id as activity_id', 'iati_identifier.activity_identifier', 'account.name as organization_name')
                                      ->groupBy('iati_result.activity_id')
                                      ->orderBy('account.name')
                                      ->get();

        foreach ($activities as $activity) {
            $activity_data                        = [];
            $activity_data['Organization Name']   = $activity->organization_name;
            $activity_data['Activity Identifier'] = $activity->activity_identifier;
            $activity_data['Activity ID']         = $activity->activity_id;
            $data[]                               = $activity_data;
        }

        return $data;
    }

    public function generateComparedIndicators()
    {
        Excel::create(
            'ComparedIndicators',
            function ($excel) {

                $excel->sheet(
                    'ComparedIndicators',
                    function ($sheet) {

                        $sheet->fromArray($this->getComparedIndicators());

                    }
                );

            }
        )->export('xls');
    }

    protected function getComparedIndicators()
    {
        $this->initDBConnection('mysql');
        $data       = [];
        $activities = $this->mysqlConn->table('iati_result/indicator as indicators')
                                      ->join('iati_result', 'iati_result.id', '=', 'indicators.result_id')
                                      ->join('iati_activity', 'iati_result.activity_id', '=', 'iati_activity.id')
                                      ->join('iati_identifier', 'iati_identifier.activity_id', '=', 'iati_activity.id')
                                      ->join('iati_activities', 'iati_activity.activities_id', '=', 'iati_activities.id')
                                      ->join('account', 'iati_activities.account_id', '=', 'account.id')
                                      ->select('iati_activity.id as activity_id', 'iati_identifier.activity_identifier', 'account.name as organization_name', 'iati_result.id as result_id')
                                      ->selectRaw('count(indicators.id) as indicators_count')
                                      ->groupBy('indicators.result_id')
                                      ->orderBy('organization_name')
                                      ->orderBy('activity_id')
                                      ->get();

        $resultModel = app(ActivityResult::class);

        foreach ($activities as $activity) {
            $activity_data                        = [];
            $resultNarrative                      = $this->mysqlConn->table('iati_result/title as result_title')
                                                                    ->join('iati_result/title/narrative as result_narrative', 'result_narrative.title_id', '=', 'result_title.id')
                                                                    ->where('result_title.result_id', '=', $activity->result_id)
                                                                    ->get();
            $activity_data['Organization Name']   = $activity->organization_name;
            $activity_data['Activity Identifier'] = $activity->activity_identifier;
            $activity_data['Activity ID']         = $activity->activity_id;
            $resultTitle                          = '';
            $indicatorCount                       = 'Result Not Found.';
            if ($resultNarrative) {
                $resultTitle = $resultNarrative[0]->text;
                $builder     = $resultModel->query();
                $resultData  = $builder->selectRaw("json_array_length(result -> 'indicator') as indicators_count")->whereRaw(
                    sprintf(
                        "activity_id = $activity->activity_id and result #>> '{title,0,narrative,0,narrative}' = '%s'",
                        pg_escape_string($resultTitle)
                    )
                )->orWhereRaw(
                    sprintf(
                        "activity_id = $activity->activity_id and result #>> '{title,0,narrative,0,narrative}' = '%s'",
                        pg_escape_string(trim(preg_replace('/\s+/', " ", $resultTitle)))
                    )
                )->first();
                !$resultData ?: $indicatorCount = $resultData->indicators_count;
            }
            $activity_data['Result Title'] = $resultTitle;
            $activity_data['Old']          = (int) $activity->indicators_count;
            $activity_data['New']          = $indicatorCount;
//            $activity_data['Result ID']           = $activity->result_id;

            $data[] = $activity_data;
        }

//        dd($data);

        return $data;
    }

    public function generateElementCounts()
    {
        Excel::create(
            'ElementCounts',
            function ($excel) {

                $excel->sheet(
                    'ElementCounts',
                    function ($sheet) {

                        $sheet->fromArray($this->getElementCounts());

                    }
                );

            }
        )->export('xls');
    }

    protected function getElementCounts()
    {

        $result      = app(ActivityResult::class);
        $transaction = app(Transaction::class);

        $data = [];

        $fields = [
            'description'                => 'iati_description',
            'participating_organization' => 'iati_participating_org',
            'document_link'              => 'iati_document_link',
            'planned_disbursement'       => 'iati_planned_disbursement',
            'budget'                     => 'iati_budget',
            'result'                     => 'iati_result',
            'transaction'                => 'iati_transaction'
        ];

        $newActivities = $this->activity->get();

        foreach ($newActivities as $activity) {
            $activity->result      = $result->where('activity_id', $activity->id)->get();
            $activity->transaction = $transaction->where('activity_id', $activity->id)->get();
            foreach ($fields as $field => $oldTable) {
                $activityData                = [];
                $activityData['Org Name']    = $activity->organization->name;
                $activityData['Activity ID'] = $activity->id;
                $activityData['element']     = $field;
                $activityData['new']         = count($activity->$field);
                $activityData['old']         = $this->getOldElementCount($oldTable, $activity->id);
                $data[]                      = $activityData;
            }
        }

        return $data;
    }

    protected function getOldElementCount($table, $activityId)
    {
        $this->initDBConnection('mysql');
        $count = $this->mysqlConn->table($table)->where('activity_id', $activityId)->count();

        return $count;
    }

    /* generates xml for organizations with version 2.02 who had published activities from April 1st, 2016 */
    public function generatePublishedActivityList()
    {
        Excel::create(
            'PublishedActivityList',
            function ($excel) {

                $excel->sheet(
                    'PublishedActivityList',
                    function ($sheet) {

                        $sheet->fromArray($this->getPublishedActivityList());

                    }
                );

            }
        )->export('xls');
    }

    protected function getPublishedActivityList()
    {
        $data = [];

        $activityPublishedModel = app(ActivityPublished::class);

        $activitiesPublished = $activityPublishedModel
            ->join('organizations', 'organizations.id', '=', 'activity_published.organization_id')
            ->join('settings', 'settings.organization_id', '=', 'activity_published.organization_id')
            ->where('activity_published.updated_at', '>=', '2016-04-01 00:00:00')
            ->where('settings.version', '2.02')
            ->groupby('organizations.id')
            ->select('organizations.name as OrganizationName', 'organizations.id as OrganizationID')
            ->get();

        $data = $activitiesPublished->toArray();

        return $data;
    }

    /* generates xml for organizations with publishing type segmented/unsegmented old vs new */
    public function generatePublishingType()
    {
        Excel::create(
            'publishingType',
            function ($excel) {

                $excel->sheet(
                    'publishingType',
                    function ($sheet) {

                        $sheet->fromArray($this->getPublishingType());

                    }
                );

            }
        )->export('xls');
    }

    protected function getPublishingType()
    {
        $this->initDBConnection('mysql');
        $data          = [];
        $organizations = $this->organization->all();

        foreach ($organizations as $organization) {
            $publishingType                      = [];
            $publishingType['Organization Name'] = $organization->name;
            $oldPublishingType                   = $this->mysqlConn->table('registry_info')->where('org_id', $organization->id)->first();
            if ($oldPublishingType == null) {
                $publishingType['Old'] = 'No Organization';
            } else {
                $publishingType['Old'] = $oldPublishingType->publishing_type == '1' ? 'segmented' : 'unsegmented';
            }
            $publishingType['New'] = $organization->settings->publishing_type;
            $data[]                = $publishingType;
        }

        return $data;
    }
}
