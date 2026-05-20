<?php

declare(strict_types=1);

use Bensondevs\Mayar\Tests\Feature\Webhooks\WebhookFixtures;
use Bensondevs\Mayar\Webhooks\Webhook;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('tests a webhook url hook', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/webhook/test' => Http::response(
            body: WebhookFixtures::webhookSuccessResponse(),
        ),
    ]);

    $success = Webhook::test('https://example.mayar.com');

    expect($success)->toBeTrue();

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/webhook/test') {
            return false;
        }

        return $request->data()['urlHook'] === 'https://example.mayar.com';
    });
});

it('throws validation exception when test url hook is missing', function (): void {
    Webhook::test('');
})->throws(ValidationException::class);
