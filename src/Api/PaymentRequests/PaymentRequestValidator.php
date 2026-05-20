<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\PaymentRequests;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class PaymentRequestValidator
{
    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateForCreate(array $payload): void
    {
        $validator = Validator::make($payload, self::fieldRules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateForEdit(array $payload): void
    {
        $rules = array_merge(['id' => ['required', 'uuid']], self::fieldRules());

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected static function fieldRules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'amount' => ['required', 'integer', 'min:0'],
            'mobile' => ['required', 'string'],
            'redirectUrl' => ['required', 'url'],
            'description' => ['required', 'string'],
            'expiredAt' => ['required', 'string'],
        ];
    }
}
