<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\Installments\Installment;
use Bensondevs\Mayar\Api\Installments\InstallmentTerms;
use Bensondevs\Mayar\Tests\Feature\Api\Installments\InstallmentFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('creates an installment via save', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/installment/create' => Http::response(
            body: InstallmentFixtures::installmentCreateResponse(),
        ),
    ]);

    $installment = new Installment;
    $installment->name = 'Azumii';
    $installment->email = 'user@example.com';
    $installment->mobile = '089961367511';
    $installment->amount = 1500000;
    $installment->setInstallment([
        'description' => 'Cicil Produk Kelas Online 3 Bulan',
        'interest' => 0,
        'tenure' => 3,
        'dueDate' => 11,
    ]);

    $installment->save();

    expect($installment->id)->toBe('ba82c2dd-06c1-4b6c-bc59-a9c00801c842')
        ->and($installment->tenure)->toBe(3)
        ->and($installment->invoices)->toHaveCount(1);

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/installment/create') {
            return false;
        }

        $body = $request->data();

        return $body['name'] === 'Azumii'
            && $body['amount'] === 1500000
            && $body['installment']['tenure'] === 3
            && $body['installment']['dueDate'] === 11;
    });
});

it('creates an installment via create', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/installment/create' => Http::response(
            body: InstallmentFixtures::installmentCreateResponse(),
        ),
    ]);

    $installment = Installment::create([
        'name' => 'Azumii',
        'email' => 'user@example.com',
        'mobile' => '089961367511',
        'amount' => 1500000,
        'installment' => [
            'description' => 'Cicil Produk Kelas Online 3 Bulan',
            'interest' => 0,
            'tenure' => 3,
            'dueDate' => 11,
        ],
    ]);

    expect($installment->id)->toBe('ba82c2dd-06c1-4b6c-bc59-a9c00801c842')
        ->and($installment->exists())->toBeTrue();

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/installment/create'
            && $request->data()['installment']['tenure'] === 3;
    });
});

it('throws logic exception when create is called with an id', function (): void {
    Installment::create(['id' => InstallmentFixtures::installmentCreateId()]);
})->throws(LogicException::class);

it('creates an installment with constructor and InstallmentTerms', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/installment/create' => Http::response(
            body: InstallmentFixtures::installmentCreateResponse(),
        ),
    ]);

    $installment = new Installment([
        'name' => 'Azumii',
        'email' => 'user@example.com',
        'mobile' => '089961367511',
        'amount' => 1500000,
    ]);

    $installment->setInstallment(new InstallmentTerms(
        description: 'Cicil Produk Kelas Online 3 Bulan',
        interest: 0,
        tenure: 3,
        dueDate: 11,
    ));

    $installment->save();

    expect($installment->exists())->toBeTrue();
});

it('throws validation exception when create payload is missing installment terms', function (): void {
    $installment = new Installment([
        'name' => 'Azumii',
        'email' => 'user@example.com',
        'mobile' => '089961367511',
        'amount' => 1500000,
    ]);

    $installment->save();
})->throws(ValidationException::class);

it('throws when save is called on an existing installment', function (): void {
    $installment = Installment::fromMayar([
        'id' => InstallmentFixtures::installmentCreateId(),
    ]);

    $installment->save();
})->throws(LogicException::class);
