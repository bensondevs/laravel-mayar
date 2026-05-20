<?php

declare(strict_types=1);

use Bensondevs\Mayar\Tests\Feature\Api\Webhooks\WebhookFixtures;
use Bensondevs\Mayar\Api\Webhooks\Webhook;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('retries a webhook history delivery', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/webhook/retry' => Http::response(
            body: WebhookFixtures::webhookSuccessResponse(),
        ),
    ]);

    $success = Webhook::retry(WebhookFixtures::webhookHistoryListFirstId());

    expect($success)->toBeTrue();

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/webhook/retry') {
            return false;
        }

        return $request->data()['webhookHistoryId'] === WebhookFixtures::webhookHistoryListFirstId();
    });
});

it('throws validation exception when webhook history id is missing', function (): void {
    Webhook::retry('');
})->throws(ValidationException::class);
