# 💸 Payment Requests

Module namespace: `Bensondevs\Mayar\Api\PaymentRequests\`

`PaymentRequest` resources are API-backed and support create, edit, list, filter, detail, close, and re-open.

## Create Single Payment Request

```php
use Bensondevs\Mayar\Api\PaymentRequests\PaymentRequest;

$paymentRequest = PaymentRequest::create([
    'name' => 'Customer name',
    'email' => 'customer@example.com',
    'amount' => 170000,
    'mobile' => '081234567890',
    'redirectUrl' => 'https://example.com/thanks',
    'description' => 'Payment description',
    'expiredAt' => '2025-12-29T09:41:09.401Z',
]);
```

API reference: [Create Single Payment Request](https://docs.mayar.id/api-reference/reqpayment/create)

## Edit Single Payment Request

```php
use Bensondevs\Mayar\Api\PaymentRequests\PaymentRequest;

$paymentRequest = PaymentRequest::update([
    'id' => 'uuid',
    'name' => 'Customer name',
    'email' => 'customer@example.com',
    'amount' => 100000,
    'mobile' => '081234567890',
    'redirectUrl' => 'https://example.com/thanks',
    'description' => 'Updated description',
    'expiredAt' => '2025-12-29T09:41:09.401Z',
]);
```

API reference: [Edit Single Payment Request](https://docs.mayar.id/api-reference/reqpayment/edit)

## Get List Single Payment Request

```php
use Bensondevs\Mayar\Api\PaymentRequests\PaymentRequest;

$paginator = PaymentRequest::paginate(page: 1, perPage: 10);
```

API reference: [Get List Single Payment Request](https://docs.mayar.id/api-reference/reqpayment)

## Get Sort / Filter Single Payment Request

```php
use Bensondevs\Mayar\Api\PaymentRequests\Enums\PaymentRequestStatus;
use Bensondevs\Mayar\Api\PaymentRequests\PaymentRequest;

$paginator = PaymentRequest::status(PaymentRequestStatus::Paid)->paginate(page: 1, perPage: 10);
```

API reference: [Get Sort / Filter Single Payment Request](https://docs.mayar.id/api-reference/reqpayment/filter)

## Get Detail Single Payment Request

```php
use Bensondevs\Mayar\Api\PaymentRequests\PaymentRequest;

$paymentRequest = PaymentRequest::find('uuid');
$paymentRequest = PaymentRequest::findOrFail('uuid');
```

API reference: [Get Detail Single Payment Request](https://docs.mayar.id/api-reference/reqpayment/detail)

## Close Single Payment Request

```php
$paymentRequest = PaymentRequest::findOrFail('uuid');
$success = $paymentRequest->close();
```

API reference: [Close Single Payment Request](https://docs.mayar.id/api-reference/reqpayment/close)

## Re-open Single Payment Request

```php
$paymentRequest = PaymentRequest::findOrFail('uuid');
$success = $paymentRequest->open();
```

API reference: [Re-open Single Payment Request](https://docs.mayar.id/api-reference/reqpayment/reopen)
