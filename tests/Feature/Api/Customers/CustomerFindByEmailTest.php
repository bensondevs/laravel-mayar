<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\Customers\Customer;
use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Tests\Feature\Api\Customers\CustomerFixtures;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;
use Illuminate\Support\Facades\Http;

it('finds a customer by email', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/customer/detail*' => Http::response(
            body: CustomerFixtures::customerDetailResponse(),
        ),
    ]);

    $customer = Customer::findByEmail('testingmayar@gmail.com');

    expect($customer)->toBeInstanceOf(Customer::class)
        ->and($customer->id)->toBe('22eb6224-c20a-4bc2-9b99-13b21e7048c6')
        ->and($customer->name)->toBe('Test Mayar')
        ->and($customer->user['name'])->toBe('GlazerOut')
        ->and($customer->user['account']['logo']['fileType'])->toBe('jpeg');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/customer/detail?email=testingmayar%40gmail.com';
    });
});

it('returns null when customer is not found by email', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/customer/detail*' => Http::response([
            'statusCode' => HttpStatusCode::NotFound->value,
            'messages' => 'Not Found',
            'data' => [],
        ]),
    ]);

    expect(Customer::findByEmail('missing@example.com'))->toBeNull();
});

it('find by email or fail throws when customer is missing', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/customer/detail*' => Http::response([
            'statusCode' => HttpStatusCode::NotFound->value,
            'messages' => 'Not Found',
            'data' => [],
        ]),
    ]);

    Customer::findByEmailOrFail('missing@example.com');
})->throws(MayarNotFoundException::class);
