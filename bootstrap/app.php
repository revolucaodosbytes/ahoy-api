<?php

use Telegram\Bot\Laravel\Facades\Telegram;

require_once __DIR__ . '/../vendor/autoload.php';

Dotenv::load(__DIR__.'/../');

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades();
$app->withEloquent();


/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     // Illuminate\Cookie\Middleware\EncryptCookies::class,
//     // Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
//     // Illuminate\Session\Middleware\StartSession::class,
//     // Illuminate\View\Middleware\ShareErrorsFromSession::class,
//     // Laravel\Lumen\Http\Middleware\VerifyCsrfToken::class,
// ]);

 $app->routeMiddleware([
     'jwt.auth' => Tymon\JWTAuth\Middleware\GetUserFromToken::class,
     'jwt.refresh' => Tymon\JWTAuth\Middleware\RefreshToken::class,
 ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\CommandServiceProvider::class);
$app->register(Vluzrmos\Tinker\TinkerServiceProvider::class);
$app->register(\Telegram\Bot\Laravel\TelegramServiceProvider::class);
$app->register(\Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class);
$app->register(\GrahamCampbell\Throttle\ThrottleServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../app/Http/routes.php';
});

Telegram::addCommands([
    \App\Console\Commands\Telegram\StartCommand::class,
    \App\Console\Commands\Telegram\TopCommand::class,
    \App\Console\Commands\Telegram\StatsCommand::class,
    \App\Console\Commands\Telegram\FunCommand::class,
    \App\Console\Commands\Telegram\AddNewSiteCommand::class,
    \App\Console\Commands\Telegram\IgnoreSiteCommand::class,
]);

Telegram::setWebhook('https://ahoy-api.revolucaodosbytes.pt/'.env('TELEGRAM_BOT_TOKEN').'/webhook');

/*
 * Register Facades
 */
class_alias('Tymon\JWTAuth\Facades\JWTAuth', 'JWTAuth');
class_alias('GrahamCampbell\Throttle\Facades\Throttle', 'Throttle');

return $app;
