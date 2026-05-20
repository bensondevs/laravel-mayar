<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Transactions;

final class DynamicQrCodeResult
{
    public function __construct(
        public readonly string $url,
        public readonly int $amount,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromMayar(array $payload): self
    {
        return new self(
            url: (string) ($payload['url'] ?? ''),
            amount: (int) ($payload['amount'] ?? 0),
        );
    }
}
