<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Webhooks;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class WebhookValidator
{
    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateForRegister(array $payload): void
    {
        $validator = Validator::make($payload, [
            'urlHook' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateForTest(array $payload): void
    {
        $validator = Validator::make($payload, [
            'urlHook' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateForRetry(array $payload): void
    {
        $validator = Validator::make($payload, [
            'webhookHistoryId' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
