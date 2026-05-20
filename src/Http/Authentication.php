<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Http;

use Bensondevs\Mayar\Exceptions\MayarException;

class Authentication
{
    /**
     * @return array<string, string>
     *
     * @throws MayarException
     */
    public static function headers(?string $apiKey = null): array
    {
        $apiKey ??= config('mayar.api_key');

        if (blank($apiKey)) {
            throw new MayarException('Mayar API key is not configured.');
        }

        return [
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
