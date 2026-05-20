<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Http;

use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;

final class MayarPayload
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public static function statusCode(array $payload): ?int
    {
        $statusCode = $payload['statusCode'] ?? null;

        if (! is_numeric($statusCode)) {
            return null;
        }

        return (int) $statusCode;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function isOk(array $payload): bool
    {
        return HttpStatusCode::Ok->is(self::statusCode($payload));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function isNotFound(array $payload): bool
    {
        return HttpStatusCode::NotFound->is(self::statusCode($payload));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public static function data(array $payload): array
    {
        $data = $payload['data'] ?? [];

        return is_array($data) ? $data : [];
    }
}
