# 🏷️ Discounts

Module namespace: `Bensondevs\Mayar\Api\Discounts\`

Discounts support create, validate, and detail.

## Create Discount with Coupon

```php
use Bensondevs\Mayar\Api\Discounts\Discount;

$discount = Discount::create([
    'name' => 'Diskon Murmer',
    'expiredAt' => '2030-01-01T09:06:14.933Z',
    'products' => [],
    'discount' => [
        'discountType' => 'monetary',
        'eligibleCustomerType' => 'all',
        'minimumPurchase' => 500000,
        'value' => 100000,
        'totalCoupons' => 100,
    ],
    'coupon' => [
        'code' => 'haribaik',
        'type' => 'reusable',
    ],
]);
```

API reference: [Create Discount with Coupon](https://docs.mayar.id/api-reference/discount/create)

## Validate Coupon

```php
use Bensondevs\Mayar\Api\Discounts\Discount;

$result = Discount::validate([
    'paymentLinkId' => 'uuid',
    'couponCode' => 'NFRBFUK',
    'finalAmount' => 0,
    'tickets' => [],
    'customerEmail' => '',
]);
```

API reference: [Validate Coupon](https://docs.mayar.id/api-reference/discount/validate)

## Get Coupon Detail

```php
use Bensondevs\Mayar\Api\Discounts\Discount;

$discount = Discount::find('uuid');
$discount = Discount::findOrFail('uuid');
```

API reference: [Get Coupon Detail](https://docs.mayar.id/api-reference/discount/detail)
