<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Events\Webhooks;

use Bensondevs\Mayar\DataTransferObject\MayarWebhookData;

abstract class MayarWebhookEvent
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public readonly MayarWebhookData $data,
        public readonly string $event,
        public readonly array $payload = [],
    ) {}
}
