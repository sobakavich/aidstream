<?php

$router->group(
    ['domain' => config('tz.domain.subdomain') . env('HOST'), 'namespace' => 'Tz'],
    function ($router) {
        $router->group(
            ['namespace' => 'ProjectUpload'],
            function ($router) {
                $router->resource('project-upload', 'ProjectUploadController');

                $router->get(
                    '/project-template-download',
                    [
                        'uses' => 'ProjectUploadController@downloadProjectTemplate',
                        'as'   => 'project.templateDownload'
                    ]
                );
            }
        );
    }
);
