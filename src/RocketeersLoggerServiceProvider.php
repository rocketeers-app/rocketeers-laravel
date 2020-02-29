<?php

namespace Rocketeers\Laravel;

use Illuminate\Support\ServiceProvider;
use Rocketeers\Laravel\Facades\RocketeersLogger;

class RocketeersLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/rocketeers.php' => config_path('rocketeers.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('rocketeers-logger', function () {
            $rocketeers = new Rocketeers(config('rocketeers.api_token'));

            return new RocketeersLogger($rocketeers);
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/rocketeers.php', 'rocketeers');
    }
}
