<?php

declare(strict_types=1);

use Bensondevs\Mayar\Exceptions\MayarRequestException;
use Bensondevs\Mayar\Api\Invoices\Invoice;
use Illuminate\Pagination\LengthAwarePaginator;

it('paginates invoices via Invoice::paginate', function (): void {
    skipUnlessMayarConfigured();

    try {
        $paginator = Invoice::paginate(page: 1, perPage: 1);
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Invoice list API unavailable: ' . $exception->getMessage());
    }

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class);

    if ($paginator->isEmpty()) {
        test()->markTestSkipped('No invoices in the sandbox account');
    }

    $first = $paginator->first();

    expect($first)->toBeInstanceOf(Invoice::class)
        ->and($first->id)->not->toBeEmpty();
});

it('creates an invoice via save', function (): void {
    skipUnlessMayarConfigured();

    $invoice = integrationCreateInvoice();

    expect($invoice->id)->not->toBeEmpty()
        ->and($invoice->link)->not->toBeEmpty();
});

it('creates an invoice and finds it by id', function (): void {
    skipUnlessMayarConfigured();

    $created = integrationCreateInvoice();

    try {
        $found = Invoice::find((string) $created->getKey());
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Invoice detail API unavailable: ' . $exception->getMessage());
    }

    expect($found)->toBeInstanceOf(Invoice::class)
        ->and($found->getKey())->toBe($created->getKey());
});

it('returns null for a non-existent invoice id', function (): void {
    skipUnlessMayarConfigured();

    try {
        $invoice = Invoice::find('00000000-0000-0000-0000-000000000000');
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Invoice detail API unavailable: ' . $exception->getMessage());
    }

    expect($invoice)->toBeNull();
});

it('closes an invoice', function (): void {
    skipUnlessMayarConfigured();

    $invoice = integrationCreateInvoice();

    expect($invoice->close())->toBeTrue();
});

it('opens a closed invoice', function (): void {
    skipUnlessMayarConfigured();

    $invoice = integrationCreateInvoice();

    if (! $invoice->close()) {
        test()->markTestSkipped('Could not close invoice before testing open');
    }

    expect($invoice->open())->toBeTrue();
});

function integrationCreateInvoice(): Invoice
{
    $invoice = new Invoice;
    $invoice->name = 'Integration Test';
    $invoice->email = 'integration-' . uniqid() . '@example.com';
    $invoice->mobile = '081234567890';
    $invoice->redirectUrl = 'https://example.com/thanks';
    $invoice->description = 'Created by laravel-mayar integration test';
    $invoice->expiredAt = now()->addDays(7)->utc()->format('Y-m-d\TH:i:s.v\Z');
    $invoice->extraData = [
        'noCustomer' => 'integration-' . uniqid(),
        'idProd' => 'integration-test',
    ];
    $invoice->addItem([
        'quantity' => 1,
        'rate' => 1000,
        'description' => 'Integration test item',
    ]);

    try {
        $invoice->save();
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Invoice create API unavailable: ' . $exception->getMessage());
    }

    if (! $invoice->exists()) {
        test()->markTestSkipped('Invoice create did not return an id');
    }

    return $invoice;
}
