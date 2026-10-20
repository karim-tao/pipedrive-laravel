<?php

use Illuminate\Support\ServiceProvider;
use Pipedrive\Laravel\PipedriveServiceProvider;

test('it merges the package config under the pipedrive namespace', function () {
    expect(config('pipedrive.http.timeout'))->toBe(30)
        ->and(config('pipedrive.http.connect_timeout'))->toBe(10);
});

test('it publishes the package config to the application config path', function () {
    $paths = ServiceProvider::pathsToPublish(PipedriveServiceProvider::class, 'pipedrive-config');
    $source = array_key_first($paths);

    expect($paths)->toHaveCount(1)
        ->and($source)->toEndWith('config/pipedrive.php')
        ->and($paths[$source])->toBe(config_path('pipedrive.php'));
});
