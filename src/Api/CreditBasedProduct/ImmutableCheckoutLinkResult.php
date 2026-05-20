<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\CreditBasedProduct;

final class ImmutableCheckoutLinkResult
{
    public function __construct(
        public readonly string $creditUsageImmutableCheckoutUrl,
        public readonly string $paymentLinkUrl,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromMayar(array $payload): self
    {
        return new self(
            creditUsageImmutableCheckoutUrl: (string) ($payload['creditUsageImmutableCheckoutUrl'] ?? ''),
            paymentLinkUrl: (string) ($payload['paymentLinkUrl'] ?? ''),
        );
    }
}
