<?php

namespace Rocketeers\Laravel;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Rocketeers\Laravel\Services\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;
use Rocketeers\Laravel\Facades\RocketeersHorizon;

class RocketeersHorizonServiceProvider extends HorizonApplicationServiceProvider
{
    public function register()
    {
        $this->app->singleton('rocketeers.horizon', fn () => new Horizon);
    }

    protected function gate()
    {
        Gate::define('viewHorizon', function ($user = null) {
            return RocketeersHorizon::getDefaultAccess() || RocketeersHorizon::hasAccess($user);
        });
    }
}
