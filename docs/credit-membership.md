# 💳 Credit Membership

Module namespace: `Bensondevs\Mayar\Api\CreditMembership\`

Use this module for membership-credit flows: balance, history, spend, add-credit, and customer registration.

## Get Customer Balance

```php
use Bensondevs\Mayar\Api\CreditMembership\CreditMembership;

$balance = CreditMembership::customerBalance([
    'productId' => 'YOUR-PRODUCT-ID',
    'membershipTierId' => 'YOUR-MEMBERSHIP-TIER-ID',
    'memberId' => 'PQVS4KGY',
]);
```

API reference: [Get Customer Balance](https://docs.mayar.id/api-reference/usagebasedmembership/customerbalance)

## Paginate Customer Credit History

```php
use Bensondevs\Mayar\Api\CreditMembership\CreditHistory;

$paginator = CreditHistory::paginate(
    identityId: 'PQVS4KGY',
    page: 1,
    perPage: 5,
    filters: ['productId' => 'YOUR-PRODUCT-ID'],
);
```

Returns: `LengthAwarePaginator<CreditHistory>`

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

API reference: [Get Paginate Customer Credit History](https://docs.mayar.id/api-reference/usagebasedmembership/paginatecustomercredithistory)

## Spend Customer Credit

```php
use Bensondevs\Mayar\Api\CreditMembership\CreditMembership;

$success = CreditMembership::spend([
    'productId' => 'YOUR-PRODUCT-ID',
    'membershipTierId' => 'YOUR-MEMBERSHIP-TIER-ID',
    'amount' => 10,
    'memberId' => 'PQVS4KGY',
]);
```

Returns: `bool` (`true` when spend succeeds).

Failure cases can happen when:
- customer balance is insufficient
- customer or membership tier is not found
- API validation/auth fails

```php
try {
    $success = CreditMembership::spend([
        'productId' => 'YOUR-PRODUCT-ID',
        'membershipTierId' => 'YOUR-MEMBERSHIP-TIER-ID',
        'amount' => 10,
        'memberId' => 'PQVS4KGY',
    ]);

    if (! $success) {
        // Handle API-level spend rejection
    }
} catch (\Throwable $e) {
    // Handle validation, not-found, or transport errors
}
```

API reference: [Spend Customer Credit](https://docs.mayar.id/api-reference/usagebasedmembership/spendcustomercredit)

## Add Customer Credit

```php
use Bensondevs\Mayar\Api\CreditMembership\CreditMembership;

$result = CreditMembership::addCredit([
    'productId' => 'YOUR-PRODUCT-ID',
    'membershipTierId' => 'YOUR-MEMBERSHIP-TIER-ID',
    'amount' => 100,
    'customerId' => 'YOUR-CUSTOMER-ID',
]);
```

Returns: array/object with updated credit information.

```php
try {
    $result = CreditMembership::addCredit([
        'productId' => 'YOUR-PRODUCT-ID',
        'membershipTierId' => 'YOUR-MEMBERSHIP-TIER-ID',
        'amount' => 100,
        'customerId' => 'YOUR-CUSTOMER-ID',
    ]);
} catch (\Throwable $e) {
    // Handle validation, not-found, or transport errors
}
```

API reference: [Add Customer Credit](https://docs.mayar.id/api-reference/usagebasedmembership/addcustomercredit)

## Register New Membership Customer

```php
use Bensondevs\Mayar\Api\CreditMembership\CreditMembership;

$result = CreditMembership::registerCustomer([
    'productId' => 'YOUR-PRODUCT-ID',
    'membershipTierId' => 'YOUR-MEMBERSHIP-TIER-ID',
    'membershipMonthlyPeriod' => 1,
    'trialCredit' => 100,
    'customerInfo' => [
        'name' => 'Customer name',
        'email' => 'customer@example.com',
        'mobile' => '081234567890',
    ],
]);
```

API reference: [Regist New Membership Customer](https://docs.mayar.id/api-reference/usagebasedmembership/registnewmembershipcustomer)
