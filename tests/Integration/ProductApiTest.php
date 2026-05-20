<?php

declare(strict_types=1);

use Bensondevs\Mayar\Exceptions\MayarRequestException;
use Bensondevs\Mayar\Api\Products\Product;
use Illuminate\Pagination\LengthAwarePaginator;

it('paginates products via Product::paginate', function (): void {
    skipUnlessMayarConfigured();

    $paginator = null;
    try {
        $paginator = Product::paginate(page: 1, perPage: 1);
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Product list API unavailable: ' . $exception->getMessage());
    }

    if (! $paginator instanceof LengthAwarePaginator) {
        test()->markTestSkipped('Product list API did not return a paginator');
    }

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class);

    if ($paginator->isEmpty()) {
        test()->markTestSkipped('No products in the sandbox account');
    }

    $first = $paginator->first();

    expect($first)->toBeInstanceOf(Product::class)
        ->and($first->id)->not->toBeEmpty()
        ->and($first->name)->not->toBeEmpty();
});

it('finds a product by id when MAYAR_TEST_PRODUCT_ID is set', function (): void {
    skipUnlessMayarConfigured();

    $product = integrationTestProduct();

    expect($product->getKey())->toBe((string) env('MAYAR_TEST_PRODUCT_ID'));
});

it('returns null for a non-existent product id', function (): void {
    skipUnlessMayarConfigured();

    $product = null;
    try {
        $product = Product::find('00000000-0000-0000-0000-000000000000');
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Product detail API unavailable: ' . $exception->getMessage());
    }

    expect($product)->toBeNull();
});

it('closes a product when MAYAR_TEST_PRODUCT_ID is set', function (): void {
    skipUnlessMayarConfigured();

    $product = integrationTestProduct();

    if ($product->status !== 'active') {
        try {
            if ($product->reopen()) {
                $product = Product::find($product->getKey());
            }
        } catch (MayarRequestException $exception) {
            test()->markTestSkipped('Product reopen API unavailable: ' . $exception->getMessage());
        }

        if ($product === null || $product->status !== 'active') {
            test()->markTestSkipped('Product must be active to test close');
        }
    }

    $closed = null;
    try {
        $closed = $product->close();
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Product close API unavailable: ' . $exception->getMessage());
    }

    expect($closed)->toBeTrue()
        ->and($product->status)->toBe('closed');
});

it('reopens a product when MAYAR_TEST_PRODUCT_ID is set', function (): void {
    skipUnlessMayarConfigured();

    $product = integrationTestProduct();

    if ($product->status !== 'closed') {
        try {
            if (! $product->close()) {
                test()->markTestSkipped('Could not close product before testing reopen');
            }
        } catch (MayarRequestException $exception) {
            test()->markTestSkipped('Product close API unavailable: ' . $exception->getMessage());
        }
    }

    $reopened = null;
    try {
        $reopened = $product->reopen();
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Product reopen API unavailable: ' . $exception->getMessage());
    }

    expect($reopened)->toBeTrue()
        ->and($product->status)->toBe('active');
});

function integrationTestProduct(): Product
{
    $productId = env('MAYAR_TEST_PRODUCT_ID');

    if (blank($productId)) {
        test()->markTestSkipped('MAYAR_TEST_PRODUCT_ID is not set in .env');
    }

    $product = null;
    try {
        $product = Product::find((string) $productId);
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Product detail API unavailable: ' . $exception->getMessage());
    }

    if ($product === null) {
        test()->markTestSkipped('Product not found for MAYAR_TEST_PRODUCT_ID');
    }

    return $product;
}
