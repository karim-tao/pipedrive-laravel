<?php

use Illuminate\Support\Facades\Http;
use Pipedrive\Client\PipedriveClient;
use Pipedrive\Laravel\Http\LaravelHttpClient;

test('the client reaches pipedrive through the laravel http client', function () {
    Http::fake([
        'acme.pipedrive.com/api/v1/users/me' => Http::response(['success' => true, 'data' => ['id' => 5, 'name' => 'Karim']]),
    ]);

    $user = PipedriveClient::factory()
        ->withClientCredentials('id', 'secret')
        ->withToken('access', time() + 3600, 'refresh', 'https://acme.pipedrive.com')
        ->withHttpClient(new LaravelHttpClient)
        ->make()
        ->users()
        ->me();

    expect($user->name)->toBe('Karim');
    Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer access'));
});
