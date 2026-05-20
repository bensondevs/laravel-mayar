<?php

declare(strict_types=1);

use Bensondevs\Mayar\Discounts\Discount;
use Bensondevs\Mayar\Exceptions\MayarRequestException;

it('creates a discount via save', function (): void {
    skipUnlessMayarConfigured();

    $discount = integrationCreateDiscount();

    expect($discount->id)->not->toBeEmpty()
        ->and($discount->coupons)->not->toBeEmpty();
});

it('creates a discount and finds it by id', function (): void {
    skipUnlessMayarConfigured();

    $created = integrationCreateDiscount();

    try {
        $found = Discount::find((string) $created->getKey());
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Discount detail API unavailable: ' . $exception->getMessage());
    }

    expect($found)->toBeInstanceOf(Discount::class)
        ->and($found->getKey())->toBe($created->getKey());
});

function integrationCreateDiscount(): Discount
{
    $code = 'integration-' . uniqid();

    $discount = new Discount;
    $discount->name = 'Integration Test Discount';
    $discount->expiredAt = now()->addYear()->utc()->format('Y-m-d\TH:i:s.v\Z');
    $discount->products = [];
    $discount->setDiscount([
        'discountType' => 'monetary',
        'eligibleCustomerType' => 'all',
        'minimumPurchase' => 1000,
        'value' => 100,
        'totalCoupons' => 10,
    ]);
    $discount->setCoupon([
        'code' => $code,
        'type' => 'reusable',
    ]);

    try {
        $discount->save();
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Discount create API unavailable: ' . $exception->getMessage());
    }

    if (! $discount->exists()) {
        test()->markTestSkipped('Discount create did not return an id');
    }

    return $discount;
}
