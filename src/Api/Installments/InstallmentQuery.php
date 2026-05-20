<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Installments;

use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Models\MayarQuery;

/**
 * @extends MayarQuery<Installment>
 */
class InstallmentQuery extends MayarQuery
{
    public function find(string $id): ?Installment
    {
        $endpoint = new InstallmentEndpoint;

        $payload = static::mayarClient()->getUrl($endpoint->show($id));

        if ($this->isPayloadNotFound($payload)) {
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
