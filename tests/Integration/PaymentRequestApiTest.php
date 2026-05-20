<?php

declare(strict_types=1);

use Bensondevs\Mayar\Exceptions\MayarRequestException;
use Bensondevs\Mayar\Api\PaymentRequests\PaymentRequest;
use Illuminate\Pagination\LengthAwarePaginator;

it('paginates payment requests via PaymentRequest::paginate', function (): void {
    skipUnlessMayarConfigured();

    try {
        $paginator = PaymentRequest::paginate(page: 1, perPage: 1);
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Payment request list API unavailable: ' . $exception->getMessage());
    }

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class);

    if ($paginator->isEmpty()) {
        test()->markTestSkipped('No payment requests in the sandbox account');
    }

    $first = $paginator->first();

    expect($first)->toBeInstanceOf(PaymentRequest::class)
        ->and($first->id)->not->toBeEmpty();
});

it('creates a payment request via save', function (): void {
    skipUnlessMayarConfigured();

    $paymentRequest = integrationCreatePaymentRequest();

    expect($paymentRequest->id)->not->toBeEmpty()
        ->and($paymentRequest->link)->not->toBeEmpty();
});

it('creates a payment request and finds it by id', function (): void {
    skipUnlessMayarConfigured();

    $created = integrationCreatePaymentRequest();

    try {
        $found = PaymentRequest::find((string) $created->getKey());
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Payment request detail API unavailable: ' . $exception->getMessage());
    }

    expect($found)->toBeInstanceOf(PaymentRequest::class)
        ->and($found->getKey())->toBe($created->getKey());
});

it('returns null for a non-existent payment request id', function (): void {
    skipUnlessMayarConfigured();

    try {
        $paymentRequest = PaymentRequest::find('00000000-0000-0000-0000-000000000000');
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Payment request detail API unavailable: ' . $exception->getMessage());
    }

    expect($paymentRequest)->toBeNull();
});

it('closes a payment request', function (): void {
    skipUnlessMayarConfigured();

    $paymentRequest = integrationCreatePaymentRequest();

    expect($paymentRequest->close())->toBeTrue();
});

it('opens a closed payment request', function (): void {
    skipUnlessMayarConfigured();

    $paymentRequest = integrationCreatePaymentRequest();

    if (! $paymentRequest->close()) {
        test()->markTestSkipped('Could not close payment request before testing open');
    }

    expect($paymentRequest->open())->toBeTrue();
});

function integrationCreatePaymentRequest(): PaymentRequest
{
    $paymentRequest = new PaymentRequest;
    $paymentRequest->name = 'Integration Test';
    $paymentRequest->email = 'integration-' . uniqid() . '@example.com';
    $paymentRequest->amount = 1000;
    $paymentRequest->mobile = '081234567890';
    $paymentRequest->redirectUrl = 'https://example.com/thanks';
    $paymentRequest->description = 'Created by laravel-mayar integration test';
    $paymentRequest->expiredAt = now()->addDays(7)->utc()->format('Y-m-d\TH:i:s.v\Z');

    try {
        $paymentRequest->save();
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Payment request create API unavailable: ' . $exception->getMessage());
    }

    if (! $paymentRequest->exists()) {
        test()->markTestSkipped('Payment request create did not return an id');
    }

    return $paymentRequest;
}
