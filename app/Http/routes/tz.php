<?php

$router->group(
    ['domain' => 'tz.localhost', 'namespace' => 'tz'],
    function ($router) {

        $router->get('activity/create', ['as' => 'activity.create', 'uses' => 'ActivityController@create']);
        $router->post('activity/store', ['as' => 'activity.tzstore', 'uses' => 'ActivityController@store']);
        $router->get('activity/{id}', ['as' => 'activity.show', 'uses' => 'ActivityController@show']);
        $router->get('activity/{id}/edit', ['as' => 'activity.edit', 'uses' => 'ActivityController@edit']);
        $router->put('activity/{id}/update', ['as' => 'activity.tzupdate', 'uses' => 'ActivityController@update']);

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

        $router->get(
            'activity/{id}/transaction',
            [
                'as'   => 'activity.transaction.index',
                'uses' => 'TransactionController@index'
            ]
        );

        $router->get(
            'activity/{id}/transaction/{code}/create',
            [
                'as'   => 'activity.transaction.create',
                'uses' => 'TransactionController@create'
            ]
        );

        $router->post(
            'activity/{id}/transaction/{code}',
            [
                'as'   => 'activity.transaction.store',
                'uses' => 'TransactionController@store'
            ]
        );

        $router->get(
            'activity/{id}/transaction/{transaction_id}/edit',
            [
                'as'   => 'activity.transaction.edit',
                'uses' => 'TransactionController@edit'
            ]
        );

        $router->put(
            'activity/{id}/transaction/{code}',
            [
                'as'   => 'update_transactions',
                'uses' => 'TransactionController@store'
            ]
        );

        $router->get(
            'activity/{id}/transaction/{transactionId}/delete',
            [
                'as'   => 'activity.transaction.delete',
                'uses' => 'TransactionController@destroy'
            ]
        );

    }
);
