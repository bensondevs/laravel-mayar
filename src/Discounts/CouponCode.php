<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Discounts;

use InvalidArgumentException;

final class CouponCode
{
    public function __construct(
        public readonly string $code,
        public readonly string $type,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        foreach (['code', 'type'] as $key) {
            if (! array_key_exists($key, $data)) {
                throw new InvalidArgumentException("Coupon code is missing required key [{$key}].");
            }
        }

        if (! is_string($data['code']) || ! is_string($data['type'])) {
            throw new InvalidArgumentException('Coupon code and type must be strings.');
        }

        return new self(
            code: $data['code'],
            type: $data['type'],
        );
    }

    /**
     * @return array{code: string, type: string}
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'type' => $this->type,
        ];
    }
}
