<?php

namespace CodebyRay\CarListApi\Resources;

use CodebyRay\CarListApi\Enums\SortDirection;
use CodebyRay\CarListApi\Response\ApiResponse;

final readonly class Powersports extends Resource
{
    public function years(SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get('powersports-data/get-years/'.$this->sort($sort)); }
    public function makes(SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get('powersports-data/all-makes/'.$this->sort($sort)); }
    public function yearsRange(int $minYear, int $maxYear, SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get("powersports-data/get-years/range/{$minYear}/{$maxYear}/".$this->sort($sort)); }
    public function vehicleId(int $year, string $make, string $model, string $subModel): ApiResponse { return $this->client->get('powersports-data/get-vehicle-id/'.$year.'/'.$this->segment($make).'/'.$this->segment($model).'/'.$this->segment($subModel)); }
    public function makeLogo(string $make): ApiResponse { return $this->client->get('powersports-data/get-make-logo/'.$this->segment($make)); }
    public function details(string $uuid): ApiResponse { return $this->client->get('powersports-data/get-details/'.$this->segment($uuid)); }
    public function makesByYear(int $year, SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get('powersports-data/get-makes/'.$year.'/'.$this->sort($sort)); }
    public function makesByYearRange(int $minYear, int $maxYear, SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get("powersports-data/get-makes-by-year/range/{$minYear}/{$maxYear}/".$this->sort($sort)); }
    public function models(int $year, string $make, SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get('powersports-data/get-models/'.$year.'/'.$this->segment($make).'/'.$this->sort($sort)); }
    public function subModels(int $year, string $make, string $model, SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get('powersports-data/get-sub-models/'.$year.'/'.$this->segment($make).'/'.$this->segment($model).'/'.$this->sort($sort)); }
    public function types(SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get('powersports-data/get-types/'.$this->sort($sort)); }
    public function yearsByType(string $type, SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get('powersports-data/type/get-years/'.$this->segment($type).'/'.$this->sort($sort)); }
    public function yearsRangeByType(string $type, int $minYear, int $maxYear, SortDirection|string $sort = SortDirection::Desc): ApiResponse { return $this->client->get('powersports-data/type/get-years/range/'.$this->segment($type)."/{$minYear}/{$maxYear}/".$this->sort($sort)); }
    public function makesByType(string $type, SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get('powersports-data/type/get-makes/'.$this->segment($type).'/'.$this->sort($sort)); }
    public function makesByYearRangeAndType(string $type, int $minYear, int $maxYear, SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get('powersports-data/type/get-makes-by-year/range/'.$this->segment($type)."/{$minYear}/{$maxYear}/".$this->sort($sort)); }
    public function makesByYearAndType(string $type, int $year, SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get('powersports-data/type/get-makes-by-year/'.$this->segment($type).'/'.$year.'/'.$this->sort($sort)); }
    public function modelsByYearMakeAndType(string $type, int $year, string $make, SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get('powersports-data/type/get-models-by-year-make/'.$this->segment($type).'/'.$year.'/'.$this->segment($make).'/'.$this->sort($sort)); }
    public function subModelsByYearMakeModelAndType(string $type, int $year, string $make, string $model, SortDirection|string $sort = SortDirection::Asc): ApiResponse { return $this->client->get('powersports-data/type/get-sub-models-by-year-make/'.$this->segment($type).'/'.$year.'/'.$this->segment($make).'/'.$this->segment($model).'/'.$this->sort($sort)); }
}
