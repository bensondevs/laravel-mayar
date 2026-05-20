<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\CreditBasedProduct\CreditBasedProduct;
use Bensondevs\Mayar\Api\CreditBasedProduct\CreditUsageBalanceResult;
use Bensondevs\Mayar\Tests\Feature\CreditBasedProduct\CreditBasedProductFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('gets customer balance for credit usage product', function (): void {
    Http::fake([
        'https://api.mayar.club/credit/v1/credit/customer/balance*' => Http::response(
            body: CreditBasedProductFixtures::balanceResponse(),
        ),
    ]);

    $result = CreditBasedProduct::customerBalance([
        'productId' => CreditBasedProductFixtures::productId(),
        'customerId' => CreditBasedProductFixtures::customerId(),
    ]);

    expect($result)->toBeInstanceOf(CreditUsageBalanceResult::class)
        ->and($result->customerBalance)->toBe(30001.0)
        ->and($result->customerMainBalance)->toBe(29801.0)
        ->and($result->customerBalanceAddon)->toBe(500.0);

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/credit/v1/credit/customer/balance?productId=' . CreditBasedProductFixtures::productId() . '&customerId=' . CreditBasedProductFixtures::customerId();
    });
});

it('maps customerBalanceMain when customerMainBalance is absent', function (): void {
    Http::fake([
        'https://api.mayar.club/credit/v1/credit/customer/balance*' => Http::response(
            body: [
                'statusCode' => 200,
                'customerBalance' => 100,
                'customerBalanceMain' => 80,
                'customerBalanceAddon' => 20,
            ],
        ),
    ]);

    $result = CreditBasedProduct::customerBalance([
        'productId' => CreditBasedProductFixtures::productId(),
        'customerId' => CreditBasedProductFixtures::customerId(),
    ]);

    expect($result->customerMainBalance)->toBe(80.0);
});

it('throws validation exception when customer id is missing for balance', function (): void {
    CreditBasedProduct::customerBalance([
        'productId' => CreditBasedProductFixtures::productId(),
    ]);
})->throws(ValidationException::class);
