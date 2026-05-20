<?php

declare(strict_types=1);

use Bensondevs\Mayar\Enums\MayarMode;
use Bensondevs\Mayar\Http\Endpoint;

it('builds urls correctly and trims slashes', function (): void {
    $endpoint = new Endpoint(MayarMode::Sandbox);

    expect($endpoint->url('/customer'))->toBe('https://api.mayar.club/hl/v1/customer')
        ->and($endpoint->url('customer'))->toBe('https://api.mayar.club/hl/v1/customer');
});

it('exposes customer and product endpoint helpers', function (): void {
    $endpoint = new Endpoint(MayarMode::Production);

    expect($endpoint->customers())->toBe('https://api.mayar.id/hl/v1/customer')
        ->and($endpoint->customerByEmail())->toBe('https://api.mayar.id/hl/v1/customer/detail')
        ->and($endpoint->products())->toBe('https://api.mayar.id/hl/v1/product')
        ->and($endpoint->product('abc-123'))->toBe('https://api.mayar.id/hl/v1/product/abc-123');
});
