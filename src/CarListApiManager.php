<?php

namespace CodebyRay\CarListApi;

use CodebyRay\CarListApi\Resources\Automotive;
use CodebyRay\CarListApi\Resources\Powersports;
use CodebyRay\CarListApi\Resources\VinDecoder;
use Illuminate\Http\Client\Factory;
use InvalidArgumentException;

final class CarListApiManager
{
    /** @param array<string, mixed> $config */
    public function __construct(
        private readonly Factory $http,
        private array $config,
        private ?string $tokenOverride = null,
    ) {}

    public function withToken(string $token): self
    {
        $clone = clone $this;
        $clone->tokenOverride = $token;

        return $clone;
    }

    public function automotive(): Automotive
    {
        return new Automotive($this->client());
    }

    public function powersports(): Powersports
    {
        return new Powersports($this->client());
    }

    public function vinDecoder(): VinDecoder
    {
        return new VinDecoder($this->client());
    }

    public function client(): Client
    {
        $token = $this->tokenOverride ?? ($this->config['token'] ?? null);

        if (! is_string($token) || trim($token) === '') {
            throw new InvalidArgumentException(
                'A Car List API token is required. Set CAR_LIST_API_TOKEN or call withToken().'
            );
        }

        $version = trim((string) ($this->config['version'] ?? 'v1'), '/');

        if ($version === '') {
            throw new InvalidArgumentException(
                'A Car List API version is required. Set CAR_LIST_API_VERSION, for example v1.'
            );
        }

        $retry = is_array($this->config['retry'] ?? null)
            ? $this->config['retry']
            : [];

        $configuredUserAgent = $this->config['user_agent'] ?? null;
        $userAgent = is_string($configuredUserAgent) && trim($configuredUserAgent) !== ''
            ? trim($configuredUserAgent)
            : Package::userAgent();

        return new Client(
            http: $this->http,
            baseUrl: rtrim((string) ($this->config['base_url'] ?? 'https://carlistapi.com/api'), '/'),
            version: $version,
            token: trim($token),
            timeout: max(1, (int) ($this->config['timeout'] ?? 15)),
            connectTimeout: max(1, (int) ($this->config['connect_timeout'] ?? 5)),
            retryTimes: max(0, (int) ($retry['times'] ?? $this->config['retry_times'] ?? 2)),
            retrySleepMs: max(0, (int) ($retry['sleep_ms'] ?? $retry['sleep'] ?? $this->config['retry_sleep_ms'] ?? 200)),
            userAgent: $userAgent,
        );
    }
}
