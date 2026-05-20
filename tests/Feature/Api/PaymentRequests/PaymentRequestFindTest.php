<?php

declare(strict_types=1);

use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Api\PaymentRequests\PaymentRequest;
use Bensondevs\Mayar\Tests\Feature\Api\PaymentRequests\PaymentRequestFixtures;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;
use Illuminate\Support\Facades\Http;

it('finds a payment request by id', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/payment/pr-123' => Http::response([
            'statusCode' => 200,
            'messages' => 'success',
            'data' => [
                'id' => 'pr-123',
                'amount' => 5000,
                'status' => 'unpaid',
                'type' => 'payment_request',
            ],
        ]),
    ]);

    $paymentRequest = PaymentRequest::find('pr-123');

    expect($paymentRequest)->toBeInstanceOf(PaymentRequest::class)
        ->and($paymentRequest->amount)->toBe(5000)
        ->and($paymentRequest->status)->toBe('unpaid');
});

it('maps the full payment request detail response from the api', function (): void {
    $id = PaymentRequestFixtures::paymentRequestDetailId();

    Http::fake([
        "https://api.mayar.club/hl/v1/payment/{$id}" => Http::response(
            body: PaymentRequestFixtures::paymentRequestDetailResponse(),
        ),
    ]);

    $paymentRequest = PaymentRequest::find($id);

    expect($paymentRequest)->toBeInstanceOf(PaymentRequest::class)
        ->and($paymentRequest->id)->toBe($id)
        ->and($paymentRequest->name)->toBe('Azumii')
        ->and($paymentRequest->amount)->toBe(100000)
        ->and($paymentRequest->userId)->toBe('348e083d-315a-4e5c-96b1-5a2a98c48413')
        ->and($paymentRequest->type)->toBe('payment_request');
});

it('returns null when payment request is not found', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/payment/missing' => Http::response([
            'statusCode' => HttpStatusCode::NotFound->value,
            'messages' => 'Not Found',
            'data' => [],
        ]),
    ]);

    expect(PaymentRequest::find('missing'))->toBeNull();
});

it('find or fail throws when payment request is missing', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/payment/missing' => Http::response([
            'statusCode' => HttpStatusCode::NotFound->value,
            'messages' => 'Not Found',
            'data' => [],
        ]),
    ]);

    PaymentRequest::findOrFail('missing');
})->throws(MayarNotFoundException::class);
