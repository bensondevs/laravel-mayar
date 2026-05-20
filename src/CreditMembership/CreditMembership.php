<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\CreditMembership;

use Bensondevs\Mayar\Http\MayarPayload;
use Bensondevs\Mayar\Mayar;

final class CreditMembership
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function customerBalance(array $attributes): CustomerCreditBalanceResult
    {
        $query = [
            'productId' => $attributes['productId'] ?? null,
            'membershipTierId' => $attributes['membershipTierId'] ?? null,
            'memberId' => $attributes['memberId'] ?? null,
            'customerId' => $attributes['customerId'] ?? null,
        ];

        CreditMembershipValidator::validateForCustomerBalance($query);

        $endpoint = new CreditMembershipEndpoint;
        $response = Mayar::client()->getUrl($endpoint->customerBalance(), $query);

        return CustomerCreditBalanceResult::fromMayar($response);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function spend(array $attributes): bool
    {
        $payload = [
            'productId' => $attributes['productId'] ?? null,
            'membershipTierId' => $attributes['membershipTierId'] ?? null,
            'amount' => $attributes['amount'] ?? null,
            'memberId' => $attributes['memberId'] ?? null,
            'customerId' => $attributes['customerId'] ?? null,
        ];

        CreditMembershipValidator::validateForSpendOrAddCredit($payload);

        $endpoint = new CreditMembershipEndpoint;
        $response = Mayar::client()->postUrl($endpoint->spend(), $payload);

        return MayarPayload::isSuccessful($response);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function addCredit(array $attributes): AddCustomerCreditResult
    {
        $payload = [
            'productId' => $attributes['productId'] ?? null,
            'membershipTierId' => $attributes['membershipTierId'] ?? null,
            'amount' => $attributes['amount'] ?? null,
            'memberId' => $attributes['memberId'] ?? null,
            'customerId' => $attributes['customerId'] ?? null,
        ];

        CreditMembershipValidator::validateForSpendOrAddCredit($payload);

        $endpoint = new CreditMembershipEndpoint;
        $response = Mayar::client()->postUrl($endpoint->addCredit(), $payload);

        return AddCustomerCreditResult::fromMayar($response);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function registerCustomer(array $attributes): RegisterMembershipCustomerResult
    {
        $payload = [
            'productId' => $attributes['productId'] ?? null,
            'membershipTierId' => $attributes['membershipTierId'] ?? null,
            'membershipMonthlyPeriod' => $attributes['membershipMonthlyPeriod'] ?? null,
            'trialCredit' => $attributes['trialCredit'] ?? null,
            'customerInfo' => $attributes['customerInfo'] ?? null,
        ];

        CreditMembershipValidator::validateForRegisterCustomer($payload);

        $endpoint = new CreditMembershipEndpoint;
        $response = Mayar::client()->postUrl($endpoint->registerCustomer(), $payload);

        return RegisterMembershipCustomerResult::fromMayar($response);
    }
}
