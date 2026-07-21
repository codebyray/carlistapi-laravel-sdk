<?php

namespace CodebyRay\CarListApiLaravel;

use CodebyRay\CarListApiLaravel\Exceptions\AuthenticationException;
use CodebyRay\CarListApiLaravel\Exceptions\AuthorizationException;
use CodebyRay\CarListApiLaravel\Exceptions\CarListApiException;
use CodebyRay\CarListApiLaravel\Exceptions\NotFoundException;
use CodebyRay\CarListApiLaravel\Exceptions\RateLimitException;
use CodebyRay\CarListApiLaravel\Exceptions\ServerException;
use CodebyRay\CarListApiLaravel\Exceptions\TransportException;
use CodebyRay\CarListApiLaravel\Exceptions\ValidationException;
use CodebyRay\CarListApiLaravel\Response\ApiResponse;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Throwable;

final readonly class Client
{
    public function __construct(
        private Factory $http,
        private string $baseUrl,
        private string $version,
        private string $token,
        private int $timeout = 15,
        private int $connectTimeout = 5,
        private int $retryTimes = 2,
        private int $retrySleepMs = 200,
        private string $userAgent = 'codebyray/carlistapi-laravel-sdk/dev',
    ) {}

    /** @param array<string, scalar|null> $query */
    public function get(string $path, array $query = []): ApiResponse
    {
        return $this->send(fn (PendingRequest $request): Response => $request->get($this->url($path), $query));
    }

    /** @param array<string, mixed> $data */
    public function post(string $path, array $data = []): ApiResponse
    {
        return $this->send(fn (PendingRequest $request): Response => $request->post($this->url($path), $data));
    }

    /** @param callable(PendingRequest): Response $callback */
    private function send(callable $callback): ApiResponse
    {
        try {
            $response = $callback($this->request());
        } catch (ConnectionException $e) {
            throw new TransportException('Unable to connect to the Car List API.', 0, $e);
        } catch (Throwable $e) {
            throw new TransportException('The Car List API request failed before a response was received.', 0, $e);
        }

        if ($response->successful()) {
            return ApiResponse::fromLaravelResponse($response);
        }

        $payload = $response->json();
        $message = is_array($payload) && is_string($payload['error'] ?? null)
            ? $payload['error']
            : 'Car List API request failed with HTTP '.$response->status().'.';

        throw match ($response->status()) {
            401 => new AuthenticationException($message, 401),
            403 => new AuthorizationException($message, 403),
            404 => new NotFoundException($message, 404),
            422 => new ValidationException($message, 422),
            429 => new RateLimitException($message, $this->intOrNull($payload['limit'] ?? null), $this->intOrNull($payload['used'] ?? null), $this->intOrNull($payload['remaining'] ?? null), is_string($payload['reset_at'] ?? null) ? $payload['reset_at'] : null),
            default => $response->serverError()
                ? new ServerException($message, $response->status())
                : new CarListApiException($message, $response->status()),
        };
    }

    private function request(): PendingRequest
    {
        $request = $this->http->acceptJson()->asJson()->withToken($this->token)->withUserAgent($this->userAgent)->timeout($this->timeout)->connectTimeout($this->connectTimeout);

        return $this->retryTimes > 0 ? $request->retry($this->retryTimes, $this->retrySleepMs, throw: false) : $request;
    }

    private function url(string $path): string
    {
        return rtrim($this->baseUrl, '/').'/'.trim($this->version, '/').'/'.ltrim($path, '/');
    }

    private function intOrNull(mixed $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }
}
