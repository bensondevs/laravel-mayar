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
