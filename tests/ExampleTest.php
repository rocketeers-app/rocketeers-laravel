<?php

namespace Rocketeers\Laravel\Tests;

use Orchestra\Testbench\TestCase;
use Rocketeers\Laravel\RocketeersServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [RocketeersServiceProvider::class];
    }
}
