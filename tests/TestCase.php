<?php

namespace Pipedrive\Laravel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Pipedrive\Laravel\PipedriveServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [PipedriveServiceProvider::class];
    }
}
