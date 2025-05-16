<?php

namespace Rocketeers\Laravel;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Application;
use Rocketeers\Laravel\Services\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;
use Rocketeers\Laravel\Facades\RocketeersHorizon;

class RocketeersHorizonServiceProvider extends HorizonApplicationServiceProvider
{
    public function register()
    {
        $this->app->singleton('rocketeers.horizon', fn() => new Horizon);

        $this->app->booting(function ($app) {
            /**
             * @var \Illuminate\Contracts\Config\Repository $config
             */
            $config = $app->make(Repository::class);

            $horizonPath = trim($config->get('horizon.path', 'horizon'), '/');

            $paths = $config->get('cors.paths', []);

            if (!in_array($horizonPath, $paths, true)) {
                $paths[] = $horizonPath;
                $config->set('cors.paths', $paths);
            }

            dd($paths);
        });
    }

    protected function gate()
    {
        Gate::define('viewHorizon', function ($user = null) {
            return RocketeersHorizon::getDefaultAccess() || RocketeersHorizon::hasAccess($user);
        });
    }
}
