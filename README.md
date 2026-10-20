# Pipedrive Laravel

[![Tests](https://github.com/karim-tao/pipedrive-laravel/actions/workflows/tests.yml/badge.svg)](https://github.com/karim-tao/pipedrive-laravel/actions/workflows/tests.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/karim-tao/pipedrive-laravel)](https://packagist.org/packages/karim-tao/pipedrive-laravel)
[![License](https://img.shields.io/packagist/l/karim-tao/pipedrive-laravel)](https://packagist.org/packages/karim-tao/pipedrive-laravel)

Talk to the Pipedrive API from Laravel: config-driven app credentials and Laravel's own HTTP client under the hood.

In this example, we build a client from a token stored for a user and read the authorized user:

```php
use Pipedrive\Client\PipedriveClient;
use Pipedrive\Laravel\Http\LaravelHttpClient;

$pipedrive = PipedriveClient::factory()
    ->withClientCredentials(config('pipedrive.auth.client_id'), config('pipedrive.auth.client_secret'))
    ->withToken($accessToken, $expiresAt, $refreshToken, $apiDomain)
    ->withHttpClient(new LaravelHttpClient)
    ->make();

$user = $pipedrive->users()->me();
```

> Not using Laravel? See [`karim-tao/pipedrive-client`](https://github.com/karim-tao/pipedrive-client).

## Installation

> Requires PHP 8.3+ and Laravel 12 or 13.

You can install the package via composer:

```bash
composer require karim-tao/pipedrive-laravel
```

The service provider is discovered automatically. Publish the config file if you want to change it:

```bash
php artisan vendor:publish --tag=pipedrive-config
```

## Configuration

Set the credentials of your Pipedrive app in `.env`:

```dotenv
PIPEDRIVE_CLIENT_ID=your-client-id
PIPEDRIVE_CLIENT_SECRET=your-client-secret
```

### All options

Everything in `config/pipedrive.php`:

| Key                    | Env                              | Default |
|------------------------|----------------------------------|---------|
| `auth.client_id`       | `PIPEDRIVE_CLIENT_ID`            | `''`    |
| `auth.client_secret`   | `PIPEDRIVE_CLIENT_SECRET`        | `''`    |
| `http.timeout`         | `PIPEDRIVE_HTTP_TIMEOUT`         | `30`    |
| `http.connect_timeout` | `PIPEDRIVE_HTTP_CONNECT_TIMEOUT` | `10`    |

## How it works

Pipedrive tokens belong to each user who connects their company, so there is no shared client: build one per user with `PipedriveClient::factory()` and pass `LaravelHttpClient`, a PSR-18 adapter over Laravel's `Http` facade, so it honours your HTTP config, fakes and middleware.

Pipedrive gives a new refresh token on every refresh. Pass an event dispatcher with `withEventDispatcher()` to store the new pair as soon as the client dispatches `Pipedrive\Client\Events\TokenRefreshed`.

## Testing

```bash
composer test
```

Because requests go through Laravel's `Http` facade, `Http::fake()` works out of the box:

```php
Http::fake([
    '*/api/v1/users/me' => Http::response(['success' => true, 'data' => ['id' => 5, 'name' => 'Karim']]),
]);
```

## License

Pipedrive Laravel is open-sourced software licensed under the [MIT license](LICENSE.md).
