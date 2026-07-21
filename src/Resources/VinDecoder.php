<?php

namespace CodebyRay\CarListApi\Resources;

use CodebyRay\CarListApi\Response\ApiResponse;

final readonly class VinDecoder extends Resource
{
    public function decode(string $vin, ?int $modelYear = null): ApiResponse
    {
        $vin = strtoupper(str_replace([' ', '-'], '', trim($vin)));
        if (! preg_match('/^[A-HJ-NPR-Z0-9]{17}$/', $vin)) {
            throw new \InvalidArgumentException('The VIN must contain exactly 17 valid characters and may not contain I, O, or Q.');
        }
        if ($modelYear !== null && ($modelYear < 1980 || $modelYear > (int) date('Y') + 2)) {
            throw new \InvalidArgumentException('The model year must be between 1980 and two years from the current year.');
        }
        return $this->client->post('vin-decoder/decode', array_filter(['vin' => $vin, 'model_year' => $modelYear], static fn (mixed $value): bool => $value !== null));
    }
}
