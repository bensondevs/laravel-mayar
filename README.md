# Laravel Mayar

Laravel integration for the [Mayar Headless API](https://docs.mayar.id/api-reference/introduction).

## Table of contents

- [Requirements](#requirements)
- [Install](#install)
- [Configuration](#configuration)
- [Usage](#usage)
- [Develop and test](#develop-and-test)
- [Roadmap](#roadmap)

## Requirements

| Requirement | Notes |
| --- | --- |
| PHP | 8.3+ |
| Laravel | 10–13 with matching `illuminate/*` components |
| Mayar account | [Production](https://web.mayar.id/api-keys) or [sandbox](https://web.mayar.club/api-keys) API key |

## Install

```bash
composer require bensondevs/laravel-mayar
```

Publish configuration (optional):

```bash
php artisan vendor:publish --tag=mayar-config
```

## Configuration

Add to your `.env`:

```dotenv
MAYAR_API_KEY=your-api-key
MAYAR_MODE=sandbox
```

| Variable | Description |
| --- | --- |
| `MAYAR_API_KEY` | API key from the Mayar dashboard (sandbox or production portal) |
| `MAYAR_MODE` | `sandbox` (default) or `production` — selects the API base URL |

Base URLs are defined on `MayarMode` in the package (not in config):

- **sandbox** → `https://api.mayar.club/hl/v1`
- **production** → `https://api.mayar.id/hl/v1`

Use a key issued from the portal that matches your mode.

## Usage

```php
use Bensondevs\Mayar\Mayar;
use Bensondevs\Mayar\Enums\MayarMode;

// List customers (uses MAYAR_MODE from config)
$response = Mayar::client()->get('customer', [
    'page' => 1,
    'pageSize' => 10,
]);

$customers = $response['data'];

// Convenience helpers
$client = Mayar::client();
$client->getCustomers(['page' => 1, 'pageSize' => 10]);
$client->getCustomerByEmail('user@example.com');
$client->getProducts(['page' => 1]);
$client->getProduct('product-uuid');

// Switch mode at runtime (updates config)
Mayar::mode(MayarMode::Production);
Mayar::client()->get('product', ['page' => 1]);
```

## Develop and test

```bash
composer install
composer test
```

Tests use `Http::fake()` and do not call the live Mayar API.

## Roadmap

- API-backed Eloquent models for Mayar resources (Customer, Product, Invoice, and more)
- Webhook handling

## License

MIT
