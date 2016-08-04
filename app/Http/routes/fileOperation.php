<?php

$router->group(
    ['namespace' => 'File'],
    function ($router) {
        $router->get(
            '/xml/{orgId}/{filename}',
            [
                'as'   => 'displayXml',
                'uses' => 'FileOperationController@display'
            ]
        );
    }
);
