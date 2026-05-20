<?php

declare(strict_types=1);

use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Products\Enums\ProductType;
use Bensondevs\Mayar\Products\Product;
use Bensondevs\Mayar\Tests\Feature\Products\ProductFixtures;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;
use Illuminate\Support\Facades\Http;

it('finds a product by id', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/product/prod-123' => Http::response([
            'statusCode' => 200,
            'messages' => 'success',
            'data' => [
                'id' => 'prod-123',
                'name' => 'Detail Product',
                'type' => 'generic_link',
                'status' => 'active',
            ],
        ]),
    ]);

    $product = Product::find('prod-123');

    expect($product)->toBeInstanceOf(Product::class)
        ->and($product->name)->toBe('Detail Product');
});

it('maps the full product detail response from the api', function (): void {
    $id = ProductFixtures::productDetailId();

    Http::fake([
        "https://api.mayar.club/hl/v1/product/{$id}" => Http::response(
            body: ProductFixtures::productDetailResponse(),
        ),
    ]);

    $product = Product::find($id);

    expect($product)->toBeInstanceOf(Product::class)
        ->and($product->name)->toBe('Pembuatan Jasa Web')
        ->and($product->userId)->toBe('348e083d-315a-4e5c-96b1-5a2a98c48413')
        ->and($product->getAttributes())->toHaveKey('userId')
        ->and($product->getAttributes())->not->toHaveKey('user_id')
        ->and($product->amount)->toBe(100000)
        ->and($product->type)->toBe(ProductType::GenericLink)
        ->and($product->variants)->toHaveCount(2)
        ->and($product->linkUrl)->toContain('pembuatan-jasa-web');
});

it('returns null when product is not found', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/product/missing' => Http::response([
            'statusCode' => HttpStatusCode::NotFound->value,
            'messages' => 'Not Found',
            'data' => [],
        ]),
    ]);

    expect(Product::find('missing'))->toBeNull();
});

it('find or fail throws when product is missing', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/product/missing' => Http::response([
            'statusCode' => HttpStatusCode::NotFound->value,
            'messages' => 'Not Found',
            'data' => [],
        ]),
    ]);

    Product::findOrFail('missing');
})->throws(MayarNotFoundException::class);

it('throws logic exception when create is called on product', function (): void {
    Product::create([]);
})->throws(LogicException::class);
