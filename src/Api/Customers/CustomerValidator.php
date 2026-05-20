<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Customers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class CustomerValidator
{
    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateForCreate(array $payload): void
    {
        $validator = Validator::make($payload, [
            'name' => ['required', 'string'],
            'email' => ['required', 'string'],
            'mobile' => ['required', 'string'],
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
    public static function validateForUpdateEmail(array $payload): void
    {
        $validator = Validator::make($payload, [
            'fromEmail' => ['required', 'string'],
            'toEmail' => ['required', 'string'],
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
    public static function validateForPortalLogin(array $payload): void
    {
        $validator = Validator::make($payload, [
            'email' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
