<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Support\Webhooks;

use Bensondevs\Mayar\Events\Webhooks\MembershipChangeTierMemberRegistered;
use Bensondevs\Mayar\Events\Webhooks\MembershipMemberExpired;
use Bensondevs\Mayar\Events\Webhooks\MembershipMemberUnsubscribed;
use Bensondevs\Mayar\Events\Webhooks\MembershipNewMemberRegistered;
use Bensondevs\Mayar\Events\Webhooks\PaymentReceived;
use Bensondevs\Mayar\Events\Webhooks\PaymentReminder;
use Bensondevs\Mayar\Events\Webhooks\ShipperStatus;

final class MayarWebhookEventMap
{
    /**
     * @return array<string, class-string>
     */
    public static function all(): array
    {
        return [
            'payment.received' => PaymentReceived::class,
            'payment.reminder' => PaymentReminder::class,
            'shipper.status' => ShipperStatus::class,
            'membership.memberUnsubscribed' => MembershipMemberUnsubscribed::class,
            'membership.memberExpired' => MembershipMemberExpired::class,
            'membership.changeTierMemberRegistered' => MembershipChangeTierMemberRegistered::class,
            'membership.newMemberRegistered' => MembershipNewMemberRegistered::class,
        ];
    }

    public static function resolve(string $eventName): ?string
    {
        return static::all()[$eventName] ?? null;
    }
}
