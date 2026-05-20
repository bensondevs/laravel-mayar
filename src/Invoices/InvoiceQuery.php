<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Invoices;

use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Invoices\Enums\InvoiceSort;
use Bensondevs\Mayar\Models\MayarQuery;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

/**
 * @extends MayarQuery<Invoice>
 */
class InvoiceQuery extends MayarQuery
{
    protected ?InvoiceSort $filterSort = null;

    public function sort(InvoiceSort | string $sort): static
    {
        if ($sort instanceof InvoiceSort) {
            $this->filterSort = $sort;
        } else {
            $resolved = InvoiceSort::find($sort);

            if ($resolved === null) {
                throw new InvalidArgumentException('Invalid invoice sort: ' . $sort);
            }

            $this->filterSort = $resolved;
        }

        return $this;
    }

    /**
     * @return LengthAwarePaginator<int, Invoice>
     */
    public function paginate(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        $query = [];

        if (filled($this->filterSort)) {
            $query['sort'] = $this->filterSort->value;
        }

        return $this->paginateMayarList(
            url: $this->listUrl(),
            page: $page,
            perPage: $perPage,
            query: $query,
        );
    }

    public function find(string $id): ?Invoice
    {
        $endpoint = new InvoiceEndpoint;

        $payload = static::mayarClient()->getUrl($endpoint->show($id));

        if (HttpStatusCode::NotFound->is($payload['statusCode'] ?? null)) {
            return null;
        }

        $data = $payload['data'] ?? null;

        if (! is_array($data) || $data === []) {
            return null;
        }

        return Invoice::fromMayar($data);
    }

    public function findOrFail(string $id): Invoice
    {
        $invoice = $this->find($id);

        if ($invoice === null) {
            throw new MayarNotFoundException(modelClass: Invoice::class, ids: [$id]);
        }

        return $invoice;
    }

    protected function listUrl(): string
    {
        return (new InvoiceEndpoint)->index();
    }
}
