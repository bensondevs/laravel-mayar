<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Installments;

use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Models\MayarQuery;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;

/**
 * @extends MayarQuery<Installment>
 */
class InstallmentQuery extends MayarQuery
{
    public function find(string $id): ?Installment
    {
        $endpoint = new InstallmentEndpoint;

        $payload = static::mayarClient()->getUrl($endpoint->show($id));

        if (HttpStatusCode::NotFound->is($payload['statusCode'] ?? null)) {
            return null;
        }

        $data = $payload['data'] ?? null;

        if (! is_array($data) || $data === []) {
            return null;
        }

        return Installment::fromMayar($data);
    }

    public function findOrFail(string $id): Installment
    {
        $installment = $this->find($id);

        if ($installment === null) {
            throw new MayarNotFoundException(modelClass: Installment::class, ids: [$id]);
        }

        return $installment;
    }
}
