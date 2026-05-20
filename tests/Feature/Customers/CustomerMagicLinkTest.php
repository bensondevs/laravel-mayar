<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\Customers\Customer;
use Bensondevs\Mayar\Api\Customers\PortalMagicLinkResult;
use Bensondevs\Mayar\Tests\Feature\Customers\CustomerFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('sends a portal magic link to a customer email', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/customer/login/portal' => Http::response(
            body: CustomerFixtures::customerPortalLoginResponse(),
        ),
    ]);

    $result = Customer::sendPortalMagicLink('mraihannanewpatch@gmail.com');

    expect($result)->toBeInstanceOf(PortalMagicLinkResult::class)
        ->and($result->url)->toBe('Sudah kami kirim ke email anda');

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/customer/login/portal') {
            return false;
        }

        return $request->data()['email'] === 'mraihannanewpatch@gmail.com';
    });
});

it('throws validation exception when portal login email is empty', function (): void {
    Customer::sendPortalMagicLink('');
})->throws(ValidationException::class);
