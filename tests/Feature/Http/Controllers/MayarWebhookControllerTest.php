<?php

declare(strict_types=1);

use Bensondevs\Mayar\Events\Webhooks\PaymentReceived;
use Bensondevs\Mayar\Http\Controllers\MayarWebhookController;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;

it('dispatches mapped webhook event from the default package route', function (): void {
    Event::fake();

    $response = $this->postJson('/webhooks/mayar', [
        'event' => 'payment.received',
        'data' => [
            'id' => 'trx-1',
            'amount' => 10000,
        ],
    ]);

    $response
        ->assertOk()
        ->assertJson([
            'status' => 'ok',
        ]);

    Event::assertDispatched(PaymentReceived::class, function (PaymentReceived $event): bool {
        return $event->event === 'payment.received'
            && $event->data->get('id') === 'trx-1'
            && $event->data->get('amount') === 10000;
    });
});

it('normalizes flat event.received payload key', function (): void {
    Event::fake();

    $response = $this->postJson('/webhooks/mayar', [
        'event.received' => 'payment.received',
        'data' => [
            'id' => 'trx-2',
        ],
    ]);

    $response->assertOk();

    Event::assertDispatched(PaymentReceived::class, function (PaymentReceived $event): bool {
        return $event->data->get('id') === 'trx-2';
    });
});

it('normalizes nested event.received payload key', function (): void {
    Event::fake();

    $response = $this->postJson('/webhooks/mayar', [
        'event' => [
            'received' => 'payment.received',
        ],
        'data' => [
            'id' => 'trx-3',
        ],
    ]);

    $response->assertOk();

    Event::assertDispatched(PaymentReceived::class, function (PaymentReceived $event): bool {
        return $event->data->get('id') === 'trx-3';
    });
});

it('returns ignored for unknown events by default', function (): void {
    Event::fake();

    $response = $this->postJson('/webhooks/mayar', [
        'event' => 'unknown.event',
        'data' => ['id' => 'trx-unknown'],
    ]);

    $response
        ->assertOk()
        ->assertJson([
            'status' => 'ignored',
        ]);

    Event::assertNotDispatched(PaymentReceived::class);
});

it('returns unprocessable entity for unknown events when ignore mode is disabled', function (): void {
    config()->set('mayar.webhook.ignore_unknown_events', false);

    Event::fake();

    $response = $this->postJson('/webhooks/mayar', [
        'event' => 'unknown.event',
        'data' => ['id' => 'trx-unknown'],
    ]);

    $response
        ->assertStatus(422)
        ->assertJson([
            'status' => 'error',
        ]);

    Event::assertNotDispatched(PaymentReceived::class);
});

it('returns ignored when event name cannot be resolved', function (): void {
    Event::fake();

    $response = $this->postJson('/webhooks/mayar', [
        'event' => '   ',
        'data' => ['id' => 'trx-missing-event'],
    ]);

    $response
        ->assertOk()
        ->assertJson([
            'status' => 'ignored',
            'message' => 'Unable to detect webhook event name.',
        ]);

    Event::assertNotDispatched(PaymentReceived::class);
});

it('allows manual user route registration with the package controller', function (): void {
    config()->set('mayar.webhook.enabled', false);

    Route::post('/my-custom-webhook', MayarWebhookController::class)
        ->name('custom.mayar.webhook');

    Event::fake();

    $response = $this->postJson('/my-custom-webhook', [
        'event' => 'payment.received',
        'data' => ['id' => 'trx-manual-route'],
    ]);

    $response->assertOk();

    Event::assertDispatched(PaymentReceived::class, function (PaymentReceived $event): bool {
        return $event->data->get('id') === 'trx-manual-route';
    });
});
