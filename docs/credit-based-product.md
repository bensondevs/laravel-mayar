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

API reference: [Generate Immutable Checkout Link](https://docs.mayar.id/api-reference/creditbasedproduct/generateimmutablecheckoutlink)
