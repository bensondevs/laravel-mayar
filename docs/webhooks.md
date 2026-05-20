# 🪝 Webhooks

Module namespace: `Bensondevs\Mayar\Api\Webhooks\`

Webhooks cover history retrieval and register/test/retry actions.

## Get Webhook History

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

echo $paginator->total();
echo $paginator->perPage();
echo $paginator->currentPage();
echo $paginator->lastPage();
```

API reference: [Get History](https://docs.mayar.id/api-reference/webhook/history)

## Register URL Hook

```php
use Bensondevs\Mayar\Api\Webhooks\Webhook;

$success = Webhook::register('https://example.com/webhook');
```

Returns: `bool` (`true` when registration succeeds).

Failure cases can happen when:
- URL format is invalid
- endpoint is blocked or unreachable by policy
- API validation/auth fails

```php
try {
    $success = Webhook::register('https://example.com/webhook');

    if (! $success) {
        // Handle API-level registration rejection
    }
} catch (\Throwable $e) {
    // Handle validation, network, or API errors
}
```

API reference: [Register URL Hook](https://docs.mayar.id/api-reference/webhook/registerurlhook)

## Test URL Hook

```php
use Bensondevs\Mayar\Api\Webhooks\Webhook;

$success = Webhook::test('https://example.com/webhook');
```

Returns: `bool` (`true` when test delivery succeeds).

```php
try {
    $success = Webhook::test('https://example.com/webhook');

    if (! $success) {
        // Handle failed test delivery
    }
} catch (\Throwable $e) {
    // Handle validation, network, or API errors
}
```

API reference: [Test URL Hook](https://docs.mayar.id/api-reference/webhook/testurlhook)

## Retry Webhook History

```php
use Bensondevs\Mayar\Api\Webhooks\Webhook;

$success = Webhook::retry('7d567063-ad7f-48d5-9e84-0e41938783a5');
```

Returns: `bool` (`true` when retry is accepted).

Failure cases can happen when:
- history ID does not exist
- webhook event is not retryable
- API validation/auth fails

```php
try {
    $success = Webhook::retry('7d567063-ad7f-48d5-9e84-0e41938783a5');

    if (! $success) {
        // Handle API-level retry rejection
    }
} catch (\Throwable $e) {
    // Handle validation, network, or API errors
}
```

API reference: [Retry History](https://docs.mayar.id/api-reference/webhook/retryhistory)
