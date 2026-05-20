<?php

declare(strict_types=1);

use Bensondevs\Mayar\Invoices\Invoice;
use Bensondevs\Mayar\Tests\Feature\Invoices\InvoiceFixtures;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

it('paginates invoices via the invoice list endpoint', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/invoice*' => Http::response(
            body: InvoiceFixtures::invoiceListResponse(),
        ),
    ]);

    $paginator = Invoice::paginate(page: 1, perPage: 10);

    $first = $paginator->first();

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginator)->toHaveCount(1)
        ->and($paginator->total())->toBe(17)
        ->and($first)->toBeInstanceOf(Invoice::class)
        ->and($first->id)->toBe('01918da0-704b-45d8-bf14-afbd738eb682')
        ->and($first->status)->toBe('paid')
        ->and($first->amount)->toBe(2000);

    Http::assertSent(function ($request): bool {
        return str_starts_with($request->url(), 'https://api.mayar.club/hl/v1/invoice')
            && str_contains($request->url(), 'page=1')
            && str_contains($request->url(), 'pageSize=10')
            && ! str_contains($request->url(), 'sort=');
    });
});
