<?php

namespace Rocketeers\Laravel;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

class RocketeersLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/rocketeers.php' => config_path('rocketeers.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/rocketeers.php', 'rocketeers');

        Log::extend('rocketeers', function ($app) {
            return $app['rocketeers.logger'];
        });

        $this->app->singleton('rocketeers.logger', function ($app) {
            $handler = new RocketeersLoggerHandler($app->make('rocketeers.client'));

            $logger = new Logger('Rocketeers');
            $logger->pushHandler($handler);

            return $logger;
        });
    }
}
