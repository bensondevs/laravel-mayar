# 👤 Customers

Module namespace: `Bensondevs\Mayar\Customers\`

Customers support listing, email-based detail, create, email update, and portal magic-link creation.

## Get Customer Page

```php
use Bensondevs\Mayar\Customers\Customer;

$paginator = Customer::paginate(page: 1, perPage: 10);
```

API reference: [Get Customer](https://docs.mayar.id/api-reference/customer/getdetail)

## Search Customer By Email

```php
use Bensondevs\Mayar\Customers\Customer;

$customer = Customer::findByEmail('customer@example.com');
$customer = Customer::findByEmailOrFail('customer@example.com');
```

API reference: [Search Customer By Email](https://docs.mayar.id/api-reference/customer/searchcustomerbyemail)

## Create Customer

```php
use Bensondevs\Mayar\Customers\Customer;

$customer = Customer::create([
    'name' => 'Customer name',
    'email' => 'customer@example.com',
    'mobile' => '081234567890',
]);
```

API reference: [Create Customer](https://docs.mayar.id/api-reference/customer/create)

## Update Customer Email

```php
use Bensondevs\Mayar\Customers\Customer;

$success = Customer::updateEmail([
    'fromEmail' => 'old@example.com',
    'toEmail' => 'new@example.com',
]);
```

API reference: [Update Customer Email](https://docs.mayar.id/api-reference/customer/update)

## Create Magic Link (Customer Portal)

```php
use Bensondevs\Mayar\Customers\Customer;

$result = Customer::sendPortalMagicLink('customer@example.com');
```

API reference: [Create Magic Link](https://docs.mayar.id/api-reference/customer/createmagiclink)
