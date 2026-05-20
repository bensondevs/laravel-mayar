<?php

declare(strict_types=1);

use Bensondevs\Mayar\Products\Enums\ProductType;
use Bensondevs\Mayar\Products\Product;
use Bensondevs\Mayar\Tests\Feature\Products\ProductFixtures;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

it('paginates products via the product page endpoint', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/product*' => Http::response(
            body: ProductFixtures::productListResponse(),
        ),
    ]);

    $paginator = Product::paginate(page: 1, perPage: 10);

    $first = $paginator->first();

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginator)->toHaveCount(1)
        ->and($paginator->total())->toBe(1)
        ->and($first)->toBeInstanceOf(Product::class)
        ->and($first->name)->toBe('Invisible Man')
        ->and($first->id)->toBe('fca92d4c-33e8-4334-b2f3-491af6d78a5b')
        ->and($first->type)->toBe(ProductType::Audiobook)
        ->and($first->createdAt)->toBe(1692004154797)
        ->and($first->linkUrl)->toContain('invisible-man');

    Http::assertSent(function ($request): bool {
        return str_starts_with($request->url(), 'https://api.mayar.club/hl/v1/product')
            && str_contains($request->url(), 'page=1')
            && str_contains($request->url(), 'pageSize=10')
            && ! str_contains($request->url(), '/product/type/');
    });
});
