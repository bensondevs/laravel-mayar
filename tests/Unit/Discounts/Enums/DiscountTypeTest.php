<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\Discounts\Enums\DiscountType;

it('resolves discount type from string', function (): void {
    expect(DiscountType::find('monetary'))->toBe(DiscountType::Monetary)
        ->and(DiscountType::find('percentage'))->toBe(DiscountType::Percentage)
        ->and(DiscountType::find('invalid'))->toBeNull();
});
