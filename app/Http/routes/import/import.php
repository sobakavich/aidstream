<?php

$router->group(
    ['namespace' => 'Complete\Activity\Import'],
    function ($router) {
        $router->get('/import-activity/upload-csv', [
            'as'   => 'activity.upload-csv',
            'uses' => 'ImportController@uploadActivityCsv'
        ]);

        $router->post('/import-activity/import-csv', [
            'as'   => 'activity.import-csv',
            'uses' => 'ImportController@activities'
        ]);

        $router->get('import-activity/download-activity-template', [
            'as'   => 'activity.download-template',
            'uses' => 'ImportController@downloadActivityTemplate'
        ]);

        $router->get('/import-activity/get-valid-data', [
            'as'   => 'activity.get-valid-data',
            'uses' => 'ImportController@getValidData'
        ]);

        $router->get('/import-activity/get-invalid-data', [
            'as'   => 'activity.get-invalid-data',
            'uses' => 'ImportController@getInvalidData'
        ]);
    }
);