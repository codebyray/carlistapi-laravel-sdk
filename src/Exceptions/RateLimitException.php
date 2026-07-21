<?php

namespace CodebyRay\CarListApi\Exceptions;

class RateLimitException extends CarListApiException
{
    public function __construct(string $message, public readonly ?int $limit = null, public readonly ?int $used = null, public readonly ?int $remaining = null, public readonly ?string $resetAt = null)
    { parent::__construct($message, 429); }
}
