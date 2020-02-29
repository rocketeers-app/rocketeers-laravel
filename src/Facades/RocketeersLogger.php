<?php

namespace Rocketeers\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class RocketeersLogger extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rocketeers-logger';
    }
}
