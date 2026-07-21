<?php

namespace CodebyRay\CarListApiLaravel\Response;

use Illuminate\Http\Client\Response;

final readonly class ApiResponse
{
    /** @param array<string, array<int, string>> $headers */
    public function __construct(
        public mixed $data,
        public int $status,
        public array $headers,
    ) {}

    public static function fromLaravelResponse(Response $response): self
    {
        return new self($response->json(), $response->status(), $response->headers());
    }

    public function header(string $name): ?string
    {
        foreach ($this->headers as $key => $values) {
            if (strcasecmp($key, $name) === 0) {
                return $values[0] ?? null;
            }
        }
        return null;
    }
}
