# Laravel Mayar

Laravel integration for the [Mayar Headless API](https://docs.mayar.id/api-reference/introduction).

## Table of contents

- [Requirements](#requirements)
- [Install](#install)
- [Configuration](#configuration)
- [Usage](#usage)
- [Products](#products)
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

// Raw HTTP access (advanced)
$response = Mayar::client()->get(uri: 'customer', query: [
    'page' => 1,
    'pageSize' => 10,
]);

// Switch mode at runtime (updates config)
Mayar::mode(MayarMode::Production);
Mayar::client()->get(uri: 'customer', query: ['page' => 1]);
```

For products, use the [Products module](#products) below.

## Products

Module namespace: `Bensondevs\Mayar\Products\`

API-backed `Product` resources use an Eloquent-*like* calling style (`find`, `paginate`, attribute access) but are not `Illuminate\Database\Eloquent\Model` instances and do not use a database. Pagination returns `Illuminate\Pagination\LengthAwarePaginator`. `Product::findOrFail()` throws `Bensondevs\Mayar\Exceptions\MayarNotFoundException` when the API returns no resource.

Attribute names on resources match the Mayar API JSON exactly (for example `userId`, not `user_id`). `toArray()` and property access use those same keys. Cast keys in `Product::casts()` must use the API property names as well.

Use `Product::paginate(page:, perPage:)` for a simple list, or `->paginate(page:, perPage:)` on a query chain (`search`, `type`). Mayar always returns the full product resource; there is no column selection.

`page` / `perPage` map to Mayar query parameters `page` / `pageSize`. List responses may include `total`, `hasMore`, and `pageCount`.

`{base}` is your configured API root:

- Sandbox: `https://api.mayar.club/hl/v1`
- Production: `https://api.mayar.id/hl/v1`

All requests require: `Authorization: Bearer {MAYAR_API_KEY}`

### Get Product Page

**Package**

```php
use Bensondevs\Mayar\Products\Product;

$paginator = Product::paginate(page: 1, perPage: 10);

foreach ($paginator as $product) {
    echo $product->name;
}
```

**Mayar equivalent**

```http
GET {base}/product?page=1&pageSize=10
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Product Page](https://docs.mayar.id/api-reference/product/productpage.md)

---

### Get Product Page with Type Filter

**Package**

```php
use Bensondevs\Mayar\Products\Enums\ProductType;
use Bensondevs\Mayar\Products\Product;

$paginator = Product::type(ProductType::Ebook)
    ->paginate(page: 1, perPage: 10);

// or string backing value
$paginator = Product::type('ebook')->paginate(page: 1, perPage: 10);
```

`ProductType` cases match the [Mayar `type` parameter](https://docs.mayar.id/api-reference/product/productpagewithfilter#param-type) (`generic_link`, `ebook`, `saas`, …).

**Mayar equivalent**

```http
GET {base}/product/type/{type}?page=1&pageSize=10
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Product Page with Type Filter](https://docs.mayar.id/api-reference/product/productpagewithfilter)

---

### Search Product

**Package**

```php
use Bensondevs\Mayar\Products\Product;

$paginator = Product::search(keyword: 'course')
    ->paginate(page: 1, perPage: 10);
```

**Mayar equivalent**

```http
GET {base}/product?search={keyword}&page=1&pageSize=10
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Search Product](https://docs.mayar.id/api-reference/product/search.md)

---

### Type filter + search

**Package**

```php
use Bensondevs\Mayar\Products\Enums\ProductType;
use Bensondevs\Mayar\Products\Product;

$paginator = Product::type(ProductType::Ebook)
    ->search(keyword: 'journal')
    ->paginate(page: 1, perPage: 10);
```

**Mayar equivalent**

```http
GET {base}/product/type/{type}?search={keyword}&page=1&pageSize=10
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Product Page with Type Filter](https://docs.mayar.id/api-reference/product/productpagewithfilter) · [Search Product](https://docs.mayar.id/api-reference/product/search.md)

---

### Get Detail Product

**Package**

```php
use Bensondevs\Mayar\Products\Product;

$product = Product::find('uuid');

$product = Product::findOrFail('uuid');
```

**Mayar equivalent**

```http
GET {base}/product/{id}
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Detail Product](https://docs.mayar.id/api-reference/product/detail.md)

---

### Close Product

**Package**

```php
$product = Product::findOrFail('uuid');

if ($product->close()) {
    // Mayar messages === "success"
}
```

Returns `bool`. Mayar may respond with HTTP 200 and `messages: "failed"` without raising an exception.

**Mayar equivalent**

```http
GET {base}/product/close/{id}
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Close Product](https://docs.mayar.id/api-reference/product/close.md)

---

### Re-open Product

**Package**

```php
$product = Product::findOrFail('uuid');

if ($product->reopen()) {
    // Mayar messages === "success"
}
```

**Mayar equivalent**

```http
GET {base}/product/open/{id}
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Re-open Product](https://docs.mayar.id/api-reference/product/reopen.md)

---

## Develop and test

```bash
composer install
composer test
```

Tests use `Http::fake()` and do not call the live Mayar API.

## Roadmap

- Customer module (API-backed resources)
- Invoice, payment request, and additional Mayar resources

## License

MIT
