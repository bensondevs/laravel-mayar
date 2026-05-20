# 📊 Transactions

Module namespace: `Bensondevs\Mayar\Api\Transactions\`

Transactions module covers balance, unpaid transactions, daily statistics, and dynamic QR code.

## Get Account Balance

```php
use Bensondevs\Mayar\Api\Transactions\Transaction;

$balance = Transaction::accountBalance();
```

API reference: [Get Account Balance](https://docs.mayar.id/api-reference/transaction/accountbalance)

## Get Unpaid Transactions

```php
use Bensondevs\Mayar\Api\Transactions\UnpaidTransaction;

$paginator = UnpaidTransaction::paginate(page: 1, perPage: 10);
```

Returns: `LengthAwarePaginator<UnpaidTransaction>`

```php
foreach ($paginator as $transaction) {
    echo $transaction->id;
    echo $transaction->status;
    echo $transaction->amount;
}

echo $paginator->total();
echo $paginator->perPage();
echo $paginator->currentPage();
echo $paginator->lastPage();
```

API reference: [Get Unpaid Transaction](https://docs.mayar.id/api-reference/transaction/unpaidtransaction)

## Get Daily Transaction Statistics

```php
use Bensondevs\Mayar\Api\Transactions\Transaction;

$daily = Transaction::daily();
```

API reference: [Transaction Daily](https://docs.mayar.id/api-reference/transaction/dailytransaction)

## Create Dynamic QR Code

```php
use Bensondevs\Mayar\Api\Transactions\Transaction;

$result = Transaction::createDynamicQrCode(amount: 10000);
```

Returns: array/object containing QR payload (for example: `qrString`, `referenceId`, `expiredAt`).

Failure cases can happen when:
- amount is below minimum or invalid
- API validation/auth fails
- upstream QR generation service is unavailable

```php
try {
    $result = Transaction::createDynamicQrCode(amount: 10000);
} catch (\Throwable $e) {
    // Handle validation, network, or upstream errors
}
```

API reference: [Create Dynamic QRCode](https://docs.mayar.id/api-reference/transaction/createdynamicqrcode)
