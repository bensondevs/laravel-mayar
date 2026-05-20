<?php

declare(strict_types=1);

use Bensondevs\Mayar\Discounts\Discount;
use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Tests\Feature\Discounts\DiscountFixtures;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;
use Illuminate\Support\Facades\Http;

it('finds a discount by id', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/coupon/disc-123' => Http::response([
            'statusCode' => 200,
            'messages' => 'Success',
            'data' => [
                'id' => 'disc-123',
                'name' => 'Test Discount',
                'discountType' => 'monetary',
            ],
        ]),
    ]);

    $discount = Discount::find('disc-123');

    expect($discount)->toBeInstanceOf(Discount::class)
        ->and($discount->name)->toBe('Test Discount');
});

it('maps the full discount detail response from the api', function (): void {
    $id = DiscountFixtures::discountDetailId();

    Http::fake([
        "https://api.mayar.club/hl/v1/coupon/{$id}" => Http::response(
            body: DiscountFixtures::discountDetailResponse(),
        ),
    ]);

    $discount = Discount::find($id);

    expect($discount)->toBeInstanceOf(Discount::class)
        ->and($discount->id)->toBe($id)
        ->and($discount->name)->toBe('Diskon Murmer')
        ->and($discount->value)->toBe(100000)
        ->and($discount->coupons)->toHaveCount(1)
        ->and($discount->coupons[0]['code'])->toBe('haribaik');
});

it('returns null when discount is not found', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/coupon/missing' => Http::response([
            'statusCode' => HttpStatusCode::NotFound->value,
            'messages' => 'Not Found',
            'data' => [],
        ]),
    ]);

    expect(Discount::find('missing'))->toBeNull();
});

it('find or fail throws when discount is missing', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/coupon/missing' => Http::response([
            'statusCode' => HttpStatusCode::NotFound->value,
            'messages' => 'Not Found',
            'data' => [],
        ]),
    ]);

    Discount::findOrFail('missing');
})->throws(MayarNotFoundException::class);
