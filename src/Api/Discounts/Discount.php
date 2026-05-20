<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Discounts;

use Bensondevs\Mayar\Models\MayarResource;
use DateTimeInterface;
use LogicException;

class Discount extends MayarResource
{
    /**
     * @var array<string, mixed>|null
     */
    protected ?array $discountRules = null;

    /**
     * @var array{code: string, type: string}|null
     */
    protected ?array $couponCode = null;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'createdAt' => 'integer',
            'expiredAt' => 'integer',
            'minimumPurchase' => 'integer',
            'totalCoupons' => 'integer',
            'value' => 'integer',
        ];
    }

    public static function query(): DiscountQuery
    {
        return new DiscountQuery(static::class);
    }

    public static function find(mixed $id): ?static
    {
        return static::query()->find((string) $id);
    }

    public static function findOrFail(mixed $id): static
    {
        return static::query()->findOrFail((string) $id);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function validate(array $attributes): CouponValidationResult
    {
        $payload = [
            'paymentLinkId' => $attributes['paymentLinkId'] ?? null,
            'couponCode' => $attributes['couponCode'] ?? null,
            'finalAmount' => $attributes['finalAmount'] ?? null,
            'tickets' => $attributes['tickets'] ?? [],
            'customerEmail' => $attributes['customerEmail'] ?? '',
        ];

        DiscountValidator::validateForValidate($payload);

        $endpoint = new DiscountEndpoint;
        $response = static::mayarClient()->postUrl($endpoint->validate(), $payload);

        return CouponValidationResult::fromMayar($response['data'] ?? []);
    }

    public function exists(): bool
    {
        return filled($this->getKey());
    }

    public function isNew(): bool
    {
        return ! $this->exists();
    }

    public function setDiscount(DiscountRules | array $rules): static
    {
        $this->discountRules = $this->normalizeDiscountRules($rules)->toArray();

        return $this;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDiscount(): ?array
    {
        if ($this->discountRules !== null) {
            return $this->discountRules;
        }

        $attributeRules = $this->attributes['discount'] ?? null;

        if (! is_array($attributeRules)) {
            return null;
        }

        return $this->normalizeDiscountRules($attributeRules)->toArray();
    }

    public function setCoupon(CouponCode | array $coupon): static
    {
        $this->couponCode = $this->normalizeCouponCode($coupon)->toArray();

        return $this;
    }

    /**
     * @return array{code: string, type: string}|null
     */
    public function getCoupon(): ?array
    {
        if ($this->couponCode !== null) {
            return $this->couponCode;
        }

        $attributeCoupon = $this->attributes['coupon'] ?? null;

        if (! is_array($attributeCoupon)) {
            return null;
        }

        return $this->normalizeCouponCode($attributeCoupon)->toArray();
    }

    public function save(): static
    {
        if ($this->exists()) {
            throw new LogicException('Discount cannot be updated via the Mayar API.');
        }

        $payload = $this->toCreatePayload();

        DiscountValidator::validateForCreate($payload);

        $endpoint = new DiscountEndpoint;
        $response = static::mayarClient()->postUrl($endpoint->create(), $payload);

        $this->fillFromMayar($response['data'] ?? []);

        return $this;
    }

    public function refresh(): static
    {
        $fresh = static::query()->findOrFail((string) $this->getKey());

        $this->syncAttributes($fresh->getAttributes());

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function toCreatePayload(): array
    {
        return [
            'name' => $this->attributes['name'] ?? null,
            'expiredAt' => $this->formatExpiredAt($this->attributes['expiredAt'] ?? null),
            'products' => $this->attributes['products'] ?? [],
            'discount' => $this->getDiscount(),
            'coupon' => $this->getCoupon(),
        ];
    }

    protected function formatExpiredAt(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d\TH:i:s.v\Z');
        }

        return (string) $value;
    }

    protected function normalizeDiscountRules(DiscountRules | array $rules): DiscountRules
    {
        if ($rules instanceof DiscountRules) {
            return $rules;
        }

        return DiscountRules::fromArray($rules);
    }

    protected function normalizeCouponCode(CouponCode | array $coupon): CouponCode
    {
        if ($coupon instanceof CouponCode) {
            return $coupon;
        }

        return CouponCode::fromArray($coupon);
    }
}
