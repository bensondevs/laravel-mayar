<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\Invoices\Enums\InvoiceSort;
use Bensondevs\Mayar\Api\Invoices\Invoice;
use Bensondevs\Mayar\Tests\Feature\Api\Invoices\InvoiceFixtures;
use Illuminate\Support\Facades\Http;

it('paginates invoices filtered by sort', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/invoice*' => Http::response(
            body: InvoiceFixtures::invoiceListResponse(),
        ),
    ]);

    $paginator = Invoice::sort(InvoiceSort::Closed)->paginate(page: 1, perPage: 10);

    expect($paginator)->toHaveCount(1)
        ->and($paginator->first()->status)->toBe('paid');

    Http::assertSent(fn ($request): bool => str_contains($request->url(), 'sort=closed'));
});

it('accepts sort as a string', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/invoice*' => Http::response(
            body: InvoiceFixtures::invoiceListResponse(),
        ),
    ]);

    Invoice::sort('closed')->paginate(page: 1, perPage: 10);

    Http::assertSent(fn ($request): bool => str_contains($request->url(), 'sort=closed'));
});
