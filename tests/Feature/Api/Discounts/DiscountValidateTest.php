<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\Discounts\CouponValidationResult;
use Bensondevs\Mayar\Api\Discounts\Discount;
use Bensondevs\Mayar\Tests\Feature\Api\Discounts\DiscountFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('validates a coupon code', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/coupon/validate' => Http::response(
            body: DiscountFixtures::discountValidateResponse(),
        ),
    ]);

    $result = Discount::validate([
        'paymentLinkId' => '4d26ea37-d093-4b92-8f5f-0faec64d65b0',
        'couponCode' => 'NFRBFUK',
        'finalAmount' => 0,
        'tickets' => [],
        'customerEmail' => '',
    ]);

    expect($result)->toBeInstanceOf(CouponValidationResult::class)
        ->and($result->valid)->toBeTrue()
        ->and($result->coupon['code'])->toBe('143KYCN');

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/coupon/validate') {
            return false;
        }

        $body = $request->data();

        return $body['paymentLinkId'] === '4d26ea37-d093-4b92-8f5f-0faec64d65b0'
            && $body['couponCode'] === 'NFRBFUK'
            && $body['finalAmount'] === 0
            && $body['tickets'] === [];
    });
});

it('throws validation exception when validate payload is missing coupon code', function (): void {
    Discount::validate([
        'paymentLinkId' => '4d26ea37-d093-4b92-8f5f-0faec64d65b0',
        'finalAmount' => 0,
    ]);
})->throws(ValidationException::class);
