# 🛍️ Products

Module namespace: `Bensondevs\Mayar\Api\Products\`

`Product` uses an Eloquent-like style (`find`, `findOrFail`, `paginate`, query chaining) but is API-backed, not a database model.

## Endpoints

### Get Product Page

```php
use Bensondevs\Mayar\Api\Products\Product;

$paginator = Product::paginate(page: 1, perPage: 10);
```

Returns: `LengthAwarePaginator<Product>`

```php
foreach ($paginator as $product) {
    echo $product->id;
    echo $product->name;
    echo $product->status;
}

echo $paginator->total();
echo $paginator->perPage();
echo $paginator->currentPage();
echo $paginator->lastPage();
```

API reference: [Get Product Page](https://docs.mayar.id/api-reference/product/productpage.md)

### Get Product Page with Type Filter

```php
use Bensondevs\Mayar\Api\Products\Enums\ProductType;
use Bensondevs\Mayar\Api\Products\Product;

$paginator = Product::type(ProductType::Ebook)->paginate(page: 1, perPage: 10);
```

Returns: `LengthAwarePaginator<Product>` (same paginator usage pattern as above).

```php
foreach ($paginator as $product) {
    echo $product->name;
}
echo $paginator->total();
```

API reference: [Get Product Page with Type Filter](https://docs.mayar.id/api-reference/product/productpagewithfilter)

### Search Product

```php
use Bensondevs\Mayar\Api\Products\Product;

$paginator = Product::search(keyword: 'course')->paginate(page: 1, perPage: 10);
```

Returns: `LengthAwarePaginator<Product>` (same paginator usage pattern as above).

```php
foreach ($paginator as $product) {
    echo $product->name;
}
echo $paginator->currentPage();
```

API reference: [Search Product](https://docs.mayar.id/api-reference/product/search.md)

### Type Filter + Search

```php
use Bensondevs\Mayar\Api\Products\Enums\ProductType;
use Bensondevs\Mayar\Api\Products\Product;

$paginator = Product::type(ProductType::Ebook)
    ->search(keyword: 'journal')
    ->paginate(page: 1, perPage: 10);
```

Returns: `LengthAwarePaginator<Product>` (same paginator usage pattern as above).

```php
foreach ($paginator as $product) {
    echo $product->id;
}
echo $paginator->lastPage();
```

API references: [Type Filter](https://docs.mayar.id/api-reference/product/productpagewithfilter) · [Search](https://docs.mayar.id/api-reference/product/search.md)

### Get Detail Product

```php
use Bensondevs\Mayar\Api\Products\Product;

$product = Product::find('uuid');
$product = Product::findOrFail('uuid');
```

Returns:
- `Product::find(string $id): Product|null`
- `Product::findOrFail(string $id): Product` (throws when not found)

Common product attributes:
- `id`, `name`, `description`, `price`, `status`, `type`, `createdAt`, `updatedAt`

```php
$product = Product::findOrFail('uuid');
echo $product->id;
echo $product->name;
echo $product->status;
echo $product->price;
```

API reference: [Get Detail Product](https://docs.mayar.id/api-reference/product/detail.md)

### Close Product

```php
$product = Product::findOrFail('uuid');
$success = $product->close();
```

Returns: `bool` (`true` when product is closed).

Failure cases can happen when:
- product ID does not exist
- product is already closed
- API validation or auth fails

```php
try {
    $product = Product::findOrFail('uuid');
    $success = $product->close();

    if (! $success) {
        // Handle API-level rejection (invalid state or business rule failure)
    }
} catch (\Throwable $e) {
    // Handle not found, validation, or transport errors
}
```

API reference: [Close Product](https://docs.mayar.id/api-reference/product/close.md)

### Re-open Product

```php
$product = Product::findOrFail('uuid');
$success = $product->reopen();
```

Returns: `bool` (`true` when product is re-opened).

Failure cases can happen when:
- product ID does not exist
- product is not in a reopenable status
- API validation or auth fails

```php
try {
    $product = Product::findOrFail('uuid');
    $success = $product->reopen();

    if (! $success) {
        // Handle API-level rejection (invalid state or business rule failure)
    }
} catch (\Throwable $e) {
    // Handle not found, validation, or transport errors
}
```

API reference: [Re-open Product](https://docs.mayar.id/api-reference/product/reopen.md)
