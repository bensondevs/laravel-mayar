<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\SaaSMembership;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class SaaSMembershipValidator
{
    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateLicensePayload(array $payload): void
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
