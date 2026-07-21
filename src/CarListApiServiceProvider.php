<?php

namespace CodebyRay\CarListApiLaravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;

class CarListApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/carlistapi.php', 'carlistapi');

        $this->app->singleton(CarListApiManager::class, function (Application $app): CarListApiManager {
            $config = $app['config']->get('carlistapi', []);

            return new CarListApiManager(
                http: $app->make(Factory::class),
                config: is_array($config) ? $config : [],
            );
        });

        $this->app->alias(CarListApiManager::class, 'carlistapi');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/carlistapi.php' => config_path('carlistapi.php'),
        ], 'carlistapi-config');
    }
}
