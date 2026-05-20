<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\CreditBasedProduct;

use Bensondevs\Mayar\Http\MayarPayload;
use Bensondevs\Mayar\Mayar;

final class CreditBasedProduct
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function customerBalance(array $attributes): CreditUsageBalanceResult
    {
        $query = [
            'productId' => $attributes['productId'] ?? null,
            'customerId' => $attributes['customerId'] ?? null,
        ];

        CreditBasedProductValidator::validateForCustomerBalance($query);

        $endpoint = new CreditBasedProductEndpoint;
        $response = Mayar::client()->getUrl($endpoint->customerBalance(), $query);

        return CreditUsageBalanceResult::fromMayar($response);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function spend(array $attributes): bool
    {
        $payload = [
            'productId' => $attributes['productId'] ?? null,
            'customerId' => $attributes['customerId'] ?? null,
            'amount' => $attributes['amount'] ?? null,
        ];

        CreditBasedProductValidator::validateForSpendOrAddCredit($payload);

        $endpoint = new CreditBasedProductEndpoint;
        $response = Mayar::client()->postUrl($endpoint->spend(), $payload);

        return MayarPayload::isOk($response);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function addCredit(array $attributes): CreditUsageAddCreditResult
    {
        $payload = [
            'productId' => $attributes['productId'] ?? null,
            'customerId' => $attributes['customerId'] ?? null,
            'amount' => $attributes['amount'] ?? null,
        ];

        CreditBasedProductValidator::validateForSpendOrAddCredit($payload);

        $endpoint = new CreditBasedProductEndpoint;
        $response = Mayar::client()->postUrl($endpoint->addCredit(), $payload);

        return CreditUsageAddCreditResult::fromMayar(MayarPayload::data($response));
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function registerCustomer(array $attributes): CreditUsageRegisterCustomerResult
    {
        $payload = [
            'productId' => $attributes['productId'] ?? null,
            'trialCredit' => $attributes['trialCredit'] ?? null,
            'customerInfo' => $attributes['customerInfo'] ?? null,
        ];

        CreditBasedProductValidator::validateForRegisterCustomer($payload);

        $endpoint = new CreditBasedProductEndpoint;
        $response = Mayar::client()->postUrl($endpoint->registerCreditUsageCustomer(), $payload);

        return CreditUsageRegisterCustomerResult::fromMayar(MayarPayload::data($response));
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function generateImmutableCheckout(array $attributes): ImmutableCheckoutLinkResult
    {
        $payload = [
            'productId' => $attributes['productId'] ?? null,
            'creditAmount' => $attributes['creditAmount'] ?? null,
            'customerInfo' => $attributes['customerInfo'] ?? null,
        ];

        CreditBasedProductValidator::validateForImmutableCheckout($payload);

        $endpoint = new CreditBasedProductEndpoint;
        $response = Mayar::client()->postUrl($endpoint->generateImmutableCheckout(), $payload);

        return ImmutableCheckoutLinkResult::fromMayar($response);
    }
}
