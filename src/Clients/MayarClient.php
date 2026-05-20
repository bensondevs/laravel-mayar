<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Clients;

use Bensondevs\Mayar\Enums\MayarMode;
use Bensondevs\Mayar\Exceptions\MayarRequestException;
use Bensondevs\Mayar\Http\Authentication;
use Bensondevs\Mayar\Http\Endpoint;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class MayarClient
{
    protected Endpoint $endpoint;

    public function __construct(
        protected MayarMode $mode,
        protected ?string $apiKey = null,
    ) {
        $this->endpoint = new Endpoint($mode);
    }

    public function mode(): MayarMode
    {
        return $this->mode;
    }

    public function endpoint(): Endpoint
    {
        return $this->endpoint;
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function get(string $uri, array $query = []): array
    {
        return $this->request('get', $uri, $query);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function post(string $uri, array $data = []): array
    {
        return $this->request('post', $uri, [], $data);
    }

    public function getCustomers(array $query = []): array
    {
        $response = $this->send('get', $this->endpoint->customers(), $query);

        return $this->unwrapListResponse($response);
    }

    public function getCustomerByEmail(string $email): array
    {
        $response = $this->send('get', $this->endpoint->customerByEmail(), ['email' => $email]);

        return $this->unwrapResponse($response);
    }

    public function getProducts(array $query = []): array
    {
        $response = $this->send('get', $this->endpoint->products(), $query);

        return $this->unwrapListResponse($response);
    }

    public function getProduct(string $id): array
    {
        $response = $this->send('get', $this->endpoint->product($id));

        return $this->unwrapResponse($response);
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    protected function request(string $method, string $uri, array $query = [], array $body = []): array
    {
        $url = str_starts_with($uri, 'http')
            ? $uri
            : $this->endpoint->url($uri);

        $response = $this->send($method, $url, $query, $body);

        return $this->unwrapResponse($response);
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, mixed>  $body
     */
    protected function send(string $method, string $url, array $query = [], array $body = []): Response
    {
        $pending = Http::withHeaders(Authentication::headers($this->apiKey));

        $response = match ($method) {
            'post' => $pending->post($url, $body),
            default => $pending->get($url, $query),
        };

        if ($response->failed()) {
            $this->throwRequestException($response);
        }

        $payload = $response->json();

        if (! is_array($payload)) {
            throw new MayarRequestException(
                'Mayar API returned an invalid response.',
                $response->status(),
            );
        }

        $statusCode = $payload['statusCode'] ?? $response->status();

        if ((int) $statusCode !== 200) {
            $this->throwRequestException($response, $payload);
        }

        return $response;
    }

    /**
     * @return array<string, mixed>
     */
    protected function unwrapResponse(Response $response): array
    {
        $payload = $response->json();

        return is_array($payload) ? $payload : [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function unwrapListResponse(Response $response): array
    {
        return $this->unwrapResponse($response);
    }

    /**
     * @param  array<string, mixed>|null  $payload
     *
     * @throws MayarRequestException
     */
    protected function throwRequestException(Response $response, ?array $payload = null): never
    {
        $payload ??= $response->json();
        $mayarMessage = is_array($payload) ? ($payload['messages'] ?? null) : null;
        $statusCode = is_array($payload)
            ? (int) ($payload['statusCode'] ?? $response->status())
            : $response->status();

        throw new MayarRequestException(
            $mayarMessage ?? 'Mayar API request failed.',
            $statusCode,
            is_string($mayarMessage) ? $mayarMessage : null,
            is_array($payload) ? $payload : null,
        );
    }
}
