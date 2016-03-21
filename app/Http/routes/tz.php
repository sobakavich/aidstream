<?php

$router->group(
    ['domain' => 'tz.localhost', 'namespace' => 'tz'],
    function ($router) {

        $router->resource('activity', 'ActivityController');

    }
);