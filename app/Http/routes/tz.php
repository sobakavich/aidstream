<?php

$router->group(
    ['domain' => 'tz.localhost', 'namespace' => 'tz'],
    function ($router) {

        $router->resource('activity', 'ActivityController');

        $router->get(
            'settings',
            [
                'as'   => 'settings',
                'uses' => 'SettingsController@index'
            ]
        );

        $router->put(
            'settings/{id}',
            [
                'as'   => 'settings.update',
                'uses' => 'SettingsController@update'
            ]
        );

    }
);