# 👤 Customers

Module namespace: `Bensondevs\Mayar\Api\Customers\`

Customers support listing, email-based detail, create, email update, and portal magic-link creation.

## Get Customer Page

```php
use Bensondevs\Mayar\Api\Customers\Customer;

$paginator = Customer::paginate(page: 1, perPage: 10);
```

Returns: `LengthAwarePaginator<Customer>`

```php
foreach ($paginator as $customer) {
    echo $customer->id;
    echo $customer->name;
    echo $customer->email;
}

echo $paginator->total();
echo $paginator->perPage();
echo $paginator->currentPage();
echo $paginator->lastPage();
```

API reference: [Get Customer](https://docs.mayar.id/api-reference/customer/getdetail)

## Search Customer By Email

```php
use Bensondevs\Mayar\Api\Customers\Customer;

$customer = Customer::findByEmail('customer@example.com');
$customer = Customer::findByEmailOrFail('customer@example.com');
```

Returns:
- `Customer::findByEmail(string $email): Customer|null`
- `Customer::findByEmailOrFail(string $email): Customer` (throws when not found)

Common customer attributes:
- `id`, `name`, `email`, `mobile`, `createdAt`, `updatedAt`

```php
$customer = Customer::findByEmailOrFail('customer@example.com');
echo $customer->id;
echo $customer->name;
echo $customer->email;
```

API reference: [Search Customer By Email](https://docs.mayar.id/api-reference/customer/searchcustomerbyemail)

## Create Customer

```php
use Bensondevs\Mayar\Api\Customers\Customer;

$customer = Customer::create([
    'name' => 'Customer name',
    'email' => 'customer@example.com',
    'mobile' => '081234567890',
]);
```

API reference: [Create Customer](https://docs.mayar.id/api-reference/customer/create)

## Update Customer Email

```php
use Bensondevs\Mayar\Api\Customers\Customer;

$success = Customer::updateEmail([
    'fromEmail' => 'old@example.com',
    'toEmail' => 'new@example.com',
]);
```

Returns: `bool` (`true` when email update succeeds).

Failure cases can happen when:
- source customer email is not found
- target email is already used
- API validation/auth fails

```php
try {
    $success = Customer::updateEmail([
        'fromEmail' => 'old@example.com',
        'toEmail' => 'new@example.com',
    ]);

    if (! $success) {
        // Handle API-level rejection
    }
} catch (\Throwable $e) {
    // Handle validation or transport errors
}
```

API reference: [Update Customer Email](https://docs.mayar.id/api-reference/customer/update)

## Create Magic Link (Customer Portal)

```php
use Bensondevs\Mayar\Api\Customers\Customer;

$result = Customer::sendPortalMagicLink('customer@example.com');
```

Returns: array/object result with portal link delivery status.

Failure cases can happen when:
- customer email is not found
- customer cannot receive portal magic link yet
- API validation/auth fails

API reference: [Create Magic Link](https://docs.mayar.id/api-reference/customer/createmagiclink)
