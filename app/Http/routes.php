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

use \Telegram\Bot\Laravel\Facades\Telegram;


$app->get('/', function () use ($app) {
    return [
        'version' => 1
    ];
});

$app->post('/'.env("TELEGRAM_BOT_TOKEN").'/webhook', function () {
    Telegram::commandsHandler(true);

    return 'ok';
});

$app->group(['prefix' => 'api','namespace' => 'App\Http\Controllers'], function () use ($app) {
    $app->post('auth/login', 'Auth\AuthController@login');
    $app->post('auth/register', 'Auth\AuthController@register');
    $app->post('auth/renew', 'Auth\AuthController@renewToken');
    
    $app->get( 'pac', 'ProxyController@generatePAC' );
    $app->post( 'pac', 'ProxyController@generatePAC' );

    $app->get( 'proxies', 'ProxyController@getProxyList' );
    $app->get( 'sites', 'SitesController@getSiteList' );
    $app->get( 'hosts', 'SitesController@getHostsList' );
    $app->get( 'getProxy', 'ProxyController@getProxy' );
    $app->get( 'stats/host/{hostname}', 'StatsController@hostname');
    $app->post( 'report/blocked', 'ReportController@autoReportBlockedSite' );

    $app->get( 'vpn/certs/version', 'VPNController@getClientCAVersion');
    $app->get( 'vpn/certs/ca', 'VPNController@getClientCA');
    $app->get( 'vpn/certs/cert', 'VPNController@getClientCert');
    $app->get( 'vpn/certs/key', 'VPNController@getClientKey');
    $app->get( 'vpn/server', 'VPNController@getVPNServer');

    $app->get('/user', 'UserController@getCurrentUser');

    $app->get('banner', 'BannerController@getMessage');
});