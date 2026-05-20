<?php

declare(strict_types=1);

use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Api\Installments\Installment;
use Bensondevs\Mayar\Tests\Feature\Installments\InstallmentFixtures;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;
use Illuminate\Support\Facades\Http;

it('finds an installment by id', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/installment/inst-123' => Http::response([
            'statusCode' => 200,
            'messages' => 'Success',
            'data' => [
                'id' => 'inst-123',
                'tenure' => 3,
                'amount' => 1500000,
            ],
        ]),
    ]);

    $installment = Installment::find('inst-123');

    expect($installment)->toBeInstanceOf(Installment::class)
        ->and($installment->tenure)->toBe(3);
});

it('maps the full installment detail response from the api', function (): void {
    $id = InstallmentFixtures::installmentDetailId();

    Http::fake([
        "https://api.mayar.club/hl/v1/installment/{$id}" => Http::response(
            body: InstallmentFixtures::installmentDetailResponse(),
        ),
    ]);

    $installment = Installment::find($id);

    expect($installment)->toBeInstanceOf(Installment::class)
        ->and($installment->id)->toBe($id)
        ->and($installment->tenure)->toBe(3)
        ->and($installment->period)->toBe('MONTHLY')
        ->and($installment->paymentLink['name'])->toBe('Azumii')
        ->and($installment->invoices)->toHaveCount(1);
});

it('returns null when installment is not found', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/installment/missing' => Http::response([
            'statusCode' => HttpStatusCode::NotFound->value,
            'messages' => 'Not Found',
            'data' => [],
        ]),
    ]);

    expect(Installment::find('missing'))->toBeNull();
});

it('find or fail throws when installment is missing', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/installment/missing' => Http::response([
            'statusCode' => HttpStatusCode::NotFound->value,
            'messages' => 'Not Found',
            'data' => [],
        ]),
    ]);

    Installment::findOrFail('missing');
})->throws(MayarNotFoundException::class);
