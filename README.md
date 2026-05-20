# Laravel Mayar

Laravel integration for the [Mayar Headless API](https://docs.mayar.id/api-reference/introduction).

## ✨ Highlights

- Laravel-friendly API client with Eloquent-like resources.
- Supports products, invoices, payment requests, installments, discounts, customers, transactions, webhooks, and reviews.
- Includes SaaS/license and credit-based workflows.
- Designed for sandbox and production modes.

## 📋 Table of Contents

- [Requirements](#requirements)
- [Install](#install)
- [Configuration](#configuration)
- [Quick Usage](#quick-usage)
- [Features](#features)
- [Full Documentation](#full-documentation)
- [Develop and Test](#develop-and-test)
- [License](#license)

<a id="requirements"></a>
## ✅ Requirements

| Requirement | Notes |
| --- | --- |
| PHP | 8.3+ |
| Laravel | 10–13 with matching `illuminate/*` components |
| Mayar account | [Production](https://web.mayar.id/api-keys) or [sandbox](https://web.mayar.club/api-keys) API key |

<a id="install"></a>
## 📦 Install

```bash
composer require bensondevs/laravel-mayar
```

Publish configuration (optional):

```bash
php artisan vendor:publish --tag=mayar-config
```

<a id="configuration"></a>
## ⚙️ Configuration

Add to your `.env`:

```dotenv
MAYAR_API_KEY=your-api-key
MAYAR_MODE=sandbox
```

| Variable | Description |
| --- | --- |
| `MAYAR_API_KEY` | API key from the Mayar dashboard (sandbox or production portal) |
| `MAYAR_MODE` | `sandbox` (default) or `production` |

Use a key issued from the portal that matches your mode.

<a id="quick-usage"></a>
## 🚀 Quick Usage

```php
use Bensondevs\Mayar\Mayar;
use Bensondevs\Mayar\Enums\MayarMode;
use Bensondevs\Mayar\Api\Products\Product;

// Raw HTTP access (advanced)
$response = Mayar::client()->get(uri: 'customer', query: [
    'page' => 1,
    'pageSize' => 10,
]);

// Switch mode at runtime
Mayar::mode(MayarMode::Production);

// Eloquent-like resource usage
$products = Product::search(keyword: 'course')->paginate(page: 1, perPage: 10);
```

```php
use Bensondevs\Mayar\Api\Invoices\Invoice;

$invoice = Invoice::create([
    'name' => 'Customer name',
    'email' => 'customer@example.com',
    'mobile' => '081234567890',
    'description' => 'Order notes',
    'items' => [
        ['quantity' => 1, 'rate' => 10000, 'description' => 'Item description'],
    ],
]);
```

<a id="features"></a>
## 🧩 Features

- `Products`: list/search/detail/close/re-open.
- `Software License Codes`: license verification for software products.
- `SaaS Membership`: verify, activate, deactivate licenses.
- `Credit Membership`: balance/history/spend/add-credit/register customer.
- `Credit Based Product`: credit usage workflows and immutable checkout.
- `Invoices`: create/edit/list/filter/detail/close/re-open.
- `Payment Requests`: create/edit/list/filter/detail/close/re-open.
- `Installments`: create and detail.
- `Discounts`: create/validate/detail.
- `Customers`: list, search by email, create, update email, portal link.
- `Transactions`: balance, unpaid list, daily stats, dynamic QR.
- `Webhooks`: history, register, test, retry.
- `Webhook Receiver`: inbound webhook controller with event-driven listener handling and DTO payload access.
- `Reviews`: paginated list.

<a id="full-documentation"></a>
## 📘 Full Documentation

Complete feature docs are available in dedicated files under [`docs/`](./docs/README.md):

- [🛍️ Products](./docs/products.md)
- [🔑 Software License Codes](./docs/software-license-codes.md)
- [☁️ SaaS Membership](./docs/saas-membership.md)
- [💳 Credit Membership](./docs/credit-membership.md)
- [⚡ Credit Based Product](./docs/credit-based-product.md)
- [🧾 Invoices](./docs/invoices.md)
- [💸 Payment Requests](./docs/payment-requests.md)
- [🧩 Installments](./docs/installments.md)
- [🏷️ Discounts](./docs/discounts.md)
- [👤 Customers](./docs/customers.md)
- [📊 Transactions](./docs/transactions.md)
- [🪝 Webhooks](./docs/webhooks.md)

Webhook docs now include a complete inbound receiver guide (optional package route or manual route registration), event-listener wiring for all documented Mayar webhook event types, and DTO-based payload handling.
- [⭐ Reviews](./docs/reviews.md)

<a id="develop-and-test"></a>
## 🧪 Develop and Test

```bash
composer install
composer test
```

Tests use `Http::fake()` and do not call the live Mayar API.

<a id="license"></a>
## 📄 License

MIT
