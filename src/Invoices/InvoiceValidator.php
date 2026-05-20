<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Invoices;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class InvoiceValidator
{
    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateForCreate(array $payload): void
    {
        $validator = Validator::make($payload, self::createRules());

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
        $validator = Validator::make($payload, self::editRules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected static function createRules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'mobile' => ['required', 'string'],
            'redirectUrl' => ['required', 'url'],
            'description' => ['required', 'string'],
            'expiredAt' => ['required', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.rate' => ['required', 'integer', 'min:0'],
            'items.*.description' => ['required', 'string'],
            'extraData' => ['required', 'array'],
            'extraData.noCustomer' => ['required', 'string'],
            'extraData.idProd' => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function editRules(): array
    {
        return [
            'id' => ['required', 'uuid'],
            'name' => ['sometimes', 'string'],
            'email' => ['sometimes', 'email'],
            'mobile' => ['sometimes', 'string'],
            'redirectUrl' => ['sometimes', 'url'],
            'description' => ['sometimes', 'string'],
            'expiredAt' => ['sometimes', 'string'],
            'items' => ['sometimes', 'array', 'min:1'],
            'items.*.quantity' => ['required_with:items', 'integer', 'min:1'],
            'items.*.rate' => ['required_with:items', 'integer', 'min:0'],
            'items.*.description' => ['required_with:items', 'string'],
        ];
    }
}
