<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Discounts;

use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Models\MayarQuery;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;

/**
 * @extends MayarQuery<Discount>
 */
class DiscountQuery extends MayarQuery
{
    public function find(string $id): ?Discount
    {
        $endpoint = new DiscountEndpoint;

        $payload = static::mayarClient()->getUrl($endpoint->show($id));

        if (HttpStatusCode::NotFound->is($payload['statusCode'] ?? null)) {
            return null;
        }

        $data = $payload['data'] ?? null;

        if (! is_array($data) || $data === []) {
            return null;
        }

        return Discount::fromMayar($data);
    }

    public function findOrFail(string $id): Discount
    {
        $discount = $this->find($id);

        if ($discount === null) {
            throw new MayarNotFoundException(modelClass: Discount::class, ids: [$id]);
        }

        return $discount;
    }
}
