<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\CreditMembership;

final class RegisterMembershipCustomerResult
{
    /**
     * @param  array<string, mixed>|null  $membershipCustomer
     */
    public function __construct(
        public readonly ?array $membershipCustomer = null,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromMayar(array $payload): self
    {
        $membershipCustomer = $payload['membershipCustomer'] ?? null;

        return new self(
            membershipCustomer: is_array($membershipCustomer) ? $membershipCustomer : null,
        );
    }
}
