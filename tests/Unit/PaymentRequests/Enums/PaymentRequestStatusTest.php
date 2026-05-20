<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\PaymentRequests\Enums\PaymentRequestStatus;

it('resolves payment request status from string', function (): void {
    expect(PaymentRequestStatus::find('active'))->toBe(PaymentRequestStatus::Active)
        ->and(PaymentRequestStatus::find('paid'))->toBe(PaymentRequestStatus::Paid)
        ->and(PaymentRequestStatus::find('closed'))->toBe(PaymentRequestStatus::Closed)
        ->and(PaymentRequestStatus::find('invalid'))->toBeNull();
});
