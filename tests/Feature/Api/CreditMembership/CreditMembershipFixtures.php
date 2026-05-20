<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Feature\Api\CreditMembership;

final class CreditMembershipFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function customerBalanceResponse(): array
    {
        $path = __DIR__ . '/fixtures/credit-customer-balance-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function paginateHistoryResponse(): array
    {
        $path = __DIR__ . '/fixtures/credit-paginate-history-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function spendSuccessResponse(): array
    {
        $path = __DIR__ . '/fixtures/credit-spend-success-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function addCreditResponse(): array
    {
        $path = __DIR__ . '/fixtures/credit-add-credit-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function registerCustomerResponse(): array
    {
        $path = __DIR__ . '/fixtures/credit-register-customer-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    public static function productId(): string
    {
        return '40f26fbe-f4d8-4693-975f-e6d105d291e6';
    }

    public static function membershipTierId(): string
    {
        return '9bbbfa01-1bf8-4e4d-8470-cdf7066b6ea2';
    }

    public static function memberId(): string
    {
        return 'PQVS4KGY';
    }

    public static function customerId(): string
    {
        return 'faa4ee60-cf45-4043-b964-303890713bb9';
    }
}
