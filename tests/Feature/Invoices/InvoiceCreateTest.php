<?php

declare(strict_types=1);

use Bensondevs\Mayar\Invoices\Invoice;
use Bensondevs\Mayar\Invoices\InvoiceItem;
use Bensondevs\Mayar\Tests\Feature\Invoices\InvoiceFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('creates an invoice via save', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/invoice/create' => Http::response(
            body: InvoiceFixtures::invoiceCreateResponse(),
        ),
    ]);

    $invoice = new Invoice;
    $invoice->name = 'andre jago';
    $invoice->email = 'user@example.com';
    $invoice->mobile = '085797522261';
    $invoice->redirectUrl = 'https://example.com/redirect';
    $invoice->description = 'testing invoice';
    $invoice->expiredAt = '2026-04-19T16:43:23.000Z';
    $invoice->extraData = [
        'noCustomer' => '827hiueqy271hj',
        'idProd' => 'contoh aja',
    ];
    $invoice->addItem([
        'quantity' => 3,
        'rate' => 11000,
        'description' => '1 item',
    ]);

    $invoice->save();

    expect($invoice->id)->toBe('df65d192-8396-4f9a-b4e5-8244648c07c5')
        ->and($invoice->transactionId)->toBe('ca87fd13-8742-4d48-af33-7de1a417bc34')
        ->and($invoice->link)->toContain('ycfyxbj2h3');

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/invoice/create') {
            return false;
        }

        $body = $request->data();

        return $body['name'] === 'andre jago'
            && $body['email'] === 'user@example.com'
            && $body['items'][0]['quantity'] === 3
            && $body['extraData']['noCustomer'] === '827hiueqy271hj';
    });
});

it('creates an invoice via create', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/invoice/create' => Http::response(
            body: InvoiceFixtures::invoiceCreateResponse(),
        ),
    ]);

    $invoice = Invoice::create([
        'name' => 'andre jago',
        'email' => 'user@example.com',
        'mobile' => '085797522261',
        'redirectUrl' => 'https://example.com/redirect',
        'description' => 'testing invoice',
        'expiredAt' => '2026-04-19T16:43:23.000Z',
        'extraData' => [
            'noCustomer' => '827hiueqy271hj',
            'idProd' => 'contoh aja',
        ],
        'items' => [
            ['quantity' => 3, 'rate' => 11000, 'description' => '1 item'],
        ],
    ]);

    expect($invoice->id)->toBe('df65d192-8396-4f9a-b4e5-8244648c07c5')
        ->and($invoice->exists())->toBeTrue();

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/invoice/create') {
            return false;
        }

        $body = $request->data();

        return $body['name'] === 'andre jago'
            && $body['items'][0]['quantity'] === 3;
    });
});

it('throws logic exception when create is called with an id', function (): void {
    Invoice::create(['id' => 'df65d192-8396-4f9a-b4e5-8244648c07c5']);
})->throws(LogicException::class);

it('creates an invoice with constructor and setItems', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/invoice/create' => Http::response(
            body: InvoiceFixtures::invoiceCreateResponse(),
        ),
    ]);

    $invoice = new Invoice([
        'name' => 'andre jago',
        'email' => 'user@example.com',
        'mobile' => '085797522261',
        'redirectUrl' => 'https://example.com/redirect',
        'description' => 'testing invoice',
        'expiredAt' => '2026-04-19T16:43:23.000Z',
        'extraData' => [
            'noCustomer' => '827hiueqy271hj',
            'idProd' => 'contoh aja',
        ],
    ]);

    $invoice->setItems([
        new InvoiceItem(quantity: 2, rate: 5000, description: 'dto item'),
    ]);

    $invoice->save();

    expect($invoice->exists())->toBeTrue();
});

it('throws validation exception when create payload is invalid', function (): void {
    $invoice = new Invoice([
        'name' => 'andre jago',
    ]);

    $invoice->save();
})->throws(ValidationException::class);
