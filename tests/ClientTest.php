<?php

namespace CodebyRay\CarListApi\Tests;

use CodebyRay\CarListApi\CarListApiManager;
use CodebyRay\CarListApi\Exceptions\AuthenticationException;
use CodebyRay\CarListApi\Exceptions\AuthorizationException;
use CodebyRay\CarListApi\Exceptions\CarListApiException;
use CodebyRay\CarListApi\Exceptions\NotFoundException;
use CodebyRay\CarListApi\Exceptions\RateLimitException;
use CodebyRay\CarListApi\Exceptions\ServerException;
use CodebyRay\CarListApi\Exceptions\ValidationException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;

final class ClientTest extends TestCase
{
    public function test_it_calls_automotive_endpoint_with_bearer_token(): void
    {
        Http::fake(['example.test/*' => Http::response([['year' => '2026']], 200)]);

        $response = app(CarListApiManager::class)->automotive()->years();

        self::assertSame([['year' => '2026']], $response->data);

        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://example.test/api/v1/car-data/get-years/asc'
            && $request->hasHeader('Authorization', 'Bearer test-token')
        );
    }

    public function test_it_uses_the_configured_api_version(): void
    {
        config()->set('carlistapi.version', '/v2/');

        Http::fake(['example.test/*' => Http::response([], 200)]);

        app(CarListApiManager::class)->automotive()->years();

        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://example.test/api/v2/car-data/get-years/asc'
        );
    }

    public function test_it_uses_nested_retry_configuration(): void
    {
        config()->set('carlistapi.retry', [
            'times' => 0,
            'sleep_ms' => 25,
        ]);

        Http::fake(['example.test/*' => Http::response([], 200)]);

        app(CarListApiManager::class)->automotive()->years();

        Http::assertSentCount(1);
    }

    public function test_it_uses_a_custom_user_agent_when_configured(): void
    {
        config()->set('carlistapi.user_agent', 'develop-app/1.0');

        Http::fake(['example.test/*' => Http::response([], 200)]);

        app(CarListApiManager::class)->automotive()->years();

        Http::assertSent(fn (Request $request): bool => $request->hasHeader('User-Agent', 'develop-app/1.0')
        );
    }

    public function test_it_generates_a_default_package_user_agent(): void
    {
        config()->set('carlistapi.user_agent', null);

        Http::fake(['example.test/*' => Http::response([], 200)]);

        app(CarListApiManager::class)->automotive()->years();

        Http::assertSent(fn (Request $request): bool => str_starts_with(
            $request->header('User-Agent')[0] ?? '',
            'codebyray/carlistapi-laravel-sdk/'
        )
        );
    }

    public function test_it_normalizes_and_posts_a_vin(): void
    {
        Http::fake(['example.test/*' => Http::response(['vin' => '1HGCM82633A004352'], 200)]);

        $response = app(CarListApiManager::class)
            ->vinDecoder()
            ->decode('1hg-cm82633 a004352', 2003);

        self::assertSame('1HGCM82633A004352', $response->data['vin']);

        Http::assertSent(fn (Request $request): bool => $request->method() === 'POST'
            && $request['vin'] === '1HGCM82633A004352'
            && $request['model_year'] === 2003
        );
    }

    public function test_it_maps_rate_limit_context(): void
    {
        Http::fake([
            'example.test/*' => Http::response([
                'error' => 'Limit reached.',
                'limit' => 10,
                'used' => 10,
                'remaining' => 0,
                'reset_at' => '2026-08-01T00:00:00Z',
            ], 429),
        ]);

        try {
            app(CarListApiManager::class)->vinDecoder()->decode('1HGCM82633A004352');
            self::fail('Exception not thrown');
        } catch (RateLimitException $e) {
            self::assertSame(10, $e->limit);
            self::assertSame(0, $e->remaining);
        }
    }

    public function test_it_encodes_path_segments(): void
    {
        Http::fake(['example.test/*' => Http::response([], 200)]);

        app(CarListApiManager::class)
            ->powersports()
            ->modelsByYearMakeAndType('ATV / Utility', 2026, 'Can-Am');

        Http::assertSent(fn (Request $request): bool => str_contains($request->url(), 'ATV%20%2F%20Utility/2026/Can-Am/asc')
        );
    }

    public static function httpExceptionProvider(): array
    {
        return [
            'authentication' => [401, AuthenticationException::class],
            'authorization' => [403, AuthorizationException::class],
            'not found' => [404, NotFoundException::class],
            'validation' => [422, ValidationException::class],
            'server error' => [500, ServerException::class],
            'other client error' => [400, CarListApiException::class],
        ];
    }

    #[DataProvider('httpExceptionProvider')]
    public function test_it_maps_http_errors(
        int $status,
        string $exceptionClass,
    ): void {
        Http::fake([
            'example.test/*' => Http::response([
                'error' => 'Request failed.',
            ], $status),
        ]);

        $this->expectException($exceptionClass);
        $this->expectExceptionMessage('Request failed.');

        app(CarListApiManager::class)
            ->automotive()
            ->years();
    }
}
