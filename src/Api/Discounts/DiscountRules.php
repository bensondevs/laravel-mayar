<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Discounts;

use InvalidArgumentException;

final class DiscountRules
{
    public function __construct(
        public readonly string $discountType,
        public readonly string $eligibleCustomerType,
        public readonly int $minimumPurchase,
        public readonly int $value,
        public readonly int $totalCoupons,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        foreach (['discountType', 'eligibleCustomerType', 'minimumPurchase', 'value', 'totalCoupons'] as $key) {
            if (! array_key_exists($key, $data)) {
                throw new InvalidArgumentException("Discount rules is missing required key [{$key}].");
            }
        }

        if (! is_string($data['discountType']) || ! is_string($data['eligibleCustomerType'])) {
            throw new InvalidArgumentException('Discount rules discountType and eligibleCustomerType must be strings.');
        }

        if (! is_numeric($data['minimumPurchase']) || ! is_numeric($data['value']) || ! is_numeric($data['totalCoupons'])) {
            throw new InvalidArgumentException('Discount rules minimumPurchase, value, and totalCoupons must be numeric.');
        }

        return new self(
            discountType: $data['discountType'],
            eligibleCustomerType: $data['eligibleCustomerType'],
            minimumPurchase: (int) $data['minimumPurchase'],
            value: (int) $data['value'],
            totalCoupons: (int) $data['totalCoupons'],
        );
    }

    /**
     * @return array{discountType: string, eligibleCustomerType: string, minimumPurchase: int, value: int, totalCoupons: int}
     */
    public function toArray(): array
    {
        return [
            'discountType' => $this->discountType,
            'eligibleCustomerType' => $this->eligibleCustomerType,
            'minimumPurchase' => $this->minimumPurchase,
            'value' => $this->value,
            'totalCoupons' => $this->totalCoupons,
        ];
    }
}
