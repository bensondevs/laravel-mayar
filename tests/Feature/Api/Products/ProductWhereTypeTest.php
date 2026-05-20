<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\Products\Enums\ProductType;
use Bensondevs\Mayar\Api\Products\Product;
use Bensondevs\Mayar\Tests\Feature\Api\Products\ProductFixtures;
use Illuminate\Support\Facades\Http;

it('paginates products filtered by type', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/product/type/audiobook*' => Http::response(
            body: ProductFixtures::productListResponse(),
        ),
    ]);

    $paginator = Product::type(ProductType::Audiobook)->paginate(page: 1, perPage: 10);

    expect($paginator)->toHaveCount(1)
        ->and($paginator->first()->name)->toBe('Invisible Man')
        ->and($paginator->first()->type)->toBe(ProductType::Audiobook);

    Http::assertSent(fn ($request): bool => str_contains($request->url(), '/product/type/audiobook'));
});

it('accepts type as a string', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/product/type/audiobook*' => Http::response(
            body: ProductFixtures::productListResponse(),
        ),
    ]);

    $paginator = Product::type('audiobook')->paginate(page: 1, perPage: 10);

    expect($paginator->first()->type)->toBe(ProductType::Audiobook);

    Http::assertSent(fn ($request): bool => str_contains($request->url(), '/product/type/audiobook'));
});
