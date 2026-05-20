<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\PaymentRequests\PaymentRequest;
use Bensondevs\Mayar\Tests\Feature\PaymentRequests\PaymentRequestFixtures;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

it('paginates payment requests via the payment list endpoint', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/payment*' => Http::response(
            body: PaymentRequestFixtures::paymentRequestListResponse(),
        ),
    ]);

    $paginator = PaymentRequest::paginate(page: 1, perPage: 10);

    $first = $paginator->first();

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginator)->toHaveCount(1)
        ->and($paginator->total())->toBe(1)
        ->and($first)->toBeInstanceOf(PaymentRequest::class)
        ->and($first->id)->toBe('07e1d023-3bc4-46cd-9a30-2102cd0770f4')
        ->and($first->type)->toBe('payment_request')
        ->and($first->amount)->toBe(100000);

    Http::assertSent(function ($request): bool {
        return str_starts_with($request->url(), 'https://api.mayar.club/hl/v1/payment')
            && str_contains($request->url(), 'page=1')
            && str_contains($request->url(), 'pageSize=10')
            && ! str_contains($request->url(), 'status=');
    });
});
