<?php

declare(strict_types=1);

use Bensondevs\Mayar\Tests\Feature\Api\Webhooks\WebhookFixtures;
use Bensondevs\Mayar\Api\Webhooks\Webhook;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('registers a webhook url hook', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/webhook/register' => Http::response(
            body: WebhookFixtures::webhookSuccessResponse(),
        ),
    ]);

    $success = Webhook::register('https://example.mayar.com');

    expect($success)->toBeTrue();

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/webhook/register') {
            return false;
        }

        return $request->data()['urlHook'] === 'https://example.mayar.com';
    });
});

it('throws validation exception when register url hook is missing', function (): void {
    Webhook::register('');
})->throws(ValidationException::class);
