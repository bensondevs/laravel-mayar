<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Discounts;

final class CouponValidationResult
{
    /**
     * @param  array<string, mixed>|null  $coupon
     */
    public function __construct(
        public readonly bool $valid,
        public readonly ?array $coupon = null,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromMayar(array $payload): self
    {
        return new self(
            valid: (bool) ($payload['valid'] ?? false),
            coupon: is_array($payload['coupon'] ?? null) ? $payload['coupon'] : null,
        );
    }
}
