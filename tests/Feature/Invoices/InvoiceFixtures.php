<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Feature\Invoices;

final class InvoiceFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function invoiceListResponse(): array
    {
        $path = __DIR__ . '/fixtures/invoice-list-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function invoiceListData(): array
    {
        return self::invoiceListResponse()['data'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function invoiceListFirstItem(): array
    {
        return self::invoiceListData()[0];
    }

    /**
     * @return array<string, mixed>
     */
    public static function invoiceDetailResponse(): array
    {
        $path = __DIR__ . '/fixtures/invoice-detail-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function invoiceDetailData(): array
    {
        return self::invoiceDetailResponse()['data'];
    }

    public static function invoiceDetailId(): string
    {
        return self::invoiceDetailData()['id'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function invoiceCreateResponse(): array
    {
        $path = __DIR__ . '/fixtures/invoice-create-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function invoiceEditResponse(): array
    {
        $path = __DIR__ . '/fixtures/invoice-edit-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }
}
