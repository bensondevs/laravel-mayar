<?php

declare(strict_types=1);

use Bensondevs\Mayar\Tests\Feature\Webhooks\WebhookFixtures;
use Bensondevs\Mayar\Webhooks\WebhookHistory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

it('paginates webhook history via the history endpoint', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/webhook/history*' => Http::response(
            body: WebhookFixtures::webhookHistoryListResponse(),
        ),
    ]);

    $paginator = WebhookHistory::paginate(page: 1, perPage: 10);

    $first = $paginator->first();

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginator)->toHaveCount(1)
        ->and($paginator->total())->toBe(770)
        ->and($first)->toBeInstanceOf(WebhookHistory::class)
        ->and($first->id)->toBe('7d567063-ad7f-48d5-9e84-0e41938783a5')
        ->and($first->type)->toBe('payment.received')
        ->and($first->urlDestination)->toBe('https://example.mayar.com')
        ->and($first->payload)->toBe('{"event":"payment.received"}');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/webhook/history?page=1&pageSize=10';
    });
});
