<?php

declare(strict_types=1);

use Bensondevs\Mayar\PaymentRequests\PaymentRequest;
use Bensondevs\Mayar\Tests\Feature\PaymentRequests\PaymentRequestFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('edits a payment request via save on an existing instance', function (): void {
    $id = PaymentRequestFixtures::paymentRequestDetailId();

    Http::fake([
        'https://api.mayar.club/hl/v1/payment/edit' => Http::response(
            body: PaymentRequestFixtures::paymentRequestEditResponse(),
        ),
    ]);

    $paymentRequest = PaymentRequest::fromMayar([
        'id' => $id,
        'name' => 'Azumii',
        'email' => 'azumiikecee@gmail.com',
        'amount' => 100000,
        'mobile' => '08996136751',
        'redirectUrl' => 'https://web.mayar.id',
        'description' => 'Old description',
        'expiredAt' => '2025-12-29T09:41:09.401Z',
    ]);
    $paymentRequest->description = 'Ubah ReqPayment';
    $paymentRequest->amount = 100000;

    $paymentRequest->save();

    expect($paymentRequest->id)->toBe($id)
        ->and($paymentRequest->link)->toContain('ohsjrd3wko');

    Http::assertSent(function ($request) use ($id): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/payment/edit') {
            return false;
        }

        $body = $request->data();

        return $body['id'] === $id
            && $body['description'] === 'Ubah ReqPayment'
            && $body['amount'] === 100000;
    });
});

it('edits a payment request via static update', function (): void {
    $id = PaymentRequestFixtures::paymentRequestDetailId();

    Http::fake([
        'https://api.mayar.club/hl/v1/payment/edit' => Http::response(
            body: PaymentRequestFixtures::paymentRequestEditResponse(),
        ),
    ]);

    $paymentRequest = PaymentRequest::update([
        'id' => $id,
        'name' => 'Azumii',
        'email' => 'azumiikecee@gmail.com',
        'amount' => 100000,
        'mobile' => '089961367511',
        'redirectUrl' => 'https://web.mayar.id',
        'description' => 'Ubah ReqPayment',
        'expiredAt' => '2025-12-29T09:41:09.401Z',
    ]);

    expect($paymentRequest->id)->toBe($id);

    Http::assertSent(function ($request) use ($id): bool {
        $body = $request->data();

        return $body['id'] === $id
            && $body['mobile'] === '089961367511';
    });
});

it('throws validation exception when edit payload is missing required fields', function (): void {
    PaymentRequest::update([
        'id' => PaymentRequestFixtures::paymentRequestDetailId(),
        'description' => 'missing other fields',
    ]);
})->throws(ValidationException::class);
