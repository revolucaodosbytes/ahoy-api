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

$app->get( 'api/ahoy.pac', 'ProxyController@generatePAC' );
$app->post( 'api/ahoy.pac', 'ProxyController@generatePAC' );
$app->get( 'api/proxies', 'ProxyController@getProxyList' );
$app->get( 'api/sites', 'SitesController@getSiteList' );
$app->get( 'api/hosts', 'SitesController@getHostsList' );
$app->get( 'api/getProxy', 'ProxyController@getProxy' );
$app->get( 'api/stats/host/{hostname}', 'StatsController@hostname');
$app->post( 'api/report/blocked', 'ReportController@autoReportBlockedSite' );

$app->get( 'api/vpn/certs/version', 'VPNController@getClientCAVersion');
$app->get( 'api/vpn/certs/ca', 'VPNController@getClientCA');
$app->get( 'api/vpn/certs/cert', 'VPNController@getClientCert');
$app->get( 'api/vpn/certs/key', 'VPNController@getClientKey');
$app->get( 'api/vpn/server', 'VPNController@getVPNServer');

$app->post('/'.env("TELEGRAM_BOT_TOKEN").'/webhook', function () {
    Telegram::commandsHandler(true);

    return 'ok';
});

$app->post('api/auth/login', 'Auth\AuthController@login');
$app->post('api/auth/register', 'Auth\AuthController@register');
$app->post('api/auth/renew', 'Auth\AuthController@renewToken');

$app->get('/api/user', 'UserController@getCurrentUser');
