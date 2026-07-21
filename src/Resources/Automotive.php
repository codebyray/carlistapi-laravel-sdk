<?php

namespace CodebyRay\CarListApiLaravel\Resources;

use CodebyRay\CarListApiLaravel\Enums\SortDirection;
use CodebyRay\CarListApiLaravel\Response\ApiResponse;

final readonly class Automotive extends Resource
{
    public function years(SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-years/'.$this->sort($sort));
    }

    public function bodyStyles(SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-body-styles/'.$this->sort($sort));
    }

    public function yearsByBodyStyle(string $bodyStyle, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-years-by-body-style/'.$this->segment($bodyStyle).'/'.$this->sort($sort));
    }

    public function makesByBodyStyle(string $bodyStyle, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-makes-by-body-style/'.$this->segment($bodyStyle).'/'.$this->sort($sort));
    }

    public function makesByBodyStyleAndYear(string $bodyStyle, int $year, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-makes-by-body-style-and-year/'.$this->segment($bodyStyle).'/'.$year.'/'.$this->sort($sort));
    }

    public function makes(SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/all-makes/'.$this->sort($sort));
    }

    public function makeLogo(string $make): ApiResponse
    {
        return $this->client->get('car-data/get-make-logo/'.$this->segment($make));
    }

    public function details(string $uuid): ApiResponse
    {
        return $this->client->get('car-data/get-details/'.$this->segment($uuid));
    }

    public function makesByYear(int $year, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-makes/'.$year.'/'.$this->sort($sort));
    }

    public function models(int $year, string $make, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-models/'.$year.'/'.$this->segment($make).'/'.$this->sort($sort));
    }

    public function yearsRange(int $minYear, int $maxYear, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get("car-data/get-years/range/{$minYear}/{$maxYear}/".$this->sort($sort));
    }

    public function vehicleId(int $year, string $make, string $model, string $trim, string $engine): ApiResponse
    {
        return $this->client->get('car-data/get-vehicle-id/'.$year.'/'.$this->segment($make).'/'.$this->segment($model).'/'.$this->segment($trim).'/'.$this->segment($engine));
    }

    public function trims(int $year, string $make, string $model, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-trims/'.$year.'/'.$this->segment($make).'/'.$this->segment($model).'/'.$this->sort($sort));
    }

    public function makesByYearRange(int $minYear, int $maxYear, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get("car-data/get-makes-by-year/range/{$minYear}/{$maxYear}/".$this->sort($sort));
    }

    public function engines(int $year, string $make, string $model, string $trim, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-engines/'.$year.'/'.$this->segment($make).'/'.$this->segment($model).'/'.$this->segment($trim).'/'.$this->sort($sort));
    }

    public function driveTypes(SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-drive-types/'.$this->sort($sort));
    }

    public function fuelTypes(SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-fuel-types/'.$this->sort($sort));
    }

    public function makesByFuelType(string $fuelType, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-fuel-types/get-makes/'.$this->segment($fuelType).'/'.$this->sort($sort));
    }

    public function modelsByFuelTypeAndMake(string $fuelType, string $make, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-fuel-types/get-models/'.$this->segment($fuelType).'/'.$this->segment($make).'/'.$this->sort($sort));
    }

    public function numberOfDoors(SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/get-number-doors/'.$this->sort($sort));
    }

    public function makesByDriveType(string $driveType, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/drive-types/get-makes/'.$this->segment($driveType).'/'.$this->sort($sort));
    }

    public function modelsByDriveTypeAndMake(string $driveType, string $make, SortDirection|string $sort = SortDirection::Asc): ApiResponse
    {
        return $this->client->get('car-data/drive-types/get-models/'.$this->segment($driveType).'/'.$this->segment($make).'/'.$this->sort($sort));
    }
}
