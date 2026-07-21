<?php

namespace CodebyRay\CarListApiLaravel\Resources;

use CodebyRay\CarListApiLaravel\Client;
use CodebyRay\CarListApiLaravel\Enums\SortDirection;

abstract readonly class Resource
{
    public function __construct(protected Client $client) {}

    protected function segment(string|int $value): string { return rawurlencode((string) $value); }
    protected function sort(SortDirection|string $sort): string
    {
        $value = $sort instanceof SortDirection ? $sort->value : strtolower($sort);
        if (! in_array($value, ['asc', 'desc'], true)) throw new \InvalidArgumentException('Sort direction must be asc or desc.');
        return $value;
    }
}
