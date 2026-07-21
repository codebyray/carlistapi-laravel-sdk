<?php

namespace CodebyRay\CarListApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \CodebyRay\CarListApi\Resources\Automotive automotive()
 * @method static \CodebyRay\CarListApi\Resources\Powersports powersports()
 * @method static \CodebyRay\CarListApi\Resources\VinDecoder vinDecoder()
 * @method static \CodebyRay\CarListApi\CarListApiManager withToken(string $token)
 */
class CarListApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'carlistapi';
    }
}
