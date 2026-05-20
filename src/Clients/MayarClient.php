<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Clients;

use Bensondevs\Mayar\Enums\MayarMode;
use Bensondevs\Mayar\Exceptions\MayarRequestException;
use Bensondevs\Mayar\Http\Authentication;
use Bensondevs\Mayar\Http\Endpoint;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;
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
     *
     * @throws MayarRequestException
     */
    public function get(string $uri, array $query = []): array
    {
        return $this->request(method: 'get', uri: $uri, query: $query);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     *
     * @throws MayarRequestException
     */
    public function post(string $uri, array $data = []): array
    {
        return $this->request(method: 'post', uri: $uri, query: [], body: $data);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     *
     * @throws MayarRequestException
     */
    public function getUrl(string $url, array $query = []): array
    {
        $response = $this->send(method: 'get', url: $url, query: $query);

        return $this->unwrapResponse($response);
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     *
     * @throws MayarRequestException
     */
    protected function request(string $method, string $uri, array $query = [], array $body = []): array
    {
        $url = str_starts_with($uri, 'http')
            ? $uri
            : $this->endpoint->url($uri);

        $response = $this->send(method: $method, url: $url, query: $query, body: $body);

        return $this->unwrapResponse($response);
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, mixed>  $body
     *
     * @throws MayarRequestException
     */
    public function send(string $method, string $url, array $query = [], array $body = []): Response
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
            $this->throwRequestException($response);
        }

        $statusCode = (int) ($payload['statusCode'] ?? $response->status());

        if (! HttpStatusCode::Ok->is($statusCode)) {
            if (HttpStatusCode::NotFound->is($statusCode)) {
                return $response;
            }

            $this->throwRequestException(response: $response, payload: $payload);
        }

        return $response;
    }

    /**
     * @return array<string, mixed>
     */
    public function unwrapResponse(Response $response): array
    {
        $payload = $response->json();

        return is_array($payload) ? $payload : [];
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
            message: $mayarMessage ?? 'Mayar API request failed.',
            statusCode: $statusCode,
            mayarMessage: is_string($mayarMessage) ? $mayarMessage : null,
            response: is_array($payload) ? $payload : null,
        );
    }
}
