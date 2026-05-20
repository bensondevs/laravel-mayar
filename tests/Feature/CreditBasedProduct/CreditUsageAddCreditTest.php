<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\CreditBasedProduct\CreditBasedProduct;
use Bensondevs\Mayar\Api\CreditBasedProduct\CreditUsageAddCreditResult;
use Bensondevs\Mayar\Tests\Feature\CreditBasedProduct\CreditBasedProductFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('adds credit for credit usage product', function (): void {
    Http::fake([
        'https://api.mayar.club/credit/v1/credit/customer/add-credit' => Http::response(
            body: CreditBasedProductFixtures::addCreditResponse(),
        ),
    ]);

    $result = CreditBasedProduct::addCredit([
        'productId' => CreditBasedProductFixtures::productId(),
        'customerId' => CreditBasedProductFixtures::customerIdForHistory(),
        'amount' => 100,
    ]);

    expect($result)->toBeInstanceOf(CreditUsageAddCreditResult::class)
        ->and($result->customerId)->toBe('faa4ee60-cf45-4043-b964-303890713bb9')
        ->and($result->productId)->toBe(CreditBasedProductFixtures::productId())
        ->and($result->amount)->toBe(100)
        ->and($result->customerBalance)->toBe(911090.0);

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/credit/v1/credit/customer/add-credit') {
            return false;
        }

        $body = $request->data();

        return $body['productId'] === CreditBasedProductFixtures::productId()
            && $body['customerId'] === CreditBasedProductFixtures::customerIdForHistory()
            && $body['amount'] === 100;
    });
});

it('throws validation exception when add credit without customer id', function (): void {
    CreditBasedProduct::addCredit([
        'productId' => CreditBasedProductFixtures::productId(),
        'amount' => 100,
    ]);
})->throws(ValidationException::class);
