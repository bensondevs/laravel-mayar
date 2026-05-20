<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\CreditBasedProduct\CreditBasedProduct;
use Bensondevs\Mayar\Tests\Feature\CreditBasedProduct\CreditBasedProductFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('spends credit for credit usage product', function (): void {
    Http::fake([
        'https://api.mayar.club/credit/v1/credit/customer/spend' => Http::response(
            body: CreditBasedProductFixtures::spendResponse(),
        ),
    ]);

    $ok = CreditBasedProduct::spend([
        'productId' => CreditBasedProductFixtures::productId(),
        'customerId' => CreditBasedProductFixtures::customerId(),
        'amount' => 10,
    ]);

    expect($ok)->toBeTrue();

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/credit/v1/credit/customer/spend') {
            return false;
        }

        $body = $request->data();

        return $body['productId'] === CreditBasedProductFixtures::productId()
            && $body['customerId'] === CreditBasedProductFixtures::customerId()
            && $body['amount'] === 10;
    });
});

it('throws validation exception when spending without customer id', function (): void {
    CreditBasedProduct::spend([
        'productId' => CreditBasedProductFixtures::productId(),
        'amount' => 10,
    ]);
})->throws(ValidationException::class);
