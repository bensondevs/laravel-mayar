<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\Products\Enums\ProductType;

it('resolves product types via find', function (): void {
    expect(ProductType::find('ebook'))->toBe(ProductType::Ebook)
        ->and(ProductType::find('generic_link'))->toBe(ProductType::GenericLink)
        ->and(ProductType::find('invalid'))->toBeNull();
});

it('exposes all mayar type backing values in options', function (): void {
    expect(ProductType::values())->toContain('ebook', 'saas', 'payment_request');
});
