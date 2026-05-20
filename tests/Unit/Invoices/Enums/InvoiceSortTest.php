<?php

declare(strict_types=1);

use Bensondevs\Mayar\Invoices\Enums\InvoiceSort;

it('resolves invoice sort from string', function (): void {
    expect(InvoiceSort::find('closed'))->toBe(InvoiceSort::Closed)
        ->and(InvoiceSort::find('active'))->toBe(InvoiceSort::Active)
        ->and(InvoiceSort::find('paid'))->toBe(InvoiceSort::Paid)
        ->and(InvoiceSort::find('invalid'))->toBeNull();
});
