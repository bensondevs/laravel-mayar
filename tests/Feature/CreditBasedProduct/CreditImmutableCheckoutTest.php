<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\CreditBasedProduct\CreditBasedProduct;
use Bensondevs\Mayar\Api\CreditBasedProduct\ImmutableCheckoutLinkResult;
use Bensondevs\Mayar\Tests\Feature\CreditBasedProduct\CreditBasedProductFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('generates immutable checkout link', function (): void {
    Http::fake([
        'https://api.mayar.club/credit/v1/credit/generate/immutable/checkout' => Http::response(
            body: CreditBasedProductFixtures::checkoutResponse(),
        ),
    ]);

    $result = CreditBasedProduct::generateImmutableCheckout([
        'productId' => CreditBasedProductFixtures::productId(),
        'creditAmount' => 1000,
        'customerInfo' => [
            'name' => 'memberTambahan',
            'email' => 'tambahan@gg.com',
            'mobile' => '08777777799',
        ],
    ]);

    expect($result)->toBeInstanceOf(ImmutableCheckoutLinkResult::class)
        ->and($result->creditUsageImmutableCheckoutUrl)->toBe('https://example.myr.id/pl/test?immutable=abc')
        ->and($result->paymentLinkUrl)->toBe('https://example.myr.id/pl/test');

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/credit/v1/credit/generate/immutable/checkout') {
            return false;
        }

        $body = $request->data();

        return $body['productId'] === CreditBasedProductFixtures::productId()
            && $body['creditAmount'] === 1000
            && $body['customerInfo']['name'] === 'memberTambahan';
    });
});

it('throws validation exception when immutable checkout missing product id', function (): void {
    CreditBasedProduct::generateImmutableCheckout([
        'customerInfo' => [
            'name' => 'x',
            'email' => 'x@y.com',
            'mobile' => '1',
        ],
    ]);
})->throws(ValidationException::class);
