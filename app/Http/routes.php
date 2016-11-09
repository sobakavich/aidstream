<?php

$language = Cookie::get('language');
if (isset($language)) {
    App::setLocale($language);
}

$router->get(
    '/public/files/xml/{file}',
    function ($file) {
        return redirect('/files/xml/' . $file);
    }
);

$router->get('/', 'HomeController@index');
$router->get('home', 'HomeController@index');
$router->get('about', 'AboutController@index');
$router->get('who-is-using', 'WhoIsUsingController@index');
$router->get('who-is-using/{page}/{count}', 'WhoIsUsingController@listOrganization');
$router->get('admin/dashboard', 'SuperAdmin\OrganizationController@adminDashboard');
$router->resource('settings', 'Complete\SettingsController');

$router->put(
    'update-settings',
    [
        'as'   => 'update-settings',
        'uses' => 'Complete\SettingsController@updateSettings'
    ]
);

$router->post(
    'change-segmentation',
    [
        'as'   => 'change-segmentation',
        'uses' => 'Complete\SettingsController@changeSegmentation'
    ]
);

$router->post(
    'activity/{activity}/complete',
    [
        'as'   => 'activity.complete',
        'uses' => 'Complete\WorkflowController@complete'
    ]
);

$router->post(
    'activity/{activity}/verify',
    [
        'as'   => 'activity.verify',
        'uses' => 'Complete\WorkflowController@verify'
    ]
);

$router->post(
    'activity/{activity}/publish',
    [
        'as'   => 'activity.publish',
        'uses' => 'Complete\WorkflowController@publish'
    ]
);

$router->get('who-is-using/{organization_id}', 'WhoIsUsingController@getDataForOrganization');

$router->controllers(
    [
        'auth'     => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]
);

$router->post(
    'check-organization-user-identifier',
    [
        'as'   => 'check-organization-user-identifier',
        'uses' => 'Auth\AuthController@checkUserIdentifier'
    ]
);

$router->get('logs', 'LogViewerController@index');

$router->get(
    'show-logs',
    [
        'as'   => 'show-logs',
        'uses' => '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index'
    ]
);

$router->get(
    'admin/activity-log',
    [
        'SuperAdmin' => true,
        'as'         => 'admin.activity-log',
        'uses'       => 'Complete\AdminController@index'
    ]
);

$router->get(
    'admin/activity-log/organization/{orgId}',
    [
        'SuperAdmin' => true,
        'as'         => 'admin.activity-log.organization',
        'uses'       => 'Complete\AdminController@index'
    ]
);

$router->get(
    'admin/activity-log/{id}',
    [
        'SuperAdmin' => true,
        'as'         => 'admin.activity-log.view-data',
        'uses'       => 'Complete\AdminController@viewData'
    ]
);

$router->resource('upgrade-version', 'Complete\UpgradeController');
$router->get(
    'documents',
    [
        'as'   => 'documents',
        'uses' => 'Complete\DocumentController@index'
    ]
);
$router->post(
    'document/upload',
    [
        'as'   => 'document.upload',
        'uses' => 'Complete\DocumentController@store'
    ]
);
$router->get(
    'document/list',
    [
        'as'   => 'document.list',
        'uses' => 'Complete\DocumentController@getDocuments'
    ]
);
$router->get(
    'document/{id}/delete',
    [
        'as'   => 'document.delete',
        'uses' => 'Complete\DocumentController@destroy'
    ]
);
$router->get(
    'validate-activity/{id}',
    [
        'as'   => 'validate-activity',
        'uses' => 'CompleteValidateController@validateActivity'
    ]
);
$router->get(
    'validate-activity/{id}/version/{version?}',
    [
        'as'   => 'validate-activity',
        'uses' => 'CompleteValidateController@validateActivity'
    ]
);
$router->get(
    'validate-organization/{id}',
    [
        'as'   => 'validate-organization',
        'uses' => 'CompleteValidateController@validateOrganization'
    ]
);
$router->get(
    'validate-organization/{id}/version/{version?}',
    [
        'as'   => 'validate-organization',
        'uses' => 'CompleteValidateController@validateOrganization'
    ]
);
$router->get(
    'admin/updateOrganizationIdForUserActivities',
    [
        'SuperAdmin' => true,
        'as'         => 'admin.updateOrganizationIdForUserActivities',
        'uses'       => 'Complete\AdminController@updateOrganizationIdForUserActivities'
    ]
);

$router->get(
    'user-logs',
    [
        'as'   => 'user-logs',
        'uses' => 'Complete\UserLogController@search'
    ]
);

