<?php

namespace App\Providers;

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

        $this->commands(
            'command.proxy.fetch'
        );
    }
}
