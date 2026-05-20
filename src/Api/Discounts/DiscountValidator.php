<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Discounts;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class DiscountValidator
{
    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateForCreate(array $payload): void
    {
        $validator = Validator::make($payload, [
            'name' => ['required', 'string'],
            'expiredAt' => ['required', 'string'],
            'products' => ['present', 'array'],
            'discount' => ['required', 'array'],
            'discount.discountType' => ['required', 'string', 'in:monetary,percentage'],
            'discount.eligibleCustomerType' => ['required', 'string'],
            'discount.minimumPurchase' => ['required', 'integer', 'min:0'],
            'discount.value' => ['required', 'integer', 'min:0'],
            'discount.totalCoupons' => ['required', 'integer', 'min:1'],
            'coupon' => ['required', 'array'],
            'coupon.code' => ['required', 'string'],
            'coupon.type' => ['required', 'string', 'in:reusable,onetime'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateForValidate(array $payload): void
    {
        $validator = Validator::make($payload, [
            'paymentLinkId' => ['required', 'string'],
            'couponCode' => ['required', 'string'],
            'finalAmount' => ['required', 'integer', 'min:0'],
            'tickets' => ['present', 'array'],
            'customerEmail' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
