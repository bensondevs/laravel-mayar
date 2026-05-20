<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Transactions;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class TransactionValidator
{
    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateForCreateQrCode(array $payload): void
    {
        $validator = Validator::make($payload, [
            'amount' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
