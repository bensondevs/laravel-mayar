<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\PaymentRequests\PaymentRequest;
use Bensondevs\Mayar\Tests\Feature\Api\PaymentRequests\PaymentRequestFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('creates a payment request via save', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/payment/create' => Http::response(
            body: PaymentRequestFixtures::paymentRequestCreateResponse(),
        ),
    ]);

    $paymentRequest = new PaymentRequest;
    $paymentRequest->name = 'Azumii';
    $paymentRequest->email = 'user@example.com';
    $paymentRequest->amount = 170000;
    $paymentRequest->mobile = '08996136751';
    $paymentRequest->redirectUrl = 'https://example.com/redirect';
    $paymentRequest->description = 'Testing ReqPayment';
    $paymentRequest->expiredAt = '2025-12-29T09:41:09.401Z';

    $paymentRequest->save();

    expect($paymentRequest->id)->toBe('e890d24a-cfc0-4915-83d2-3166b9ffba9e')
        ->and($paymentRequest->transactionId)->toBe('040d5adb-1496-45de-8435-5cab16526a8c')
        ->and($paymentRequest->link)->toContain('ohsjrd3wko');

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/payment/create') {
            return false;
        }

        $body = $request->data();

        return $body['name'] === 'Azumii'
            && $body['email'] === 'user@example.com'
            && $body['amount'] === 170000;
    });
});

it('creates a payment request via create', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/payment/create' => Http::response(
            body: PaymentRequestFixtures::paymentRequestCreateResponse(),
        ),
    ]);

    $paymentRequest = PaymentRequest::create([
        'name' => 'Azumii',
        'email' => 'user@example.com',
        'amount' => 170000,
        'mobile' => '08996136751',
        'redirectUrl' => 'https://example.com/redirect',
        'description' => 'Testing ReqPayment',
        'expiredAt' => '2025-12-29T09:41:09.401Z',
    ]);

    expect($paymentRequest->id)->toBe('e890d24a-cfc0-4915-83d2-3166b9ffba9e')
        ->and($paymentRequest->exists())->toBeTrue();

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/payment/create'
            && $request->data()['amount'] === 170000;
    });
});

it('throws logic exception when create is called with an id', function (): void {
    PaymentRequest::create(['id' => 'e890d24a-cfc0-4915-83d2-3166b9ffba9e']);
})->throws(LogicException::class);

it('creates a payment request with constructor attributes', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/payment/create' => Http::response(
            body: PaymentRequestFixtures::paymentRequestCreateResponse(),
        ),
    ]);

    $paymentRequest = new PaymentRequest([
        'name' => 'Azumii',
        'email' => 'user@example.com',
        'amount' => 170000,
        'mobile' => '08996136751',
        'redirectUrl' => 'https://example.com/redirect',
        'description' => 'Testing ReqPayment',
        'expiredAt' => '2025-12-29T09:41:09.401Z',
    ]);

    $paymentRequest->save();

    expect($paymentRequest->exists())->toBeTrue();
});

it('throws validation exception when create payload is invalid', function (): void {
    $paymentRequest = new PaymentRequest([
        'name' => 'Azumii',
    ]);

    $paymentRequest->save();
})->throws(ValidationException::class);
