<?php

namespace CodebyRay\CarListApi\Resources;

use CodebyRay\CarListApi\Client;
use CodebyRay\CarListApi\Enums\SortDirection;

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
