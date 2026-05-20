<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\CreditBasedProduct\CreditBasedProduct;
use Bensondevs\Mayar\Api\CreditBasedProduct\CreditUsageRegisterCustomerResult;
use Bensondevs\Mayar\Tests\Feature\Api\CreditBasedProduct\CreditBasedProductFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('registers a new customer for credit usage product', function (): void {
    Http::fake([
        'https://api.mayar.club/credit/v1/credit/credit-usage/customer/regist' => Http::response(
            body: CreditBasedProductFixtures::registerResponse(),
        ),
    ]);

    $result = CreditBasedProduct::registerCustomer([
        'productId' => CreditBasedProductFixtures::registerProductId(),
        'trialCredit' => 300,
        'customerInfo' => [
            'name' => 'john doe',
            'email' => 'johndoe@gmail.com',
            'mobile' => '08777777777',
        ],
    ]);

    expect($result)->toBeInstanceOf(CreditUsageRegisterCustomerResult::class)
        ->and($result->customerId)->toBe('bcf56452-ec21-4791-aca2-7a41b033e9d2')
        ->and($result->paymentLinkId)->toBe(CreditBasedProductFixtures::registerProductId());

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/credit/v1/credit/credit-usage/customer/regist') {
            return false;
        }

        $body = $request->data();

        return $body['productId'] === CreditBasedProductFixtures::registerProductId()
            && $body['trialCredit'] === 300
            && $body['customerInfo']['email'] === 'johndoe@gmail.com';
    });
});

it('throws validation exception when register customer info is invalid', function (): void {
    CreditBasedProduct::registerCustomer([
        'productId' => CreditBasedProductFixtures::registerProductId(),
        'customerInfo' => [],
    ]);
})->throws(ValidationException::class);
