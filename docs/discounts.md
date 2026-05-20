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

Returns: array/object validation result (for example: `isValid`, `message`, `discountAmount`, `finalAmount`).

Failure cases can happen when:
- coupon is expired or inactive
- coupon does not match purchase constraints
- API validation/auth fails

```php
try {
    $result = Discount::validate([
        'paymentLinkId' => 'uuid',
        'couponCode' => 'NFRBFUK',
        'finalAmount' => 0,
        'tickets' => [],
        'customerEmail' => '',
    ]);

    if (! ($result['isValid'] ?? false)) {
        // Handle invalid coupon case
    }
} catch (\Throwable $e) {
    // Handle validation, network, or API errors
}
```

API reference: [Validate Coupon](https://docs.mayar.id/api-reference/discount/validate)

## Get Coupon Detail

```php
use Bensondevs\Mayar\Api\Discounts\Discount;

$discount = Discount::find('uuid');
$discount = Discount::findOrFail('uuid');
```

Returns:
- `Discount::find(string $id): Discount|null`
- `Discount::findOrFail(string $id): Discount` (throws when not found)

Common discount attributes:
- `id`, `name`, `expiredAt`, `discount`, `coupon`, `status`, `createdAt`

```php
$discount = Discount::findOrFail('uuid');
echo $discount->id;
echo $discount->name;
echo $discount->expiredAt;
```

API reference: [Get Coupon Detail](https://docs.mayar.id/api-reference/discount/detail)
