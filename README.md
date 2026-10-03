# Car List API Laravel SDK

A Laravel SDK for interacting with the authenticated **Car List API**
from Laravel 11, 12, and 13 applications.

> Laravel 11 compatibility is provided for existing applications. Laravel 11
> reached end of security support on March 12, 2026, so Laravel 12 or newer is
> recommended for actively maintained applications.

This package contains only the API client. It does not include the Car List API server, database, authentication system, billing, admin interface, or import tools.

## Requirements

- PHP 8.2+ for Laravel 11 and 12
- PHP 8.3+ for Laravel 13
- Laravel 11, 12, or 13
- A Car List API bearer token

Laravel 11 compatibility is maintained for existing applications, but Laravel
11 itself is end-of-life and no longer receives framework security updates.
Laravel 12 or newer is recommended.

## Installation

Install via Composer:

```bash
composer require codebyray/carlistapi-laravel-sdk
```

Laravel package auto-discovery registers the service provider and facade automatically.

### Publishing the config

Publishing is optional. Use it only when you want to customize package configuration in the consuming application:

```bash
php artisan vendor:publish --tag=carlistapi-config
```

After changing `.env` or published configuration, clear Laravel's configuration cache:

```bash
php artisan optimize:clear
```

## Configuration

Add the required connection settings to the consuming application's `.env` file:

```dotenv
CAR_LIST_API_URL=https://carlistapi.com/api
CAR_LIST_API_VERSION=v1
CAR_LIST_API_TOKEN=your-token
```

The base URL must end at `/api`. Do not include the API version in `CAR_LIST_API_URL`; the SDK appends `CAR_LIST_API_VERSION` to every request.

For local development:

```dotenv
CAR_LIST_API_URL=https://carlistapi.test/api
CAR_LIST_API_VERSION=v1
CAR_LIST_API_TOKEN=your-local-token
```

This produces URLs such as:

```text
https://carlistapi.test/api/v1/car-data/get-years/asc
```

When a new API version is available, update only:

```dotenv
CAR_LIST_API_VERSION=v2
```

### Optional HTTP settings

The following environment variables are optional. The SDK provides sensible defaults:

```dotenv
CAR_LIST_API_TIMEOUT=15
CAR_LIST_API_CONNECT_TIMEOUT=5
CAR_LIST_API_RETRY_TIMES=2
CAR_LIST_API_RETRY_SLEEP_MS=200
```

`CAR_LIST_API_RETRY_TIMES` counts additional attempts after the first request.
Only GET requests are retried after connection failures or HTTP 5xx responses;
quota and other client errors are returned immediately. VIN decode POST requests
are sent once because a successful, quota-counted decode may have a lost response.

### User Agent

By default, requests include a `User-Agent` identifying the SDK and its version, for example:

```text
codebyray/carlistapi-laravel-sdk/<installed-version>
```

You can override it by setting:

```dotenv
CAR_LIST_API_USER_AGENT=my-application/1.0
```

## Quick Start

```php
use CodebyRay\CarListApiLaravel\Facades\CarListApi;

$vehicle = CarListApi::vinDecoder()
    ->decode('1HGCM82633A004352')
    ->data;
```

## Usage

### Dependency injection

```php
use CodebyRay\CarListApiLaravel\CarListApiManager;

final class VehicleController
{
    public function __invoke(CarListApiManager $carListApi)
    {
        $years = $carListApi->automotive()->years()->data;

        return response()->json($years);
    }
}
```

### Facade

```php
use CodebyRay\CarListApiLaravel\Facades\CarListApi;

$makes = CarListApi::automotive()->makes()->data;
$models = CarListApi::automotive()->models(2026, 'Toyota')->data;
$details = CarListApi::automotive()->details($uuid)->data;
```

### VIN decoder

```php
$decoded = CarListApi::vinDecoder()
    ->decode('1HGCM82633A004352', modelYear: 2003)
    ->data;
```

The SDK normalizes spaces, hyphens, and letter casing before sending the VIN.

### Powersports

```php
$types = CarListApi::powersports()->types()->data;
$years = CarListApi::powersports()->yearsByType('ATV / Utility')->data;
$models = CarListApi::powersports()
    ->modelsByYearMakeAndType('ATV / Utility', 2026, 'Can-Am')
    ->data;
```

### Per-request token override

```php
$response = CarListApi::withToken($customerToken)
    ->automotive()
    ->years();
```

## Responses

All successful requests return an `ApiResponse` instance:

```php
$response->data;
$response->status;
$response->headers;
$response->header('X-RateLimit-Remaining');
```

## Exceptions

The SDK throws typed exceptions for common HTTP and transport errors.

| Exception | Description |
|-----------|-------------|
| `AuthenticationException` | HTTP **401 Unauthorized** |
| `AuthorizationException` | HTTP **403 Forbidden** |
| `NotFoundException` | HTTP **404 Not Found** |
| `ValidationException` | HTTP **422 Unprocessable Entity** |
| `RateLimitException` | HTTP **429 Too Many Requests**. Includes `limit`, `used`, `remaining`, and `resetAt` properties. |
| `ServerException` | HTTP **5xx Server Error** |
| `TransportException` | Network or transport failure (timeouts, DNS errors, connection failures, etc.). |

## Endpoint coverage

The SDK wraps all authenticated routes currently defined by the Car List API:

- Automotive years, makes, models, trims, engines, vehicle IDs, full details, logos, body styles, fuel types, drive types, and door counts
- Powersports years, makes, models, sub-models, vehicle IDs, full details, logos, and type-filtered routes
- VIN decoding with optional model year

Demo endpoints are intentionally excluded because this package is intended for authenticated production API access.

## Testing

```bash
composer install
composer test
composer lint
```

The GitHub Actions matrix tests Laravel 11, Laravel 12, and Laravel 13.

## Support

- Documentation: https://carlistapi.com/docs
- Issues: https://github.com/Codebyray/carlistapi-laravel-sdk/issues
## License

MIT
