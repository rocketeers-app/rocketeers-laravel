<?php

namespace Rocketeers\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void defineAccess(Closure $closure)
 * @method static bool hasAccess()
 * @method static bool getDefaultAccess()
 *
 * @see \Rocketeers\Laravel\Services\Horizon
 */
class RocketeersHorizon extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'rocketeers.horizon';
    }
}
