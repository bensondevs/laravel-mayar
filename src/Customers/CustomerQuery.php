<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Customers;

use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Models\MayarQuery;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends MayarQuery<Customer>
 */
class CustomerQuery extends MayarQuery
{
    /**
     * @return LengthAwarePaginator<int, Customer>
     */
    public function paginate(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        $endpoint = new CustomerEndpoint;

        return $this->paginateMayarList(
            url: $endpoint->index(),
            page: $page,
            perPage: $perPage,
        );
    }

    public function findByEmail(string $email): ?Customer
    {
        $endpoint = new CustomerEndpoint;

        $payload = static::mayarClient()->getUrl(
            url: $endpoint->detail(),
            query: ['email' => $email],
        );

        if (HttpStatusCode::NotFound->is($payload['statusCode'] ?? null)) {
            return null;
        }

        $data = $payload['data'] ?? null;

        if (! is_array($data) || $data === []) {
            return null;
        }

        return Customer::fromMayar($data);
    }

    public function findByEmailOrFail(string $email): Customer
    {
        $customer = $this->findByEmail($email);

        if ($customer === null) {
            throw new MayarNotFoundException(modelClass: Customer::class, ids: [$email]);
        }

        return $customer;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function resolveTotal(array $payload, int $perPage): int
    {
        if (isset($payload['totalCustomer']) && is_numeric($payload['totalCustomer'])) {
            return (int) $payload['totalCustomer'];
        }

        return parent::resolveTotal($payload, $perPage);
    }
}
