<?php

namespace App\Providers;

use App\Console\Commands\CheckProxy;
use App\Console\Commands\CheckProxySecurity;
use App\Console\Commands\FetchProxy;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.proxy.fetch', function()
        {
            return new FetchProxy();
        });

        $this->app->singleton('command.proxy.check', function()
        {
            return new CheckProxy();
        });

        $this->app->singleton('command.proxy.security', function()
        {
            return new CheckProxySecurity();
        });

        $this->commands(
            'command.proxy.fetch',
            'command.proxy.check',
            'command.proxy.security'
        );
    }
}
