<?php

namespace Rocketeers\Laravel;

use Illuminate\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Monolog\Logger;
use Rocketeers\Laravel\RocketeersEventServiceProvider;

class RocketeersLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/rocketeers.php' => config_path('rocketeers.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $source = realpath($raw = __DIR__.'/../config/rocketeers.php') ?: $raw;

        $this->mergeConfigFrom($source, 'rocketeers');

        $this->app->register(RocketeersEventServiceProvider::class);

        $this->app->singleton('rocketeers.logger', function ($app) {
            $handler = new RocketeersLoggerHandler($app->make('rocketeers.client'));

            $logger = new Logger('rocketeers');
            $logger->pushHandler($handler);

            return $logger;
        });

        Log::extend('rocketeers', function ($app) {
            return $app['rocketeers.logger'];
        });
    }
}
