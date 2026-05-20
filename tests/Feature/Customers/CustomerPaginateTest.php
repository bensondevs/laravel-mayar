<?php

declare(strict_types=1);

use Bensondevs\Mayar\Customers\Customer;
use Bensondevs\Mayar\Tests\Feature\Customers\CustomerFixtures;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

it('paginates customers via the customer list endpoint', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/customer*' => Http::response(
            body: CustomerFixtures::customerListResponse(),
        ),
    ]);

    $paginator = Customer::paginate(page: 1, perPage: 10);

    $first = $paginator->first();

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginator)->toHaveCount(1)
        ->and($paginator->total())->toBe(1176)
        ->and($first)->toBeInstanceOf(Customer::class)
        ->and($first->name)->toBe('Kirana')
        ->and($first->id)->toBe('0072cf8e-a8f1-4ce9-9dcd-3683f085ebf1')
        ->and($first->email)->toBe('ghasyiyahps@gmail.com')
        ->and($first->createdAt)->toBe(1735787039376);

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/customer?page=1&pageSize=10';
    });
});
