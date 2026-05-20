<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\PaymentRequests\Enums\PaymentRequestStatus;
use Bensondevs\Mayar\Api\PaymentRequests\PaymentRequest;
use Bensondevs\Mayar\Tests\Feature\Api\PaymentRequests\PaymentRequestFixtures;
use Illuminate\Support\Facades\Http;

it('paginates payment requests filtered by status', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/payment*' => Http::response(
            body: PaymentRequestFixtures::paymentRequestListResponse(),
        ),
    ]);

    $paginator = PaymentRequest::status(PaymentRequestStatus::Paid)->paginate(page: 1, perPage: 10);

    expect($paginator)->toHaveCount(1);

    Http::assertSent(fn ($request): bool => str_contains($request->url(), 'status=paid'));
});

it('accepts status as a string', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/payment*' => Http::response(
            body: PaymentRequestFixtures::paymentRequestListResponse(),
        ),
    ]);

    PaymentRequest::status('closed')->paginate(page: 1, perPage: 10);

    Http::assertSent(fn ($request): bool => str_contains($request->url(), 'status=closed'));
});
