# 🧾 Invoices

Module namespace: `Bensondevs\Mayar\Api\Invoices\`

`Invoice` resources are API-backed and support create, edit, list, filter, detail, close, and re-open.

## Create Invoice

```php
use Bensondevs\Mayar\Api\Invoices\Invoice;

$invoice = Invoice::create([
    'name' => 'Customer name',
    'email' => 'customer@example.com',
    'mobile' => '081234567890',
    'redirectUrl' => 'https://example.com/thanks',
    'description' => 'Order notes',
    'expiredAt' => '2026-04-19T16:43:23.000Z',
    'items' => [
        ['quantity' => 1, 'rate' => 10000, 'description' => 'Item description'],
    ],
]);
```

API reference: [Create Invoice](https://docs.mayar.id/api-reference/invoice/create)

## Edit Invoice

```php
use Bensondevs\Mayar\Api\Invoices\Invoice;

$invoice = Invoice::update([
    'id' => 'uuid',
    'description' => 'Updated description',
    'items' => [
        ['quantity' => 2, 'rate' => 55000, 'description' => 'Updated item'],
    ],
]);
```

API reference: [Edit Invoice](https://docs.mayar.id/api-reference/invoice/edit)

## Get List Invoice

```php
use Bensondevs\Mayar\Api\Invoices\Invoice;

$paginator = Invoice::paginate(page: 1, perPage: 10);
```

API reference: [Get List Invoice](https://docs.mayar.id/api-reference/invoice)

## Get Sort / Filter Invoice

```php
use Bensondevs\Mayar\Api\Invoices\Enums\InvoiceSort;
use Bensondevs\Mayar\Api\Invoices\Invoice;

$paginator = Invoice::sort(InvoiceSort::Closed)->paginate(page: 1, perPage: 10);
```

API reference: [Get Sort / Filter Invoice](https://docs.mayar.id/api-reference/invoice/filter)

## Get Detail Invoice

```php
use Bensondevs\Mayar\Api\Invoices\Invoice;

$invoice = Invoice::find('uuid');
$invoice = Invoice::findOrFail('uuid');
```

API reference: [Get Detail / Invoice Status](https://docs.mayar.id/api-reference/invoice/detail)

## Close Invoice

```php
$invoice = Invoice::findOrFail('uuid');
$success = $invoice->close();
```

API reference: [Close Invoice](https://docs.mayar.id/api-reference/invoice/close)

## Re-open Invoice

```php
$invoice = Invoice::findOrFail('uuid');
$success = $invoice->open();
```

API reference: [Re-open Invoice](https://docs.mayar.id/api-reference/invoice/reopen)