$router->post(
    'user-logs',
    [
        'as'   => 'user-logs.filter',
        'uses' => 'Complete\UserLogController@search'
    ]
);

$router->get(
    'user-logs/viewDeletedData/{id}',
    [
        'as'   => 'user-logs.viewDeletedData',
        'uses' => 'Complete\UserLogController@viewDeletedData'
    ]
);

$router->get(
    'registration/organization',
    [
        'as'   => 'registration.organization',
        'uses' => 'Auth\RegistrationController@showOrgForm'
    ]
);

$router->post(
    'registration/save-organization',
    [
        'as'   => 'registration.save-organization',
        'uses' => 'Auth\RegistrationController@saveOrganization'
    ]
);

$router->get(
    'registration/users',
    [
        'as'   => 'registration.users',
        'uses' => 'Auth\RegistrationController@showUsersForm'
    ]
);

$router->post(
    'registration/complete',
    [
        'as'   => 'registration.complete',
        'uses' => 'Auth\RegistrationController@completeRegistration'
    ]
);

$router->get(
    'registration/similar-organizations',
    [
        'as'   => 'registration.similar-organizations',
        'uses' => 'Auth\RegistrationController@showSimilarOrganizations'
    ]
);

$router->post(
    'registration/similar-organizations',
    [
        'as'   => 'registration.submit-similar-organization',
        'uses' => 'Auth\RegistrationController@submitSimilarOrganization'
    ]

);

$router->get(
    'similar-organizations/{orgName}',
    [
        'as'   => 'similar-org',
        'uses' => 'Auth\RegistrationController@listSimilarOrganizations'
    ]
);

$router->get(
    'user/verification/{code}',
    [
        'as'   => 'user-verification',
        'uses' => 'Auth\VerificationController@verifyUser'
    ]
);

$router->get(
    'user/secondary-verification/{code}',
    [
        'as'   => 'secondary-verification',
        'uses' => 'Auth\VerificationController@verifySecondary'
    ]
);

$router->post(
    'settings/registry-info/{code}',
    [
        'as'   => 'save-registry-info',
        'uses' => 'Auth\VerificationController@saveRegistryInfo'
    ]
);

$router->get(
    'user/create-password/{code}',
    [
        'as'   => 'show-create-password',
        'uses' => 'Auth\PasswordController@showCreatePasswordForm'
    ]
);

$router->post(
    'user/create-password/{code}',
    [
        'as'   => 'create-password',
        'uses' => 'Auth\PasswordController@createPassword'
    ]
);

$router->resource('agency', 'AgencyController');

$router->get(
    'register',
    [
        'as'   => 'registration',
        'uses' => 'Auth\RegistrationController@showRegistrationForm'
    ]
);

$router->post(
    'register',
    [
        'as'   => 'registration.register',
        'uses' => 'Auth\RegistrationController@register'
    ]
);

$router->match(
    ['GET', 'POST'],
    'find-similar-organizations/{type?}',
    [
        'as'   => 'similar-organizations',
        'uses' => 'Auth\RegistrationController@showSimilarOrganizations'
    ]
);

$router->post(
    'same-organization-identifier',
    [
        'as'   => 'same-organization-identifier',
        'uses' => 'Auth\RegistrationController@showSameOrgIdentifier'
    ]
);

$router->match(
    ['GET', 'POST'],
    'submit-similar-organizations/{type?}',
    [
        'as'   => 'submit-similar-organization',
        'uses' => 'Auth\RegistrationController@submitSimilarOrganization'
    ]
);

$router->post(
    'check-org-identifier',
    [
        'as'   => 'check-org-identifier',
        'uses' => 'Auth\RegistrationController@checkOrgIdentifier'
    ]
);

$router->get(
    'similar-organizations/{orgName}',
    [
        'as'   => 'similar-org',
        'uses' => 'Auth\RegistrationController@listSimilarOrganizations'
    ]
);

$router->get(
    'contact/{template}',
    [
        'as'   => 'contact',
        'uses' => 'ContactController@showContactForm'
    ]
);

$router->post(
    'contact/{template}',
    [
        'as'   => 'contact',
        'uses' => 'ContactController@processEmail'
    ]
);

$router->get(
    'registration/organization',
    [
        'as'   => 'registration.organization',
        'uses' => 'Auth\RegistrationController@showOrgForm'
    ]
);

$router->post(
    'registration/save-organization',
    [
        'as'   => 'registration.save-organization',
        'uses' => 'Auth\RegistrationController@saveOrganization'
    ]
);

$router->get(
    'registration/users',
    [
        'as'   => 'registration.users',
        'uses' => 'Auth\RegistrationController@showUsersForm'
    ]
);

