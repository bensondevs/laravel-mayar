<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\SoftwareLicenseCodes;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class SoftwareLicenseCodeValidator
{
    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateForVerify(array $payload): void
    {
        $validator = Validator::make($payload, [
            'licenseCode' => ['required', 'string'],
            'productId' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
