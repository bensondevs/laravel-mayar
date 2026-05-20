<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\Discounts\Enums\CouponType;

it('resolves coupon type from string', function (): void {
    expect(CouponType::find('reusable'))->toBe(CouponType::Reusable)
        ->and(CouponType::find('onetime'))->toBe(CouponType::Onetime)
        ->and(CouponType::find('invalid'))->toBeNull();
});