$router->post(
    'registration/complete',
    [
        'as'   => 'registration.complete',
        'uses' => 'Auth\RegistrationController@completeRegistration'
    ]
);

$router->get(
    'user/verification/{code}',
    [
        'as'   => 'user-verification',
        'uses' => 'Auth\VerificationController@verifyUser'
    ]
);

$router->get(
    'user/secondary-verification/{code}',
    [
        'as'   => 'secondary-verification',
        'uses' => 'Auth\VerificationController@verifySecondary'
    ]
);

$router->post(
    'settings/registry-info/{code}',
    [
        'as'   => 'save-registry-info',
        'uses' => 'Auth\VerificationController@saveRegistryInfo'
    ]
);

$router->get(
    'user/create-password/{code}',
    [
        'as'   => 'show-create-password',
        'uses' => 'Auth\PasswordController@showCreatePasswordForm'
    ]
);

$router->post(
    'user/create-password/{code}',
    [
        'as'   => 'create-password',
        'uses' => 'Auth\PasswordController@createPassword'
    ]
);

$router->resource('agency', 'AgencyController');

$router->post('/add-publishing-info-later', [
    'as'   => 'publishing-info.add-later',
    'uses' => 'Auth\VerificationController@addPublishingInfoLater'
]);

$router->get('csv-for-sid', function () {
    $activityModel = app()->make(\App\Models\Activity\Activity::class);
    $excel = app(Maatwebsite\Excel\Excel::class);

    $activities = $activityModel->where('published_to_registry', 1)->with('organization', 'transactions')->get();

    $activityData = [];

    try {
        foreach ($activities as $index => $activity) {
            $data = [];

            $activity_identifier = $activity->identifier['activity_identifier'];
            $title = preg_replace('/[^A-Za-z0-9\- ]/', '', $activity->title[0]['narrative']);
            $reporting_org_id = (getVal($activity->organization->reporting_org, [0, 'reporting_organization_identifier']));
            $reporting_org_name = (getVal($activity->organization->reporting_org, [0, 'narrative', 0, 'narrative']));
            $recipient_code = $activity->recipient_country ? ($activity->recipient_country[0]['country_code']) : '';
            $recipient_country = $activity->recipient_country ? ($activity->recipient_country[0]['narrative'][0]['narrative']) : '';
            $funding_org_identifier = ($activity->participating_organization[0]['organization_role'] == 2)?($activity->participating_organization[0]['identifier']): "";
            $funding_org = ($activity->participating_organization[0]['organization_role'] == 2)?($activity->participating_organization[0]['narrative'][0]['narrative']): "";
            $transaction_recipient_country_code = '';

            $data[$index]['activity_identifier'] = $activity_identifier;
            $data[$index]['title'] = $title;
            $data[$index]['reporting_org_id'] = $reporting_org_id;
            $data[$index]['reporting_org_name'] = $reporting_org_name;
            $data[$index]['recipient_code'] = $recipient_code;
            $data[$index]['recipient_country'] = $recipient_country;
            $data[$index]['funding_org_identifier'] = $funding_org_identifier;
            $data[$index]['funding_org'] = $funding_org;
            $data[$index]['transaction_recipient_country_code'] = $transaction_recipient_country_code;

            if (count($activity->transactions) > 1) {
                $i = $index;

                foreach ($activity->transactions as $transaction) {
                    if (array_key_exists($i, $activity->transactions)) {
                        $data[$i]['transaction_recipient_country_code'] = getVal($activity->transactions[$i]->transaction, ['recipient_country', 0, 'country_code'], "");
                    }

                    $i++;

                        $data[$i] = [
                            'activity_identifier' => $activity_identifier,
                            'title' => $title,
                            'reporting_org_id' => $reporting_org_id,
                            'reporting_org_name' => $reporting_org_name,
                            'recipient_code' => $recipient_code,
                            'recipient_country' => $recipient_country,
                            'funding_org_identifier' => $funding_org_identifier,
                            'funding_org' => $funding_org,
                            'transaction_recipient_country_code' => $transaction_recipient_country_code
                        ];
                }
            } else {
                $data[$index]['transaction_recipient_country_code'] = (!is_null($activity->transactions()->first())) ? getVal($activity->transactions()->first()->transaction, ['recipient_country', 0, 'country_code'], "") : '';
            }
            $activityData[] = $data[$index];

        }
    } catch (Exception $e) {

    }

    $excel->create('temp', function ($excel) use ($activityData) {
        $excel->sheet('sheet1', function ($sheet) use ($activityData) {
            $sheet->fromArray($activityData);
        });
    })->export('csv');

});
