<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Feature\PaymentRequests;

final class PaymentRequestFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function paymentRequestListResponse(): array
    {
        $path = __DIR__ . '/fixtures/payment-request-list-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function paymentRequestListData(): array
    {
        return self::paymentRequestListResponse()['data'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function paymentRequestListFirstItem(): array
    {
        return self::paymentRequestListData()[0];
    }

    /**
     * @return array<string, mixed>
     */
    public static function paymentRequestDetailResponse(): array
    {
        $path = __DIR__ . '/fixtures/payment-request-detail-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function paymentRequestDetailData(): array
    {
        return self::paymentRequestDetailResponse()['data'];
    }

    public static function paymentRequestDetailId(): string
    {
        return self::paymentRequestDetailData()['id'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function paymentRequestCreateResponse(): array
    {
        $path = __DIR__ . '/fixtures/payment-request-create-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function paymentRequestEditResponse(): array
    {
        $path = __DIR__ . '/fixtures/payment-request-edit-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }
}
