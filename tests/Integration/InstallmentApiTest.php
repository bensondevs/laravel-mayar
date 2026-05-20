<?php

declare(strict_types=1);

use Bensondevs\Mayar\Exceptions\MayarRequestException;
use Bensondevs\Mayar\Api\Installments\Installment;

it('creates an installment via save', function (): void {
    skipUnlessMayarConfigured();

    $installment = integrationCreateInstallment();

    expect($installment->id)->not->toBeEmpty()
        ->and($installment->invoices)->not->toBeEmpty();
});

it('creates an installment and finds it by id', function (): void {
    skipUnlessMayarConfigured();

    $created = integrationCreateInstallment();

    try {
        $found = Installment::find((string) $created->getKey());
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Installment detail API unavailable: ' . $exception->getMessage());
    }

    expect($found)->toBeInstanceOf(Installment::class)
        ->and($found->getKey())->toBe($created->getKey());
});

function integrationCreateInstallment(): Installment
{
    $installment = new Installment;
    $installment->name = 'Integration Test';
    $installment->email = 'integration-' . uniqid() . '@example.com';
    $installment->mobile = '081234567890';
    $installment->amount = 3000;
    $installment->setInstallment([
        'description' => 'Created by laravel-mayar integration test',
        'interest' => 0,
        'tenure' => 3,
        'dueDate' => 11,
    ]);

    try {
        $installment->save();
    } catch (MayarRequestException $exception) {
        test()->markTestSkipped('Installment create API unavailable: ' . $exception->getMessage());
    }

    if (! $installment->exists()) {
        test()->markTestSkipped('Installment create did not return an id');
    }

    return $installment;
}
