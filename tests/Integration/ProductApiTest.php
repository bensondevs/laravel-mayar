<?php

declare(strict_types=1);

use Bensondevs\Mayar\Products\Product;
use Illuminate\Pagination\LengthAwarePaginator;

it('paginates products via Product::paginate', function (): void {
    $paginator = Product::paginate(page: 1, perPage: 1);

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginator)->not->toBeEmpty();

    $first = $paginator->first();

    expect($first)->toBeInstanceOf(Product::class)
        ->and($first->id)->not->toBeEmpty()
        ->and($first->name)->not->toBeEmpty();
});

it('finds a product by id when MAYAR_TEST_PRODUCT_ID is set', function (): void {
    $product = integrationTestProduct();

    expect($product->getKey())->toBe((string) config('mayar.test_product_id'));
});

it('returns null for a non-existent product id', function (): void {
    expect(Product::find('00000000-0000-0000-0000-000000000000'))->toBeNull();
});

it('closes a product when MAYAR_TEST_PRODUCT_ID is set', function (): void {
    $product = integrationTestProduct();

    if ($product->status !== 'active') {
        if ($product->reopen()) {
            $product = Product::find($product->getKey());
        }

        if ($product === null || $product->status !== 'active') {
            test()->markTestSkipped('Product must be active to test close');
        }
    }

    expect($product->close())->toBeTrue()
        ->and($product->status)->toBe('closed');
});

it('reopens a product when MAYAR_TEST_PRODUCT_ID is set', function (): void {
    $product = integrationTestProduct();

    if ($product->status !== 'closed') {
        if (! $product->close()) {
            test()->markTestSkipped('Could not close product before testing reopen');
        }
    }

    expect($product->reopen())->toBeTrue()
        ->and($product->status)->toBe('active');
});

function integrationTestProduct(): Product
{
    $productId = config('mayar.test_product_id');

    if (blank($productId)) {
        test()->markTestSkipped('MAYAR_TEST_PRODUCT_ID is not set in .env');
    }

    $product = Product::find((string) $productId);

    if ($product === null) {
        test()->markTestSkipped('Product not found for MAYAR_TEST_PRODUCT_ID');
    }

    return $product;
}
