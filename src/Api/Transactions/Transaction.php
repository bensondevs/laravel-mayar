<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Transactions;

use Bensondevs\Mayar\Http\MayarPayload;
use Bensondevs\Mayar\Mayar;

final class Transaction
{
    /**
     * @return array<string, mixed>
     */
    public static function accountBalance(): array
    {
        $endpoint = new TransactionEndpoint;
        $response = Mayar::client()->getUrl($endpoint->balance());

        return MayarPayload::data($response);
    }

    /**
     * @return array<string, mixed>
     */
    public static function daily(): array
    {
        $endpoint = new TransactionEndpoint;
        $response = Mayar::client()->getUrl($endpoint->daily());

        return MayarPayload::data($response);
    }

    public static function createDynamicQrCode(int $amount): DynamicQrCodeResult
    {
        $payload = ['amount' => $amount];

        TransactionValidator::validateForCreateQrCode($payload);

        $endpoint = new TransactionEndpoint;
        $response = Mayar::client()->postUrl($endpoint->createQrCode(), $payload);

        return DynamicQrCodeResult::fromMayar(MayarPayload::data($response));
    }
}
