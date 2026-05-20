<?php

declare(strict_types=1);

use Bensondevs\Mayar\Products\Enums\ProductType;
use Bensondevs\Mayar\Products\Product;
use Bensondevs\Mayar\Tests\Feature\Products\ProductFixtures;
use Illuminate\Support\Facades\Http;

it('searches products by keyword', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/product*' => Http::response(
            body: ProductFixtures::productListResponse(),
        ),
    ]);

    $paginator = Product::search(keyword: 'invisible')->paginate(page: 1, perPage: 10);

    expect($paginator)->toHaveCount(1)
        ->and($paginator->first()->name)->toBe('Invisible Man');

    Http::assertSent(function ($request): bool {
        return str_contains($request->url(), 'search=invisible')
            && ! str_contains($request->url(), '/product/type/');
    });
});

it('omits search query param when keyword is empty', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/product*' => Http::response([
            'statusCode' => 200,
            'messages' => 'success',
            'page' => 1,
            'pageSize' => 10,
            'total' => 0,
            'data' => [],
        ]),
    ]);

    Product::search(keyword: '')->paginate(page: 1, perPage: 10);

    Http::assertSent(function ($request): bool {
        return ! str_contains($request->url(), 'search=');
    });
});

it('combines type filter and search', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/product/type/audiobook*' => Http::response(
            body: ProductFixtures::productListResponse(),
        ),
    ]);

    $paginator = Product::type(ProductType::Audiobook)
        ->search(keyword: 'invisible')
        ->paginate(page: 1, perPage: 10);

    expect($paginator)->toHaveCount(1)
        ->and($paginator->first()->type)->toBe(ProductType::Audiobook);

    Http::assertSent(function ($request): bool {
        return str_contains($request->url(), '/product/type/audiobook')
            && str_contains($request->url(), 'search=invisible');
    });
});
