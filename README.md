# Laravel Mayar

Laravel integration for the [Mayar Headless API](https://docs.mayar.id/api-reference/introduction).

## Table of contents

- [Requirements](#requirements)
- [Install](#install)
- [Configuration](#configuration)
- [Usage](#usage)
- [Products](#products)
- [Software license codes](#software-license-codes)
- [SaaS Membership](#saas-membership)
- [Invoices](#invoices)
- [Payment Requests](#payment-requests)
- [Installments](#installments)
- [Discounts](#discounts)
- [Customers](#customers)
- [Transactions](#transactions)
- [Webhooks](#webhooks)
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

For products, software license codes, SaaS membership, invoices, payment requests, installments, discounts, customers, transactions, and webhooks, use the [Products](#products), [Software license codes](#software-license-codes), [SaaS Membership](#saas-membership), [Invoices](#invoices), [Payment Requests](#payment-requests), [Installments](#installments), [Discounts](#discounts), [Customers](#customers), [Transactions](#transactions), and [Webhooks](#webhooks) modules below.

## Products

Module namespace: `Bensondevs\Mayar\Products\`

API-backed `Product` resources use an Eloquent-*like* calling style (`find`, `paginate`, attribute access) but are not `Illuminate\Database\Eloquent\Model` instances and do not use a database. Products are read-only in this package (no `save()` or `Product::create()`). Pagination returns `Illuminate\Pagination\LengthAwarePaginator`. `Product::findOrFail()` throws `Bensondevs\Mayar\Exceptions\MayarNotFoundException` when the API returns no resource.

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

## Software license codes

Module namespace: `Bensondevs\Mayar\SoftwareLicenseCodes\`

Verify license codes for software license products via the Mayar **software** API (`/software/v1`), separate from the headless API used for products and payments. `SoftwareLicenseCode::verify()` returns `LicenseVerificationResult` with `isLicenseActive` and optional nested `licenseCode` details (plain array, matching API field names).

`{softwareBase}` is the software API root:

- Sandbox: `https://api.mayar.club/software/v1`
- Production: `https://api.mayar.id/software/v1`

All requests require `Authorization: Bearer {MAYAR_API_KEY}`.

### Verify license

**Package**

```php
use Bensondevs\Mayar\SoftwareLicenseCodes\SoftwareLicenseCode;

$result = SoftwareLicenseCode::verify(
    licenseCode: 'YOUR-LICENSE-CODE',
    productId: 'YOUR-PRODUCT-ID',
);

if ($result->isLicenseActive) {
    echo $result->licenseCode['status'];
}
```

**Mayar equivalent**

```http
POST {softwareBase}/license/verify
Authorization: Bearer {MAYAR_API_KEY}
Content-Type: application/json

{"licenseCode":"YOUR-LICENSE-CODE","productId":"YOUR-PRODUCT-ID"}
```

**API reference:** [Verify License](https://docs.mayar.id/api-reference/licensecode/verifylicense)

---

## SaaS Membership

Module namespace: `Bensondevs\Mayar\SaaSMembership\`

Manage SaaS membership licenses via the Mayar **SaaS** API (`/saas/v1`). `SaaSMembership::verify()` returns `SaaSLicenseVerificationResult`, while `SaaSMembership::activate()` and `SaaSMembership::deactivate()` return `bool` based on the API envelope.

`{saasBase}` is the SaaS API root:

- Sandbox: `https://api.mayar.club/saas/v1`
- Production: `https://api.mayar.id/saas/v1`

All requests require `Authorization: Bearer {MAYAR_API_KEY}`.

### Verify license SaaS subscription

**Package**

```php
use Bensondevs\Mayar\SaaSMembership\SaaSMembership;

$result = SaaSMembership::verify(
    licenseCode: 'YOUR-LICENSE-CODE',
    productId: 'YOUR-PRODUCT-ID',
);

if ($result->isLicenseActive) {
    echo $result->licenseCode['membershipTierName'];
}
```

**Mayar equivalent**

```http
POST {saasBase}/license/verify
Authorization: Bearer {MAYAR_API_KEY}
Content-Type: application/json

{"licenseCode":"YOUR-LICENSE-CODE","productId":"YOUR-PRODUCT-ID"}
```

**API reference:** [Verify License SaaS Subscription](https://docs.mayar.id/api-reference/saas/verify)

---

### Activate license

**Package**

```php
use Bensondevs\Mayar\SaaSMembership\SaaSMembership;

$success = SaaSMembership::activate(
    licenseCode: 'YOUR-LICENSE-CODE',
    productId: 'YOUR-PRODUCT-ID',
);
```

**Mayar equivalent**

```http
POST {saasBase}/license/activate
Authorization: Bearer {MAYAR_API_KEY}
Content-Type: application/json

{"licenseCode":"YOUR-LICENSE-CODE","productId":"YOUR-PRODUCT-ID"}
```

**API reference:** [Activate License](https://docs.mayar.id/api-reference/saas/activate)

---

### Deactivate license

**Package**

```php
use Bensondevs\Mayar\SaaSMembership\SaaSMembership;

$success = SaaSMembership::deactivate(
    licenseCode: 'YOUR-LICENSE-CODE',
    productId: 'YOUR-PRODUCT-ID',
);
```

**Mayar equivalent**

```http
POST {saasBase}/license/deactivate
Authorization: Bearer {MAYAR_API_KEY}
Content-Type: application/json

{"licenseCode":"YOUR-LICENSE-CODE","productId":"YOUR-PRODUCT-ID"}
```

**API reference:** [Deactivate License](https://docs.mayar.id/api-reference/saas/deactivate)

---

## Invoices

Module namespace: `Bensondevs\Mayar\Invoices\`

API-backed `Invoice` resources use an Eloquent-*like* calling style (`new`, attribute access, `save`, `find`, `paginate`) but are not database models. Create and edit payloads are validated with Laravel's validator before any HTTP request. `Invoice::findOrFail()` throws `Bensondevs\Mayar\Exceptions\MayarNotFoundException` when the API returns no resource.

Line items are managed with `addItem()` / `setItems()` using `InvoiceItem` DTOs or arrays (`quantity`, `rate`, `description`).

`{base}` is your configured API root (sandbox or production). All requests require `Authorization: Bearer {MAYAR_API_KEY}`.

### Create Invoice

**Package**

```php
use Bensondevs\Mayar\Invoices\Invoice;

$invoice = new Invoice;
$invoice->name = 'Customer name';
$invoice->email = 'customer@example.com';
$invoice->mobile = '081234567890';
$invoice->redirectUrl = 'https://example.com/thanks';
$invoice->description = 'Order notes';
$invoice->expiredAt = '2026-04-19T16:43:23.000Z';
$invoice->extraData = [
    'noCustomer' => 'ref-123',
    'idProd' => 'prod-456',
];
$invoice->addItem([
    'quantity' => 1,
    'rate' => 10000,
    'description' => 'Item description',
]);
$invoice->save();

// Static shorthand (equivalent to new Invoice($attrs)->save())
$invoice = Invoice::create([
    'name' => 'Customer name',
    'email' => 'customer@example.com',
    'mobile' => '081234567890',
    'redirectUrl' => 'https://example.com/thanks',
    'description' => 'Order notes',
    'expiredAt' => '2026-04-19T16:43:23.000Z',
    'extraData' => [
        'noCustomer' => 'ref-123',
        'idProd' => 'prod-456',
    ],
    'items' => [
        ['quantity' => 1, 'rate' => 10000, 'description' => 'Item description'],
    ],
]);
```

**Mayar equivalent**

```http
POST {base}/invoice/create
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Create Invoice](https://docs.mayar.id/api-reference/invoice/create)

---

### Edit Invoice

**Package**

```php
use Bensondevs\Mayar\Invoices\Invoice;

// Instance
$invoice = Invoice::findOrFail('uuid');
$invoice->description = 'Updated description';
$invoice->save();

// Static (validated before request)
$invoice = Invoice::update([
    'id' => 'uuid',
    'description' => 'Updated description',
    'items' => [
        ['quantity' => 2, 'rate' => 55000, 'description' => 'Updated item'],
    ],
]);
```

**Mayar equivalent**

```http
POST {base}/invoice/edit
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Edit Invoice](https://docs.mayar.id/api-reference/invoice/edit)

---

### Get List Invoice

**Package**

```php
use Bensondevs\Mayar\Invoices\Invoice;

$paginator = Invoice::paginate(page: 1, perPage: 10);
```

**Mayar equivalent**

```http
GET {base}/invoice?page=1&pageSize=10
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get List Invoice](https://docs.mayar.id/api-reference/invoice)

---

### Get Sort / Filter Invoice

**Package**

```php
use Bensondevs\Mayar\Invoices\Enums\InvoiceSort;
use Bensondevs\Mayar\Invoices\Invoice;

$paginator = Invoice::sort(InvoiceSort::Closed)->paginate(page: 1, perPage: 10);

// or string
$paginator = Invoice::sort('closed')->paginate(page: 1, perPage: 10);
```

`InvoiceSort` cases: `active`, `paid`, `closed` (Mayar filter examples use `sort=closed`).

**Mayar equivalent**

```http
GET {base}/invoice?sort=closed&page=1&pageSize=10
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Sort / Filter Invoice](https://docs.mayar.id/api-reference/invoice/filter)

---

### Get Detail Invoice

**Package**

```php
use Bensondevs\Mayar\Invoices\Invoice;

$invoice = Invoice::find('uuid');
$invoice = Invoice::findOrFail('uuid');
```

**Mayar equivalent**

```http
GET {base}/invoice/{id}
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Detail / Invoice Status](https://docs.mayar.id/api-reference/invoice/detail)

---

### Close Invoice

**Package**

```php
$invoice = Invoice::findOrFail('uuid');

if ($invoice->close()) {
    // Mayar messages === "success"
}
```

**Mayar equivalent**

```http
GET {base}/invoice/close/{id}
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Close Invoice](https://docs.mayar.id/api-reference/invoice/close)

---

### Re-open Invoice

**Package**

```php
$invoice = Invoice::findOrFail('uuid');

if ($invoice->open()) {
    // Mayar messages === "success"
}
```

**Mayar equivalent**

```http
GET {base}/invoice/open/{id}
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Re-open Invoice](https://docs.mayar.id/api-reference/invoice/reopen)

---

## Payment Requests

Module namespace: `Bensondevs\Mayar\PaymentRequests\`

API-backed `PaymentRequest` resources use an Eloquent-*like* calling style (`new`, attribute access, `save`, `find`, `paginate`) but are not database models. Create and edit payloads are validated before any HTTP request; edit requires all fields per the Mayar API. `PaymentRequest::findOrFail()` throws `Bensondevs\Mayar\Exceptions\MayarNotFoundException` when the API returns no resource.

`{base}` is your configured API root (sandbox or production). All requests require `Authorization: Bearer {MAYAR_API_KEY}`.

### Create Single Payment Request

**Package**

```php
use Bensondevs\Mayar\PaymentRequests\PaymentRequest;

$paymentRequest = new PaymentRequest;
$paymentRequest->name = 'Customer name';
$paymentRequest->email = 'customer@example.com';
$paymentRequest->amount = 170000;
$paymentRequest->mobile = '081234567890';
$paymentRequest->redirectUrl = 'https://example.com/thanks';
$paymentRequest->description = 'Payment description';
$paymentRequest->expiredAt = '2025-12-29T09:41:09.401Z';
$paymentRequest->save();

// Static shorthand (equivalent to new PaymentRequest($attrs)->save())
$paymentRequest = PaymentRequest::create([
    'name' => 'Customer name',
    'email' => 'customer@example.com',
    'amount' => 170000,
    'mobile' => '081234567890',
    'redirectUrl' => 'https://example.com/thanks',
    'description' => 'Payment description',
    'expiredAt' => '2025-12-29T09:41:09.401Z',
]);
```

**Mayar equivalent**

```http
POST {base}/payment/create
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Create Single Payment Request](https://docs.mayar.id/api-reference/reqpayment/create)

---

### Edit Single Payment Request

**Package**

```php
use Bensondevs\Mayar\PaymentRequests\PaymentRequest;

// Instance (all required fields must be on the model)
$paymentRequest = PaymentRequest::findOrFail('uuid');
$paymentRequest->description = 'Updated description';
$paymentRequest->save();

// Static (validated before request; all fields required)
$paymentRequest = PaymentRequest::update([
    'id' => 'uuid',
    'name' => 'Customer name',
    'email' => 'customer@example.com',
    'amount' => 100000,
    'mobile' => '081234567890',
    'redirectUrl' => 'https://example.com/thanks',
    'description' => 'Updated description',
    'expiredAt' => '2025-12-29T09:41:09.401Z',
]);
```

**Mayar equivalent**

```http
POST {base}/payment/edit
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Edit Single Payment Request](https://docs.mayar.id/api-reference/reqpayment/edit)

---

### Get List Single Payment Request

**Package**

```php
use Bensondevs\Mayar\PaymentRequests\PaymentRequest;

$paginator = PaymentRequest::paginate(page: 1, perPage: 10);
```

**Mayar equivalent**

```http
GET {base}/payment?page=1&pageSize=10
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get List Single Payment Request](https://docs.mayar.id/api-reference/reqpayment)

---

### Get Sort / Filter Single Payment Request

**Package**

```php
use Bensondevs\Mayar\PaymentRequests\Enums\PaymentRequestStatus;
use Bensondevs\Mayar\PaymentRequests\PaymentRequest;

$paginator = PaymentRequest::status(PaymentRequestStatus::Paid)->paginate(page: 1, perPage: 10);

// or string
$paginator = PaymentRequest::status('paid')->paginate(page: 1, perPage: 10);
```

`PaymentRequestStatus` cases: `active`, `paid`, `closed`.

**Mayar equivalent**

```http
GET {base}/payment?status=paid&page=1&pageSize=10
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Sort / Filter Single Payment Request](https://docs.mayar.id/api-reference/reqpayment/filter)

---

### Get Detail Single Payment Request

**Package**

```php
use Bensondevs\Mayar\PaymentRequests\PaymentRequest;

$paymentRequest = PaymentRequest::find('uuid');
$paymentRequest = PaymentRequest::findOrFail('uuid');
```

**Mayar equivalent**

```http
GET {base}/payment/{id}
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Detail Single Payment Request](https://docs.mayar.id/api-reference/reqpayment/detail)

---

### Close Single Payment Request

**Package**

```php
$paymentRequest = PaymentRequest::findOrFail('uuid');

if ($paymentRequest->close()) {
    // Mayar messages === "success"
}
```

**Mayar equivalent**

```http
GET {base}/payment/close/{id}
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Close Single Payment Request](https://docs.mayar.id/api-reference/reqpayment/close)

---

### Re-open Single Payment Request

**Package**

```php
$paymentRequest = PaymentRequest::findOrFail('uuid');

if ($paymentRequest->open()) {
    // Mayar messages === "success"
}
```

**Mayar equivalent**

```http
GET {base}/payment/open/{id}
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Re-open Single Payment Request](https://docs.mayar.id/api-reference/reqpayment/reopen)

---

## Installments

Module namespace: `Bensondevs\Mayar\Installments\`

API-backed `Installment` resources support **create** and **detail** only (no list, edit, or close endpoints in the Mayar API). Use an Eloquent-*like* style: `new`, attribute access, `setInstallment()`, and `save()` for create; `find()` / `findOrFail()` for detail. Create payloads are validated before any HTTP request.

Installment terms (`description`, `interest`, `tenure`, `dueDate`) are set via `setInstallment()` using `InstallmentTerms` DTOs or arrays. Calling `save()` on an existing record throws `LogicException` because the API has no update endpoint.

`{base}` is your configured API root. All requests require `Authorization: Bearer {MAYAR_API_KEY}`.

### Create Installment

**Package**

```php
use Bensondevs\Mayar\Installments\Installment;

$installment = new Installment;
$installment->name = 'Customer name';
$installment->email = 'customer@example.com';
$installment->mobile = '081234567890';
$installment->amount = 1500000;
$installment->setInstallment([
    'description' => 'Cicil Produk 3 Bulan',
    'interest' => 0,
    'tenure' => 3,
    'dueDate' => 11,
]);
$installment->save();

// Static shorthand (equivalent to new Installment($attrs)->save())
$installment = Installment::create([
    'name' => 'Customer name',
    'email' => 'customer@example.com',
    'mobile' => '081234567890',
    'amount' => 1500000,
    'installment' => [
        'description' => 'Cicil Produk 3 Bulan',
        'interest' => 0,
        'tenure' => 3,
        'dueDate' => 11,
    ],
]);
```

**Mayar equivalent**

```http
POST {base}/installment/create
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [POST Create Installment](https://docs.mayar.id/api-reference/installment/create)

---

### Get Installment Detail

**Package**

```php
use Bensondevs\Mayar\Installments\Installment;

$installment = Installment::find('uuid');
$installment = Installment::findOrFail('uuid');
```

**Mayar equivalent**

```http
GET {base}/installment/{id}
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Installment Detail](https://docs.mayar.id/api-reference/installment/detail)

---

## Discounts

Module namespace: `Bensondevs\Mayar\Discounts\`

API-backed `Discount` resources support **create**, **validate**, and **detail** (no list or edit endpoints). Use an Eloquent-*like* style for create: `new`, `setDiscount()`, `setCoupon()`, and `save()`. Coupon validation at checkout uses `Discount::validate()`. Detail uses `find()` / `findOrFail()`. Create and validate payloads are validated before any HTTP request.

Nested `discount` rules and `coupon` code are set via `setDiscount()` / `setCoupon()` using `DiscountRules` / `CouponCode` DTOs or arrays. Calling `save()` on an existing record throws `LogicException` because the API has no update endpoint.

`{base}` is your configured API root. All requests require `Authorization: Bearer {MAYAR_API_KEY}`.

### Create Discount with Coupon

**Package**

```php
use Bensondevs\Mayar\Discounts\Discount;

$discount = new Discount;
$discount->name = 'Diskon Murmer';
$discount->expiredAt = '2030-01-01T09:06:14.933Z';
$discount->products = [];
$discount->setDiscount([
    'discountType' => 'monetary',
    'eligibleCustomerType' => 'all',
    'minimumPurchase' => 500000,
    'value' => 100000,
    'totalCoupons' => 100,
]);
$discount->setCoupon([
    'code' => 'haribaik',
    'type' => 'reusable',
]);
$discount->save();

// Static shorthand (equivalent to new Discount($attrs)->save())
$discount = Discount::create([
    'name' => 'Diskon Murmer',
    'expiredAt' => '2030-01-01T09:06:14.933Z',
    'products' => [],
    'discount' => [
        'discountType' => 'monetary',
        'eligibleCustomerType' => 'all',
        'minimumPurchase' => 500000,
        'value' => 100000,
        'totalCoupons' => 100,
    ],
    'coupon' => [
        'code' => 'haribaik',
        'type' => 'reusable',
    ],
]);
```

**Mayar equivalent**

```http
POST {base}/coupon/create
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Create Discount with Coupon](https://docs.mayar.id/api-reference/discount/create)

---

### Validate Coupon

**Package**

```php
use Bensondevs\Mayar\Discounts\Discount;

$result = Discount::validate([
    'paymentLinkId' => 'uuid',
    'couponCode' => 'NFRBFUK',
    'finalAmount' => 0,
    'tickets' => [],
    'customerEmail' => '',
]);

if ($result->valid) {
    // $result->coupon contains coupon details from the API
}
```

**Mayar equivalent**

```http
POST {base}/coupon/validate
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Validate Coupon](https://docs.mayar.id/api-reference/discount/validate)

---

### Get Coupon Detail

**Package**

```php
use Bensondevs\Mayar\Discounts\Discount;

$discount = Discount::find('uuid');
$discount = Discount::findOrFail('uuid');
```

**Mayar equivalent**

```http
GET {base}/coupon/{id}
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Coupon Detail](https://docs.mayar.id/api-reference/discount/detail)

---

## Customers

Module namespace: `Bensondevs\Mayar\Customers\`

API-backed `Customer` resources use an Eloquent-*like* calling style (`paginate`, `findByEmail`, `save`, `create`) but are not database models. There is no find-by-ID endpoint in the Mayar API; use `findByEmail()` / `findByEmailOrFail()` instead. List pagination uses `totalCustomer` from the API response. Create payloads are validated before any HTTP request. Email updates and portal magic links use dedicated static methods (`updateEmail()`, `sendPortalMagicLink()`).

`{base}` is your configured API root. All requests require `Authorization: Bearer {MAYAR_API_KEY}`.

### Get Customer Page

**Package**

```php
use Bensondevs\Mayar\Customers\Customer;

$paginator = Customer::paginate(page: 1, perPage: 10);

foreach ($paginator as $customer) {
    echo $customer->name;
}
```

**Mayar equivalent**

```http
GET {base}/customer?page=1&pageSize=10
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Customer](https://docs.mayar.id/api-reference/customer/getdetail)

---

### Search Customer By Email

**Package**

```php
use Bensondevs\Mayar\Customers\Customer;

$customer = Customer::findByEmail('customer@example.com');
$customer = Customer::findByEmailOrFail('customer@example.com');
```

**Mayar equivalent**

```http
GET {base}/customer/detail?email=customer@example.com
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Search Customer By Email](https://docs.mayar.id/api-reference/customer/searchcustomerbyemail)

---

### Create Customer

**Package**

```php
use Bensondevs\Mayar\Customers\Customer;

$customer = new Customer;
$customer->name = 'Customer name';
$customer->email = 'customer@example.com';
$customer->mobile = '081234567890';
$customer->save();

// Static shorthand (equivalent to new Customer($attrs)->save())
$customer = Customer::create([
    'name' => 'Customer name',
    'email' => 'customer@example.com',
    'mobile' => '081234567890',
]);
```

The create response returns `customerId`; the package maps it to `id` on the resource.

**Mayar equivalent**

```http
POST {base}/customer/create
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Create Customer](https://docs.mayar.id/api-reference/customer/create)

---

### Update Customer Email

**Package**

```php
use Bensondevs\Mayar\Customers\Customer;

$success = Customer::updateEmail([
    'fromEmail' => 'old@example.com',
    'toEmail' => 'new@example.com',
]);
```

**Mayar equivalent**

```http
POST {base}/customer/update
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Update Customer Email](https://docs.mayar.id/api-reference/customer/update)

---

### Create Magic Link (Customer Portal)

**Package**

```php
use Bensondevs\Mayar\Customers\Customer;

$result = Customer::sendPortalMagicLink('customer@example.com');

echo $result->url;
```

**Mayar equivalent**

```http
POST {base}/customer/login/portal
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Create Magic Link](https://docs.mayar.id/api-reference/customer/createmagiclink)

---

## Transactions

Module namespace: `Bensondevs\Mayar\Transactions\`

The transactions module covers account balance, unpaid transaction listing, daily statistics, and dynamic QR code creation. `Transaction::accountBalance()` and `Transaction::daily()` return only the Mayar `data` object as a plain PHP array (not the full API envelope). Unpaid transactions use `UnpaidTransaction::paginate()` and return `Illuminate\Pagination\LengthAwarePaginator` of resource instances.

`{base}` is your configured API root. All requests require `Authorization: Bearer {MAYAR_API_KEY}`.

### Get Account Balance

**Package**

```php
use Bensondevs\Mayar\Transactions\Transaction;

$balance = Transaction::accountBalance();

// ['balanceActive' => 0, 'balancePending' => 0, 'balance' => 0]
```

**Mayar equivalent**

```http
GET {base}/balance
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Account Balance](https://docs.mayar.id/api-reference/transaction/accountbalance)

---

### Get Unpaid Transactions

**Package**

```php
use Bensondevs\Mayar\Transactions\UnpaidTransaction;

$paginator = UnpaidTransaction::paginate(page: 1, perPage: 10);

foreach ($paginator as $transaction) {
    echo $transaction->paymentUrl;
}
```

**Mayar equivalent**

```http
GET {base}/transactions/unpaid?page=1&pageSize=10
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get Unpaid Transaction](https://docs.mayar.id/api-reference/transaction/unpaidtransaction)

---

### Get Daily Transaction Statistics

**Package**

```php
use Bensondevs\Mayar\Transactions\Transaction;

$daily = Transaction::daily();

// ['date' => '2026-05-08', 'tpvCount' => 125000, 'trxCount' => 10]
```

**Mayar equivalent**

```http
GET {base}/transactions/daily
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Transaction Daily](https://docs.mayar.id/api-reference/transaction/dailytransaction)

---

### Create Dynamic QR Code

**Package**

```php
use Bensondevs\Mayar\Transactions\Transaction;

$result = Transaction::createDynamicQrCode(amount: 10000);

echo $result->url;
echo $result->amount;
```

**Mayar equivalent**

```http
POST {base}/qrcode/create
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Create Dynamic QRCode](https://docs.mayar.id/api-reference/transaction/createdynamicqrcode)

---

## Webhooks

Module namespace: `Bensondevs\Mayar\Webhooks\`

The webhooks module covers paginated webhook delivery history and POST actions to register, test, and retry URL hooks. `WebhookHistory::paginate()` returns `Illuminate\Pagination\LengthAwarePaginator` of `WebhookHistory` instances. Register, test, and retry use the `Webhook` facade and return `bool` based on the Mayar envelope (`statusCode` + `messages`). The API returns webhook `payload` as a JSON string; the package keeps it as a string (no auto-decode).

`{base}` is your configured API root. All requests require `Authorization: Bearer {MAYAR_API_KEY}`.

### Get Webhook History

**Package**

```php
use Bensondevs\Mayar\Webhooks\WebhookHistory;

$paginator = WebhookHistory::paginate(page: 1, perPage: 10);

foreach ($paginator as $history) {
    echo $history->type;
    echo $history->urlDestination;
}
```

**Mayar equivalent**

```http
GET {base}/webhook/history?page=1&pageSize=10
Authorization: Bearer {MAYAR_API_KEY}
```

**API reference:** [Get History](https://docs.mayar.id/api-reference/webhook/history)

---

### Register URL Hook

**Package**

```php
use Bensondevs\Mayar\Webhooks\Webhook;

$success = Webhook::register('https://example.com/webhook');
```

**Mayar equivalent**

```http
POST {base}/webhook/register
Authorization: Bearer {MAYAR_API_KEY}
Content-Type: application/json

{"urlHook":"https://example.com/webhook"}
```

**API reference:** [Register URL Hook](https://docs.mayar.id/api-reference/webhook/registerurlhook)

---

### Test URL Hook

**Package**

```php
use Bensondevs\Mayar\Webhooks\Webhook;

$success = Webhook::test('https://example.com/webhook');
```

**Mayar equivalent**

```http
POST {base}/webhook/test
Authorization: Bearer {MAYAR_API_KEY}
Content-Type: application/json

{"urlHook":"https://example.com/webhook"}
```

**API reference:** [Test URL Hook](https://docs.mayar.id/api-reference/webhook/testurlhook)

---

### Retry Webhook History

**Package**

```php
use Bensondevs\Mayar\Webhooks\Webhook;

$success = Webhook::retry('7d567063-ad7f-48d5-9e84-0e41938783a5');
```

**Mayar equivalent**

```http
POST {base}/webhook/retry
Authorization: Bearer {MAYAR_API_KEY}
Content-Type: application/json

{"webhookHistoryId":"7d567063-ad7f-48d5-9e84-0e41938783a5"}
```

**API reference:** [Retry History](https://docs.mayar.id/api-reference/webhook/retryhistory)

---

## Develop and test

```bash
composer install
composer test
```

Tests use `Http::fake()` and do not call the live Mayar API.

## Roadmap

- Additional Mayar resources

## License

MIT
