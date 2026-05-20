<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Feature\Api\CreditBasedProduct;

final class CreditBasedProductFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function balanceResponse(): array
    {
        $path = __DIR__ . '/fixtures/credit-usage-balance-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function paginateHistoryResponse(): array
    {
        $path = __DIR__ . '/fixtures/credit-usage-paginate-history-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function spendResponse(): array
    {
        $path = __DIR__ . '/fixtures/credit-usage-spend-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function addCreditResponse(): array
    {
        $path = __DIR__ . '/fixtures/credit-usage-add-credit-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function registerResponse(): array
    {
        $path = __DIR__ . '/fixtures/credit-usage-register-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function checkoutResponse(): array
    {
        $path = __DIR__ . '/fixtures/credit-usage-checkout-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    public static function productId(): string
    {
        return '40f26fbe-f4d8-4693-975f-e6d105d291e6';
    }

    public static function customerId(): string
    {
        return '9de4b4b4-525c-4ee0-ac9c-b29c3e10fe55';
    }

    public static function customerIdForHistory(): string
    {
        return 'faa4ee60-cf45-4043-b964-303890713bb9';
    }

    public static function registerProductId(): string
    {
        return '43eadce2-bc52-4e38-a832-93fb835f2a69';
    }
}
