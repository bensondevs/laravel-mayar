<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\Customers\Customer;
use Bensondevs\Mayar\Tests\Feature\Customers\CustomerFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('creates a customer via save', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/customer/create' => Http::response(
            body: CustomerFixtures::customerCreateResponse(),
        ),
    ]);

    $customer = new Customer;
    $customer->name = 'Raihan Nur Azmii';
    $customer->email = 'mraihanna19@gmail.com';
    $customer->mobile = '089912345678';

    $customer->save();

    expect($customer->id)->toBe('b0356d4c-516a-403e-abfe-b144da7068b4')
        ->and($customer->exists())->toBeTrue()
        ->and($customer->userId)->toBe('348e083d-315a-4e5c-96b1-5a2a98c48413');

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/customer/create') {
            return false;
        }

        $body = $request->data();

        return $body['name'] === 'Raihan Nur Azmii'
            && $body['email'] === 'mraihanna19@gmail.com'
            && $body['mobile'] === '089912345678';
    });
});

it('creates a customer via create', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/customer/create' => Http::response(
            body: CustomerFixtures::customerCreateResponse(),
        ),
    ]);

    $customer = Customer::create([
        'name' => 'Raihan Nur Azmii',
        'email' => 'mraihanna19@gmail.com',
        'mobile' => '089912345678',
    ]);

    expect($customer->id)->toBe(CustomerFixtures::customerCreateId())
        ->and($customer->exists())->toBeTrue();
});

it('throws validation exception when create payload is invalid', function (): void {
    $customer = new Customer([
        'name' => 'Raihan Nur Azmii',
    ]);

    $customer->save();
})->throws(ValidationException::class);

it('throws when save is called on an existing customer', function (): void {
    $customer = Customer::fromMayar([
        'id' => CustomerFixtures::customerCreateId(),
    ]);

    $customer->save();
})->throws(LogicException::class);
