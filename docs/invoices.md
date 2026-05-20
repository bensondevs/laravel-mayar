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

Returns: `LengthAwarePaginator<Invoice>`

```php
foreach ($paginator as $invoice) {
    echo $invoice->id;
    echo $invoice->status;
    echo $invoice->name;
}

echo $paginator->total();
echo $paginator->perPage();
echo $paginator->currentPage();
echo $paginator->lastPage();
```

API reference: [Get List Invoice](https://docs.mayar.id/api-reference/invoice)

## Get Sort / Filter Invoice

```php
use Bensondevs\Mayar\Api\Invoices\Enums\InvoiceSort;
use Bensondevs\Mayar\Api\Invoices\Invoice;

$paginator = Invoice::sort(InvoiceSort::Closed)->paginate(page: 1, perPage: 10);
```

Returns: `LengthAwarePaginator<Invoice>` (same paginator usage pattern as above).

```php
foreach ($paginator as $invoice) {
    echo $invoice->id;
}
echo $paginator->total();
```

API reference: [Get Sort / Filter Invoice](https://docs.mayar.id/api-reference/invoice/filter)

## Get Detail Invoice

```php
use Bensondevs\Mayar\Api\Invoices\Invoice;

$invoice = Invoice::find('uuid');
$invoice = Invoice::findOrFail('uuid');
```

Returns:
- `Invoice::find(string $id): Invoice|null`
- `Invoice::findOrFail(string $id): Invoice` (throws when not found)

Common invoice attributes:
- `id`, `name`, `email`, `mobile`, `description`, `status`, `expiredAt`, `items`, `createdAt`

```php
$invoice = Invoice::findOrFail('uuid');
echo $invoice->id;
echo $invoice->status;
echo $invoice->expiredAt;
```

API reference: [Get Detail / Invoice Status](https://docs.mayar.id/api-reference/invoice/detail)

## Close Invoice

```php
$invoice = Invoice::findOrFail('uuid');
$success = $invoice->close();
```

Returns: `bool` (`true` when invoice is closed).

Failure cases can happen when:
- invoice ID does not exist
- invoice is already closed or no longer closable
- API validation/auth fails

```php
try {
    $invoice = Invoice::findOrFail('uuid');
    $success = $invoice->close();

    if (! $success) {
        // Handle API-level close rejection
    }
} catch (\Throwable $e) {
    // Handle not found, validation, or transport errors
}
```

API reference: [Close Invoice](https://docs.mayar.id/api-reference/invoice/close)

## Re-open Invoice

```php
$invoice = Invoice::findOrFail('uuid');
$success = $invoice->open();
```

Returns: `bool` (`true` when invoice is re-opened).

Failure cases can happen when:
- invoice ID does not exist
- invoice is not in a re-openable status
- API validation/auth fails

```php
try {
    $invoice = Invoice::findOrFail('uuid');
    $success = $invoice->open();

    if (! $success) {
        // Handle API-level reopen rejection
    }
} catch (\Throwable $e) {
    // Handle not found, validation, or transport errors
}
```

API reference: [Re-open Invoice](https://docs.mayar.id/api-reference/invoice/reopen)
