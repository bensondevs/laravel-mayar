<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Http\Controllers;

use Bensondevs\Mayar\DataTransferObject\MayarWebhookData;
use Bensondevs\Mayar\Support\Webhooks\MayarWebhookEventMap;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

final class MayarWebhookController
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var array<string, mixed> $payload */
        $payload = $request->all();
        $eventName = $this->resolveEventName($payload);

        if ($eventName === null) {
            return response()->json([
                'status' => 'ignored',
                'message' => 'Unable to detect webhook event name.',
            ]);
        }

        $eventClass = MayarWebhookEventMap::resolve($eventName);

        if ($eventClass === null) {
            if (! (bool) config('mayar.webhook.ignore_unknown_events', true)) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Unsupported webhook event [{$eventName}].",
                ], 422);
            }

            return response()->json([
                'status' => 'ignored',
                'message' => "Unsupported webhook event [{$eventName}].",
            ]);
        }

        $data = MayarWebhookData::fromArray($this->resolveDataPayload($payload));

        Event::dispatch(new $eventClass($data, $eventName, $payload));

        return response()->json([
            'status' => 'ok',
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function resolveEventName(array $payload): ?string
    {
        if (isset($payload['event']) && is_string($payload['event']) && filled($payload['event'])) {
            return trim($payload['event']);
        }

        if (
            isset($payload['event.received']) &&
            is_string($payload['event.received']) &&
            filled($payload['event.received'])
        ) {
            return trim($payload['event.received']);
        }

        if (
            isset($payload['event']['received']) &&
            is_array($payload['event']) &&
            is_string($payload['event']['received']) &&
            filled($payload['event']['received'])
        ) {
            return trim($payload['event']['received']);
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function resolveDataPayload(array $payload): array
    {
        if (! isset($payload['data']) || blank($payload['data']) || ! is_array($payload['data'])) {
            return [];
        }

        return $payload['data'];
    }
}
