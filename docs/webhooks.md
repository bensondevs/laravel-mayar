# 🪝 Webhooks

This package supports both:
- **Outbound webhook management** (register/test/retry/history via Mayar API)
- **Inbound webhook receiving** (invokable controller + Laravel event dispatch)

Mayar webhook docs:
- [Webhook Overview](https://docs.mayar.id/integration/webhook)
- [Event Types](https://docs.mayar.id/integration/webhook#event-type)
- [Payload Parameters](https://docs.mayar.id/integration/webhook#parameter-description)

## Inbound Webhook Handling (Event Listener Architecture)

### 1) Route registration options

You can choose either mode:

- **Optional package route (enabled by default)**
  - Uses `POST /webhooks/mayar`
  - Controller: `Bensondevs\Mayar\Http\Controllers\MayarWebhookController`
- **Manual route registration in your app**
  - Keep full control over path, middleware, and naming
  - Still use the same package controller

Config (`config/mayar.php`):

```php
'webhook' => [
    'enabled' => true, // if false, package route is not loaded
    'path' => 'webhooks/mayar',
    'middleware' => ['api'],
    'name' => 'mayar.webhook',
    'ignore_unknown_events' => true,
],
```

If you prefer manual route registration:

```php
use Bensondevs\Mayar\Http\Controllers\MayarWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/my-custom-mayar-webhook', MayarWebhookController::class)
    ->middleware(['api'])
    ->name('my-custom-mayar-webhook');
```

### 2) Supported incoming event classes

Incoming Mayar event names are mapped to these package events:

- `payment.received` -> `Bensondevs\Mayar\Events\Webhooks\PaymentReceived`
- `payment.reminder` -> `Bensondevs\Mayar\Events\Webhooks\PaymentReminder`
- `shipper.status` -> `Bensondevs\Mayar\Events\Webhooks\ShipperStatus`
- `membership.memberUnsubscribed` -> `Bensondevs\Mayar\Events\Webhooks\MembershipMemberUnsubscribed`
- `membership.memberExpired` -> `Bensondevs\Mayar\Events\Webhooks\MembershipMemberExpired`
- `membership.changeTierMemberRegistered` -> `Bensondevs\Mayar\Events\Webhooks\MembershipChangeTierMemberRegistered`
- `membership.newMemberRegistered` -> `Bensondevs\Mayar\Events\Webhooks\MembershipNewMemberRegistered`

Each dispatched event carries:
- `$event->data` as `Bensondevs\Mayar\DataTransferObject\MayarWebhookData` (objectified `data.*`)
- `$event->event` as raw event name
- `$event->payload` as original webhook payload

### 3) Listener example

Register listener in your app's event provider:

```php
use App\Listeners\HandleMayarPaymentReceived;
use Bensondevs\Mayar\Events\Webhooks\PaymentReceived;

protected $listen = [
    PaymentReceived::class => [
        HandleMayarPaymentReceived::class,
    ],
];
```

Listener implementation:

```php
namespace App\Listeners;

use Bensondevs\Mayar\Events\Webhooks\PaymentReceived;

class HandleMayarPaymentReceived
{
    public function handle(PaymentReceived $event): void
    {
        $transactionId = $event->data->get('id');
        $amount = (int) $event->data->get('amount', 0);
        $customerEmail = $event->data->get('customerEmail');

        // Your business logic here.
    }
}
```

### 4) DTO usage (`MayarWebhookData`)

`MayarWebhookData` wraps `data.*` and provides:

- `get(string $key, mixed $default = null): mixed`
- `has(string $key): bool`
- `toArray(): array`
- `ArrayAccess` support (e.g. `$event->data['amount']`)

Example:

```php
$amount = (int) $event->data->get('amount', 0);

if ($event->data->has('customerEmail')) {
    $email = (string) $event->data->get('customerEmail');
}
```

### 5) Payload normalization behavior

The receiver accepts multiple event key formats:

- `event` as string (recommended)
- `event.received` as flat key
- `event.received` as nested structure (`['event' => ['received' => '...']]`)

Unknown events:
- By default, unknown events are acknowledged with `200` and status `ignored`
- Set `mayar.webhook.ignore_unknown_events` to `false` to return `422` for unknown events

## Outbound Webhook Management

Module namespace: `Bensondevs\Mayar\Api\Webhooks\`

### Get Webhook History

```php
use Bensondevs\Mayar\Api\Webhooks\WebhookHistory;

$paginator = WebhookHistory::paginate(page: 1, perPage: 10);
```

Returns: `LengthAwarePaginator<WebhookHistory>`

```php
foreach ($paginator as $history) {
    echo $history->id;
    echo $history->status;
    echo $history->createdAt;
}
```

API reference: [Get History](https://docs.mayar.id/api-reference/webhook/history)

### Register URL Hook

```php
use Bensondevs\Mayar\Api\Webhooks\Webhook;

$success = Webhook::register('https://example.com/webhook');
```

Returns: `bool` (`true` when registration succeeds).

API reference: [Register URL Hook](https://docs.mayar.id/api-reference/webhook/registerurlhook)

### Test URL Hook

```php
use Bensondevs\Mayar\Api\Webhooks\Webhook;

$success = Webhook::test('https://example.com/webhook');
```

Returns: `bool` (`true` when test delivery succeeds).

API reference: [Test URL Hook](https://docs.mayar.id/api-reference/webhook/testurlhook)

### Retry Webhook History

```php
use Bensondevs\Mayar\Api\Webhooks\Webhook;

$success = Webhook::retry('7d567063-ad7f-48d5-9e84-0e41938783a5');
```

Returns: `bool` (`true` when retry is accepted).

API reference: [Retry History](https://docs.mayar.id/api-reference/webhook/retryhistory)
