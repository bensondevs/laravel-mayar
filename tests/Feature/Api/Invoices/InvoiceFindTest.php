<?php

declare(strict_types=1);

use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Api\Invoices\Invoice;
use Bensondevs\Mayar\Tests\Feature\Api\Invoices\InvoiceFixtures;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;
use Illuminate\Support\Facades\Http;

it('finds an invoice by id', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/invoice/inv-123' => Http::response([
            'statusCode' => 200,
            'messages' => 'success',
            'data' => [
                'id' => 'inv-123',
                'amount' => 5000,
                'status' => 'unpaid',
            ],
        ]),
    ]);

    $invoice = Invoice::find('inv-123');

    expect($invoice)->toBeInstanceOf(Invoice::class)
        ->and($invoice->amount)->toBe(5000)
        ->and($invoice->status)->toBe('unpaid');
});

it('maps the full invoice detail response from the api', function (): void {
    $id = InvoiceFixtures::invoiceDetailId();

    Http::fake([
        "https://api.mayar.club/hl/v1/invoice/{$id}" => Http::response(
            body: InvoiceFixtures::invoiceDetailResponse(),
        ),
    ]);

    $invoice = Invoice::find($id);

    expect($invoice)->toBeInstanceOf(Invoice::class)
        ->and($invoice->id)->toBe($id)
        ->and($invoice->amount)->toBe(110000)
        ->and($invoice->status)->toBe('unpaid')
        ->and($invoice->paymentUrl)->toContain('ibzfrf4880')
        ->and($invoice->customer['name'])->toBe('Azumii');
});

it('returns null when invoice is not found', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/invoice/missing' => Http::response([
            'statusCode' => HttpStatusCode::NotFound->value,
            'messages' => 'Not Found',
            'data' => [],
        ]),
    ]);

    expect(Invoice::find('missing'))->toBeNull();
});

it('find or fail throws when invoice is missing', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/invoice/missing' => Http::response([
            'statusCode' => HttpStatusCode::NotFound->value,
            'messages' => 'Not Found',
            'data' => [],
        ]),
    ]);

    Invoice::findOrFail('missing');
})->throws(MayarNotFoundException::class);
