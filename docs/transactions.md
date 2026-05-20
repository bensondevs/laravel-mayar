# 📊 Transactions

Module namespace: `Bensondevs\Mayar\Transactions\`

Transactions module covers balance, unpaid transactions, daily statistics, and dynamic QR code.

## Get Account Balance

```php
use Bensondevs\Mayar\Transactions\Transaction;

$balance = Transaction::accountBalance();
```

API reference: [Get Account Balance](https://docs.mayar.id/api-reference/transaction/accountbalance)

## Get Unpaid Transactions

```php
use Bensondevs\Mayar\Transactions\UnpaidTransaction;

$paginator = UnpaidTransaction::paginate(page: 1, perPage: 10);
```

API reference: [Get Unpaid Transaction](https://docs.mayar.id/api-reference/transaction/unpaidtransaction)

## Get Daily Transaction Statistics

```php
use Bensondevs\Mayar\Transactions\Transaction;

$daily = Transaction::daily();
```

API reference: [Transaction Daily](https://docs.mayar.id/api-reference/transaction/dailytransaction)

## Create Dynamic QR Code

```php
use Bensondevs\Mayar\Transactions\Transaction;

$result = Transaction::createDynamicQrCode(amount: 10000);
```

API reference: [Create Dynamic QRCode](https://docs.mayar.id/api-reference/transaction/createdynamicqrcode)
