<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\CreditMembership;

final class CustomerCreditBalanceResult
{
    public function __construct(
        public readonly float $customerBalance,
        public readonly float $customerBalanceMembership,
        public readonly float $customerBalanceAddon,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromMayar(array $payload): self
    {
        return new self(
            customerBalance: (float) ($payload['customerBalance'] ?? 0),
            customerBalanceMembership: (float) ($payload['customerBalanceMembership'] ?? 0),
            customerBalanceAddon: (float) ($payload['customerBalanceAddon'] ?? 0),
        );
    }
}
