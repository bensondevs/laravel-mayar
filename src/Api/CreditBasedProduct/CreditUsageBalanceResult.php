<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\CreditBasedProduct;

final class CreditUsageBalanceResult
{
    public function __construct(
        public readonly float $customerBalance,
        public readonly float $customerMainBalance,
        public readonly float $customerBalanceAddon,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromMayar(array $payload): self
    {
        $main = $payload['customerMainBalance'] ?? $payload['customerBalanceMain'] ?? 0;

        return new self(
            customerBalance: (float) ($payload['customerBalance'] ?? 0),
            customerMainBalance: (float) $main,
            customerBalanceAddon: (float) ($payload['customerBalanceAddon'] ?? 0),
        );
    }
}
