<?php

namespace CodebyRay\CarListApiLaravel\Tests;

use CodebyRay\CarListApiLaravel\CarListApiManager;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;

final class ResourceRoutesTest extends TestCase
{
    public static function routeProvider(): array
    {
        return require __DIR__ . '/Fixtures/routes.php';
    }

    #[DataProvider('routeProvider')]
    public function test_resource_method_uses_documented_route(
        string $resource,
        string $method,
        array $arguments,
        string $path,
        string $httpMethod,
    ): void {
        Http::fake(['example.test/*' => Http::response([], 200)]);

        app(CarListApiManager::class)->{$resource}()->{$method}(...$arguments);

        Http::assertSentCount(1);
        Http::assertSent(fn (Request $request): bool => $request->method() === $httpMethod
            && parse_url($request->url(), PHP_URL_PATH) === '/api/v1/' . $path
        );
    }
}
