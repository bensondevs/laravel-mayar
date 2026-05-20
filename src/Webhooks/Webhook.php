<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Webhooks;

use Bensondevs\Mayar\Http\MayarPayload;
use Bensondevs\Mayar\Mayar;

final class Webhook
{
    public static function register(string $urlHook): bool
    {
        $payload = ['urlHook' => $urlHook];

        WebhookValidator::validateForRegister($payload);

        $endpoint = new WebhookEndpoint;
        $response = Mayar::client()->postUrl($endpoint->register(), $payload);

        return MayarPayload::isSuccessful($response);
    }

    public static function test(string $urlHook): bool
    {
        $payload = ['urlHook' => $urlHook];

        WebhookValidator::validateForTest($payload);

        $endpoint = new WebhookEndpoint;
        $response = Mayar::client()->postUrl($endpoint->test(), $payload);

        return MayarPayload::isSuccessful($response);
    }

    public static function retry(string $webhookHistoryId): bool
    {
        $payload = ['webhookHistoryId' => $webhookHistoryId];

        WebhookValidator::validateForRetry($payload);

        $endpoint = new WebhookEndpoint;
        $response = Mayar::client()->postUrl($endpoint->retry(), $payload);

        return MayarPayload::isSuccessful($response);
    }
}
