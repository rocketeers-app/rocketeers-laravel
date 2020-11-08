<?php

namespace Rocketeers\Laravel;

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
        $this->setupConfig($this->app);
    }

    public function setupConfig(Container $app)
    {
        $source = realpath($raw = __DIR__.'/../config/rocketeers.php') ?: $raw;

        if ($app instanceof LaravelApplication && $app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/rocketeers.php' => config_path('rocketeers.php'),
            ], 'config');
        } elseif ($app instanceof LumenApplication) {
            $app->configure('rocketeers');
        }

        $this->mergeConfigFrom($source, 'rocketeers');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(RocketeersEventServiceProvider::class);

        Log::extend('rocketeers', function ($app) {
            return $app['rocketeers.logger'];
        });

        $this->app->singleton('rocketeers.logger', function ($app) {
            $handler = new RocketeersLoggerHandler($app->make('rocketeers.client'));

            $logger = new Logger('rocketeers');
            $logger->pushHandler($handler);

            return $logger;
        });
    }
}
