<?php

use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;
use Pipedrive\Laravel\Http\LaravelHttpClient;
use Psr\Http\Message\ResponseInterface;

test('it forwards method, uri, headers and body and returns a psr-7 response', function () {
    Http::fake(['*' => Http::response('accepted', 202, ['X-Test' => 'yes'])]);

    $request = new Request('POST', 'https://acme.pipedrive.com/api/v1/notes', ['Content-Type' => 'application/json', 'X-Multi' => ['a', 'b']], '{"content":"Hi"}');

    $response = (new LaravelHttpClient)->sendRequest($request);

    expect($response)->toBeInstanceOf(ResponseInterface::class)
        ->and($response->getStatusCode())->toBe(202)
        ->and((string) $response->getBody())->toBe('accepted')
        ->and($response->getHeaderLine('X-Test'))->toBe('yes');

    Http::assertSent(fn ($r) => $r->method() === 'POST'
        && $r->url() === 'https://acme.pipedrive.com/api/v1/notes'
        && $r->body() === '{"content":"Hi"}'
        && $r->header('X-Multi') === ['a, b']);
});

test('it sends a request without a body and uses the configured timeout', function () {
    config(['pipedrive.http.timeout' => 45]);
    Http::fake(function ($request, array $options) {
        return Http::response((string) $options['timeout']);
    });

    $response = (new LaravelHttpClient)->sendRequest(new Request('GET', 'https://acme.pipedrive.com/api/v1/users/me'));

    expect((string) $response->getBody())->toBe('45');
    Http::assertSent(fn ($r) => $r->method() === 'GET' && $r->body() === '');
});

test('it defaults the content type of a body to json', function () {
    Http::fake(['*' => Http::response('ok')]);

    (new LaravelHttpClient)->sendRequest(new Request('POST', 'https://acme.pipedrive.com/api/v1/notes', [], '{"a":1}'));

    Http::assertSent(fn ($r) => $r->header('Content-Type') === ['application/json']);
});
