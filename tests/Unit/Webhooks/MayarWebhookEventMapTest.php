<?php

declare(strict_types=1);

use Bensondevs\Mayar\Events\Webhooks\MembershipChangeTierMemberRegistered;
use Bensondevs\Mayar\Events\Webhooks\MembershipMemberExpired;
use Bensondevs\Mayar\Events\Webhooks\MembershipMemberUnsubscribed;
use Bensondevs\Mayar\Events\Webhooks\MembershipNewMemberRegistered;
use Bensondevs\Mayar\Events\Webhooks\PaymentReceived;
use Bensondevs\Mayar\Events\Webhooks\PaymentReminder;
use Bensondevs\Mayar\Events\Webhooks\ShipperStatus;
use Bensondevs\Mayar\Support\Webhooks\MayarWebhookEventMap;

it('resolves all documented mayar webhook event types', function (): void {
    expect(MayarWebhookEventMap::resolve('payment.received'))->toBe(PaymentReceived::class)
        ->and(MayarWebhookEventMap::resolve('payment.reminder'))->toBe(PaymentReminder::class)
        ->and(MayarWebhookEventMap::resolve('shipper.status'))->toBe(ShipperStatus::class)
        ->and(MayarWebhookEventMap::resolve('membership.memberUnsubscribed'))->toBe(MembershipMemberUnsubscribed::class)
        ->and(MayarWebhookEventMap::resolve('membership.memberExpired'))->toBe(MembershipMemberExpired::class)
        ->and(MayarWebhookEventMap::resolve('membership.changeTierMemberRegistered'))->toBe(MembershipChangeTierMemberRegistered::class)
        ->and(MayarWebhookEventMap::resolve('membership.newMemberRegistered'))->toBe(MembershipNewMemberRegistered::class);
});

it('returns null for unknown webhook events', function (): void {
    expect(MayarWebhookEventMap::resolve('unknown.event'))->toBeNull();
});
