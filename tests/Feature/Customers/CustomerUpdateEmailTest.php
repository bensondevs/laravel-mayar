<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\Customers\Customer;
use Bensondevs\Mayar\Tests\Feature\Customers\CustomerFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('updates a customer email', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/customer/update' => Http::response(
            body: CustomerFixtures::customerUpdateResponse(),
        ),
    ]);

    $success = Customer::updateEmail([
        'fromEmail' => 'mraihanna19@gmail.com',
        'toEmail' => 'mraihannanewpatch@gmail.com',
    ]);

    expect($success)->toBeTrue();

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/customer/update') {
            return false;
        }

        $body = $request->data();

        return $body['fromEmail'] === 'mraihanna19@gmail.com'
            && $body['toEmail'] === 'mraihannanewpatch@gmail.com';
    });
});

it('throws validation exception when update email payload is invalid', function (): void {
    Customer::updateEmail([
        'fromEmail' => 'mraihanna19@gmail.com',
    ]);
})->throws(ValidationException::class);
