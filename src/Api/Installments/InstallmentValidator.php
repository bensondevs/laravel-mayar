<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Installments;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class InstallmentValidator
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
            'email' => ['required', 'email'],
            'mobile' => ['required', 'string'],
            'amount' => ['required', 'integer', 'min:0'],
            'installment' => ['required', 'array'],
            'installment.description' => ['required', 'string'],
            'installment.interest' => ['required', 'integer', 'min:0'],
            'installment.tenure' => ['required', 'integer', 'min:1'],
            'installment.dueDate' => ['required', 'integer', 'min:1', 'max:31'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
