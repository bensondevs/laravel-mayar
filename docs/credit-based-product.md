# ⚡ Credit Based Product

Module namespace: `Bensondevs\Mayar\Api\CreditBasedProduct\`

Credit-based products use the credit API with payloads that differ from credit membership.

## Get Customer Balance (Credit Usage)

```php
use Bensondevs\Mayar\Api\CreditBasedProduct\CreditBasedProduct;

$balance = CreditBasedProduct::customerBalance([
    'productId' => 'YOUR-PRODUCT-ID',
    'customerId' => 'YOUR-CUSTOMER-ID',
]);
```

API reference: [Get Customer Balance](https://docs.mayar.id/api-reference/creditbasedproduct/customerbalance)

## Paginate Customer Credit History (Credit Usage)

```php
use Bensondevs\Mayar\Api\CreditBasedProduct\CreditUsageHistory;

$paginator = CreditUsageHistory::paginate(
    identityId: 'YOUR-CUSTOMER-OR-MEMBER-ID',
    page: 1,
    perPage: 5,
    filters: ['productId' => 'YOUR-PRODUCT-ID'],
);
```

Returns: `LengthAwarePaginator<CreditUsageHistory>`

```php
foreach ($paginator as $history) {
    echo $history->id;
    echo $history->amount;
    echo $history->createdAt;
}

echo $paginator->total();
echo $paginator->perPage();
echo $paginator->currentPage();
echo $paginator->lastPage();
```

API reference: [Get Paginate Customer Credit History](https://docs.mayar.id/api-reference/creditbasedproduct/paginatecustomercredithistory)

## Spend Customer Credit (Credit Usage)

```php
use Bensondevs\Mayar\Api\CreditBasedProduct\CreditBasedProduct;

$success = CreditBasedProduct::spend([
    'productId' => 'YOUR-PRODUCT-ID',
    'customerId' => 'YOUR-CUSTOMER-ID',
    'amount' => 10,
]);
```

Returns: `bool` (`true` when spend succeeds).

Failure cases can happen when:
- customer balance is insufficient
- product/customer ID is invalid
- API validation/auth fails

```php
try {
    $success = CreditBasedProduct::spend([
        'productId' => 'YOUR-PRODUCT-ID',
        'customerId' => 'YOUR-CUSTOMER-ID',
        'amount' => 10,
    ]);

    if (! $success) {
        // Handle API-level spend rejection
    }
} catch (\Throwable $e) {
    // Handle validation, not-found, or transport errors
}
```

API reference: [Spend Customer Credit](https://docs.mayar.id/api-reference/creditbasedproduct/spendcustomercredit)

## Add Customer Credit (Credit Usage)

```php
use Bensondevs\Mayar\Api\CreditBasedProduct\CreditBasedProduct;

$result = CreditBasedProduct::addCredit([
    'productId' => 'YOUR-PRODUCT-ID',
    'customerId' => 'YOUR-CUSTOMER-ID',
    'amount' => 100,
]);
```

Returns: array/object with updated credit information.

API reference: [Add Customer Credit](https://docs.mayar.id/api-reference/creditbasedproduct/addcustomercredit)

## Regist New Customer (Credit Usage)

```php
use Bensondevs\Mayar\Api\CreditBasedProduct\CreditBasedProduct;

$result = CreditBasedProduct::registerCustomer([
    'productId' => 'YOUR-PRODUCT-ID',
    'trialCredit' => 300,
    'customerInfo' => [
        'name' => 'Customer name',
        'email' => 'customer@example.com',
        'mobile' => '081234567890',
    ],
]);
```

API reference: [Regist New Customer](https://docs.mayar.id/api-reference/creditbasedproduct/registernewcustomer)

## Generate Immutable Checkout Link

```php
use Bensondevs\Mayar\Api\CreditBasedProduct\CreditBasedProduct;

$result = CreditBasedProduct::generateImmutableCheckout([
    'productId' => 'YOUR-PRODUCT-ID',
    'creditAmount' => 1000,
    'customerInfo' => [
        'name' => 'Customer name',
        'email' => 'customer@example.com',
        'mobile' => '081234567890',
    ],
]);
```

Returns: array/object containing checkout URL and checkout metadata.

Failure cases can happen when:
- product or customer data is invalid
- requested credit amount is invalid
- API validation/auth fails

API reference: [Generate Immutable Checkout Link](https://docs.mayar.id/api-reference/creditbasedproduct/generateimmutablecheckoutlink)
