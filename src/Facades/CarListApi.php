<?php

namespace CodebyRay\CarListApiLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \CodebyRay\CarListApiLaravel\Resources\Automotive automotive()
 * @method static \CodebyRay\CarListApiLaravel\Resources\Powersports powersports()
 * @method static \CodebyRay\CarListApiLaravel\Resources\VinDecoder vinDecoder()
 * @method static \CodebyRay\CarListApiLaravel\CarListApiManager withToken(string $token)
 */
class CarListApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'carlistapi';
    }
}
