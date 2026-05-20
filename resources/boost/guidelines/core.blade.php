## Laravel Mayar

This package provides a Laravel-friendly, API-backed client for the Mayar Headless API.
It exposes resource-style classes for products, invoices, payment requests, customers,
transactions, webhooks, and related workflows.

### Setup

- Install the package:

@verbatim
<code-snippet name="Install Laravel Mayar" lang="shell">
composer require bensondevs/laravel-mayar
</code-snippet>
@endverbatim

- Configure environment variables:
  - `MAYAR_API_KEY`
  - `MAYAR_MODE` (`sandbox` or `production`)
- Optionally publish config:

@verbatim
<code-snippet name="Publish Mayar Config" lang="shell">
php artisan vendor:publish --tag=mayar-config
</code-snippet>
@endverbatim

### How To Use The Package

- Treat package resources as API-backed objects (not Eloquent models).
- Prefer the package resource APIs before writing raw HTTP requests.
- Use `find(...)` when nullable results are acceptable and `findOrFail(...)` for strict flows.
- Use chainable query helpers and finalize with `paginate(page: ..., perPage: ...)`.

@verbatim
<code-snippet name="Search Products and Paginate" lang="php">
use Bensondevs\Mayar\Api\Products\Product;
use Bensondevs\Mayar\Api\Products\Enums\ProductType;

$products = Product::type(ProductType::Ebook)
    ->search(keyword: 'course')
    ->paginate(page: 1, perPage: 10);
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Create Invoice" lang="php">
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
</code-snippet>
@endverbatim

### Webhooks

This package supports:
- Outbound webhook management (`register`, `test`, `retry`, `history` APIs)
- Inbound webhook receiving with `Bensondevs\Mayar\Http\Controllers\MayarWebhookController`
  and event dispatching to typed webhook events

- For inbound webhook processing, prefer Laravel listeners bound to dispatched Mayar events
  instead of placing business logic directly in route handlers.
- Access webhook payload data from `$event->data` (`MayarWebhookData`) and use `get(...)` / `has(...)`.
- Do not generate tests that try to prove Laravel listener auto-discovery internals. Test webhook
  event dispatching and explicit listener behavior instead (manual registration is acceptable in tests).

@verbatim
<code-snippet name="Register Webhook Listener" lang="php">
use App\Listeners\HandleMayarPaymentReceived;
use Bensondevs\Mayar\Events\Webhooks\PaymentReceived;

protected $listen = [
    PaymentReceived::class => [
        HandleMayarPaymentReceived::class,
    ],
];
</code-snippet>
@endverbatim

### Error Handling Expectations

- API operations can fail due to validation, authorization, transport issues, or business state.
- Methods like `close()`, `open()` / `reopen()`, and some static actions may return `bool`; handle `false` explicitly.
- Wrap strict operations in `try/catch` where failures should be converted into application-specific behavior.

### Package-Conscious Generation Rules

When generating code for this package:
- Use namespaces under `Bensondevs\Mayar\Api\...` and documented enums/events.
- Keep examples aligned with package APIs from the official docs.
- Avoid assuming database persistence for Mayar resources.
