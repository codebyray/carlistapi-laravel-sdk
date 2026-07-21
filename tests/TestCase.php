<?php

namespace CodebyRay\CarListApiLaravel\Tests;

use CodebyRay\CarListApiLaravel\CarListApiServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [CarListApiServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('carlistapi.base_url', 'https://example.test/api');
        $app['config']->set('carlistapi.version', 'v1');
        $app['config']->set('carlistapi.token', 'test-token');
        $app['config']->set('carlistapi.retry', [
            'times' => 0,
            'sleep_ms' => 0,
        ]);
    }
}
