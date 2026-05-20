<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Exceptions;

class MayarRequestException extends MayarException
{
    public function __construct(
        string $message,
        public readonly ?int $statusCode = null,
        public readonly ?string $mayarMessage = null,
        public readonly ?array $response = null,
    ) {
        parent::__construct(message: $message, code: $statusCode ?? 0);
    }
}
