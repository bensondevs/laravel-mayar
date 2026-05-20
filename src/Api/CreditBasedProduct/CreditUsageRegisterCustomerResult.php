<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\CreditBasedProduct;

final class CreditUsageRegisterCustomerResult
{
    public function __construct(
        public readonly string $customerId,
        public readonly string $paymentLinkId,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromMayar(array $payload): self
    {
        return new self(
            customerId: (string) ($payload['customerId'] ?? ''),
            paymentLinkId: (string) ($payload['paymentLinkId'] ?? ''),
        );
    }
}
