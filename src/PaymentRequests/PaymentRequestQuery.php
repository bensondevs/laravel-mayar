<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\PaymentRequests;

use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Models\MayarQuery;
use Bensondevs\Mayar\PaymentRequests\Enums\PaymentRequestStatus;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

/**
 * @extends MayarQuery<PaymentRequest>
 */
class PaymentRequestQuery extends MayarQuery
{
    protected ?PaymentRequestStatus $filterStatus = null;

    public function status(PaymentRequestStatus | string $status): static
    {
        if ($status instanceof PaymentRequestStatus) {
            $this->filterStatus = $status;
        } else {
            $resolved = PaymentRequestStatus::find($status);

            if ($resolved === null) {
                throw new InvalidArgumentException('Invalid payment request status: ' . $status);
            }

            $this->filterStatus = $resolved;
        }

        return $this;
    }

    /**
     * @return LengthAwarePaginator<int, PaymentRequest>
     */
    public function paginate(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        $query = [];

        if (filled($this->filterStatus)) {
            $query['status'] = $this->filterStatus->value;
        }

        return $this->paginateMayarList(
            url: $this->listUrl(),
            page: $page,
            perPage: $perPage,
            query: $query,
        );
    }

    public function find(string $id): ?PaymentRequest
    {
        $endpoint = new PaymentRequestEndpoint;

        $payload = static::mayarClient()->getUrl($endpoint->show($id));

        if ($this->isPayloadNotFound($payload)) {
            return null;
        }

        $data = $payload['data'] ?? null;

        if (! is_array($data) || $data === []) {
            return null;
        }

        return PaymentRequest::fromMayar($data);
    }

    public function findOrFail(string $id): PaymentRequest
    {
        $paymentRequest = $this->find($id);

        if ($paymentRequest === null) {
            throw new MayarNotFoundException(modelClass: PaymentRequest::class, ids: [$id]);
        }

        return $paymentRequest;
    }

    protected function listUrl(): string
    {
        return (new PaymentRequestEndpoint)->index();
    }
}
