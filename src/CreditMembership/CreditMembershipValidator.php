<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\CreditMembership;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class CreditMembershipValidator
{
    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ValidationException
     */
    public static function validateForCustomerBalance(array $payload): void
    {
        $validator = Validator::make($payload, [
            'productId' => ['required', 'string'],
            'membershipTierId' => ['required', 'string'],
            'memberId' => ['nullable', 'string', 'required_without:customerId'],
            'customerId' => ['nullable', 'string', 'required_without:memberId'],
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
    public static function validateForSpendOrAddCredit(array $payload): void
    {
        $validator = Validator::make($payload, [
            'productId' => ['required', 'string'],
            'membershipTierId' => ['required', 'string'],
            'amount' => ['required', 'integer', 'min:1'],
            'memberId' => ['nullable', 'string', 'required_without:customerId'],
            'customerId' => ['nullable', 'string', 'required_without:memberId'],
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
    public static function validateForRegisterCustomer(array $payload): void
    {
        $validator = Validator::make($payload, [
            'productId' => ['required', 'string'],
            'membershipTierId' => ['required', 'string'],
            'membershipMonthlyPeriod' => ['required', 'integer', 'in:1,3,6,12'],
            'trialCredit' => ['nullable', 'integer', 'min:0'],
            'customerInfo' => ['required', 'array'],
            'customerInfo.name' => ['required', 'string'],
            'customerInfo.email' => ['required', 'string'],
            'customerInfo.mobile' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param  array<string, mixed>  $query
     *
     * @throws ValidationException
     */
    public static function validateForPaginateHistory(array $query): void
    {
        $validator = Validator::make($query, [
            'productId' => ['required', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1'],
            'membershipTierId' => ['nullable', 'string'],
            'sortField' => ['nullable', 'string'],
            'sortOrder' => ['nullable', 'string', 'in:asc,desc'],
            'startDate' => ['nullable', 'string'],
            'endDate' => ['nullable', 'string'],
            'walletType' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
