<?php

$api = app(Dingo\Api\Routing\Router::class);

$api->version(config('api.version'), ['namespace' => 'App\Http\Controllers'], function ($api) {
    $api->get('/', 'AccountController@store');
});