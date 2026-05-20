<?php

declare(strict_types=1);

use Bensondevs\Mayar\Reviews\Review;
use Bensondevs\Mayar\Tests\Feature\Reviews\ReviewFixtures;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

it('paginates reviews via the review list endpoint', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/reviews*' => Http::response(
            body: ReviewFixtures::reviewListResponse(),
        ),
    ]);

    $paginator = Review::paginate(page: 1, perPage: 10);

    $first = $paginator->first();

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginator)->toHaveCount(1)
        ->and($paginator->total())->toBe(1)
        ->and($first)->toBeInstanceOf(Review::class)
        ->and($first->id)->toBe('11f4d767-4d88-41ff-8777-06564d906fe7')
        ->and($first->message)->toBe('Bagus sekali, takde minusnyo')
        ->and($first->rating)->toBe(5)
        ->and($first->createdAt)->toBe(1778223034383)
        ->and($first->customer['name'])->toBe('Test Mayar')
        ->and($first->paymentLink['type'])->toBe('course');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/reviews?page=1&pageSize=10';
    });
});
