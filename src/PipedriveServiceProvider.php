<?php

namespace Pipedrive\Laravel;

use Illuminate\Support\ServiceProvider;

final class PipedriveServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pipedrive.php', 'pipedrive');
    }

    public function boot(): void
    {
        $this->publishes(
            paths: [
                __DIR__ . '/../config/pipedrive.php' => config_path('pipedrive.php'),
            ],
            groups: 'pipedrive-config'
        );
    }
}
