# 🧩 Installments

Module namespace: `Bensondevs\Mayar\Api\Installments\`

Installments currently support create and detail.

## Create Installment

```php
use Bensondevs\Mayar\Api\Installments\Installment;

$installment = Installment::create([
    'name' => 'Customer name',
    'email' => 'customer@example.com',
    'mobile' => '081234567890',
    'amount' => 1500000,
    'installment' => [
        'description' => 'Cicil Produk 3 Bulan',
        'interest' => 0,
        'tenure' => 3,
        'dueDate' => 11,
    ],
]);
```

API reference: [POST Create Installment](https://docs.mayar.id/api-reference/installment/create)

## Get Installment Detail

```php
use Bensondevs\Mayar\Api\Installments\Installment;

$installment = Installment::find('uuid');
$installment = Installment::findOrFail('uuid');
```

Returns:
- `Installment::find(string $id): Installment|null`
- `Installment::findOrFail(string $id): Installment` (throws when not found)

Common installment attributes:
- `id`, `name`, `email`, `mobile`, `amount`, `status`, `installment`, `createdAt`

```php
$installment = Installment::findOrFail('uuid');
echo $installment->id;
echo $installment->status;
echo $installment->amount;
```

API reference: [Get Installment Detail](https://docs.mayar.id/api-reference/installment/detail)
