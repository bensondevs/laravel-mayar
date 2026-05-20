<?php

declare(strict_types=1);

use Bensondevs\Mayar\Discounts\CouponCode;
use Bensondevs\Mayar\Discounts\Discount;
use Bensondevs\Mayar\Discounts\DiscountRules;
use Bensondevs\Mayar\Tests\Feature\Discounts\DiscountFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('creates a discount via save', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/coupon/create' => Http::response(
            body: DiscountFixtures::discountCreateResponse(),
        ),
    ]);

    $discount = new Discount;
    $discount->name = 'Diskon Murmer';
    $discount->expiredAt = '2030-01-01T09:06:14.933Z';
    $discount->products = [];
    $discount->setDiscount([
        'discountType' => 'monetary',
        'eligibleCustomerType' => 'all',
        'minimumPurchase' => 500000,
        'value' => 100000,
        'totalCoupons' => 100,
    ]);
    $discount->setCoupon([
        'code' => 'haribaik',
        'type' => 'reusable',
    ]);

    $discount->save();

    expect($discount->id)->toBe('c9aa143a-53f9-44e7-a9c6-83229ad7199b')
        ->and($discount->discountType)->toBe('monetary')
        ->and($discount->coupons)->toHaveCount(1);

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/coupon/create') {
            return false;
        }

        $body = $request->data();

        return $body['name'] === 'Diskon Murmer'
            && $body['discount']['discountType'] === 'monetary'
            && $body['coupon']['code'] === 'haribaik'
            && $body['products'] === [];
    });
});

it('creates a discount via create', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/coupon/create' => Http::response(
            body: DiscountFixtures::discountCreateResponse(),
        ),
    ]);

    $discount = Discount::create([
        'name' => 'Diskon Murmer',
        'expiredAt' => '2030-01-01T09:06:14.933Z',
        'products' => [],
        'discount' => [
            'discountType' => 'monetary',
            'eligibleCustomerType' => 'all',
            'minimumPurchase' => 500000,
            'value' => 100000,
            'totalCoupons' => 100,
        ],
        'coupon' => [
            'code' => 'haribaik',
            'type' => 'reusable',
        ],
    ]);

    expect($discount->id)->toBe('c9aa143a-53f9-44e7-a9c6-83229ad7199b')
        ->and($discount->exists())->toBeTrue();

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/coupon/create'
            && $request->data()['coupon']['code'] === 'haribaik';
    });
});

it('throws logic exception when create is called with an id', function (): void {
    Discount::create(['id' => DiscountFixtures::discountCreateId()]);
})->throws(LogicException::class);

it('creates a discount with DTOs', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/coupon/create' => Http::response(
            body: DiscountFixtures::discountCreateResponse(),
        ),
    ]);

    $discount = new Discount([
        'name' => 'Diskon Murmer',
        'expiredAt' => '2030-01-01T09:06:14.933Z',
        'products' => [],
    ]);

    $discount->setDiscount(new DiscountRules(
        discountType: 'monetary',
        eligibleCustomerType: 'all',
        minimumPurchase: 500000,
        value: 100000,
        totalCoupons: 100,
    ));
    $discount->setCoupon(new CouponCode(code: 'haribaik', type: 'reusable'));

    $discount->save();

    expect($discount->exists())->toBeTrue();
});

it('throws validation exception when create payload is missing coupon', function (): void {
    $discount = new Discount([
        'name' => 'Diskon Murmer',
        'expiredAt' => '2030-01-01T09:06:14.933Z',
        'products' => [],
    ]);
    $discount->setDiscount([
        'discountType' => 'monetary',
        'eligibleCustomerType' => 'all',
        'minimumPurchase' => 500000,
        'value' => 100000,
        'totalCoupons' => 100,
    ]);

    $discount->save();
})->throws(ValidationException::class);

it('throws when save is called on an existing discount', function (): void {
    $discount = Discount::fromMayar([
        'id' => DiscountFixtures::discountCreateId(),
    ]);

    $discount->save();
})->throws(LogicException::class);
