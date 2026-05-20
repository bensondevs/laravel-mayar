# 🪝 Webhooks

Module namespace: `Bensondevs\Mayar\Api\Webhooks\`

Webhooks cover history retrieval and register/test/retry actions.

## Get Webhook History

```php
use Bensondevs\Mayar\Api\Webhooks\WebhookHistory;

$paginator = WebhookHistory::paginate(page: 1, perPage: 10);
```

API reference: [Get History](https://docs.mayar.id/api-reference/webhook/history)

## Register URL Hook

```php
use Bensondevs\Mayar\Api\Webhooks\Webhook;

$success = Webhook::register('https://example.com/webhook');
```

API reference: [Register URL Hook](https://docs.mayar.id/api-reference/webhook/registerurlhook)

## Test URL Hook

```php
use Bensondevs\Mayar\Api\Webhooks\Webhook;

$success = Webhook::test('https://example.com/webhook');
```

API reference: [Test URL Hook](https://docs.mayar.id/api-reference/webhook/testurlhook)

## Retry Webhook History

```php
use Bensondevs\Mayar\Api\Webhooks\Webhook;

$success = Webhook::retry('7d567063-ad7f-48d5-9e84-0e41938783a5');
```

API reference: [Retry History](https://docs.mayar.id/api-reference/webhook/retryhistory)
