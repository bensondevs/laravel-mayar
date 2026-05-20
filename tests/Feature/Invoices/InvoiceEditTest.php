<?php

declare(strict_types=1);

use Bensondevs\Mayar\Invoices\Invoice;
use Bensondevs\Mayar\Tests\Feature\Invoices\InvoiceFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('edits an invoice via save on an existing instance', function (): void {
    $id = InvoiceFixtures::invoiceDetailId();

    Http::fake([
        'https://api.mayar.club/hl/v1/invoice/edit' => Http::response(
            body: InvoiceFixtures::invoiceEditResponse(),
        ),
    ]);

    $invoice = Invoice::fromMayar(['id' => $id, 'description' => 'old']);
    $invoice->description = 'Berubah Jadi Invoice Yang Sudah Diedit';
    $invoice->setItems([
        [
            'quantity' => 2,
            'rate' => 55000,
            'description' => 'Berubah Jadi Invoice Yang Sudah Diedit',
        ],
    ]);

    $invoice->save();

    expect($invoice->id)->toBe($id)
        ->and($invoice->link)->toContain('ibzfrf4880');

    Http::assertSent(function ($request) use ($id): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/invoice/edit') {
            return false;
        }

        $body = $request->data();

        return $body['id'] === $id
            && $body['description'] === 'Berubah Jadi Invoice Yang Sudah Diedit'
            && $body['items'][0]['rate'] === 55000;
    });
});

it('edits an invoice via static update', function (): void {
    $id = InvoiceFixtures::invoiceDetailId();

    Http::fake([
        'https://api.mayar.club/hl/v1/invoice/edit' => Http::response(
            body: InvoiceFixtures::invoiceEditResponse(),
        ),
    ]);

    $invoice = Invoice::update([
        'id' => $id,
        'redirectUrl' => 'https://web.mayar.id',
        'description' => 'Updated via static',
    ]);

    expect($invoice->id)->toBe($id);

    Http::assertSent(function ($request) use ($id): bool {
        $body = $request->data();

        return $body['id'] === $id
            && $body['redirectUrl'] === 'https://web.mayar.id';
    });
});

it('throws validation exception when edit payload has no id', function (): void {
    Invoice::update([
        'description' => 'missing id',
    ]);
})->throws(ValidationException::class);
