<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return [
        'version' => 1
    ];
});

$app->get( 'api/proxies', 'ProxyController@getProxyList' );
$app->get( 'api/sites', 'SitesController@getSiteList' );
$app->get( 'api/getProxy', 'ProxyController@getProxy' );
$app->get( 'api/stats/host/{hostname}', 'StatsController@hostname');