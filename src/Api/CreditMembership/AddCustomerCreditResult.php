<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\CreditMembership;

final class AddCustomerCreditResult
{
    public function __construct(
        public readonly string $customerId,
        public readonly string $productId,
        public readonly string $membershipTierId,
        public readonly int $amount,
        public readonly float $customerBalance,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromMayar(array $payload): self
    {
        return new self(
            customerId: (string) ($payload['customerId'] ?? ''),
            productId: (string) ($payload['productId'] ?? ''),
            membershipTierId: (string) ($payload['membershipTierId'] ?? ''),
            amount: (int) ($payload['amount'] ?? 0),
            customerBalance: (float) ($payload['customerBalance'] ?? 0),
        );
    }
}
