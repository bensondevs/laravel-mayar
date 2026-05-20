# 🛍️ Products

Module namespace: `Bensondevs\Mayar\Products\`

`Product` uses an Eloquent-like style (`find`, `findOrFail`, `paginate`, query chaining) but is API-backed, not a database model.

## Endpoints

### Get Product Page

```php
use Bensondevs\Mayar\Products\Product;

$paginator = Product::paginate(page: 1, perPage: 10);
```

API reference: [Get Product Page](https://docs.mayar.id/api-reference/product/productpage.md)

### Get Product Page with Type Filter

```php
use Bensondevs\Mayar\Products\Enums\ProductType;
use Bensondevs\Mayar\Products\Product;

$paginator = Product::type(ProductType::Ebook)->paginate(page: 1, perPage: 10);
```

API reference: [Get Product Page with Type Filter](https://docs.mayar.id/api-reference/product/productpagewithfilter)

### Search Product

```php
use Bensondevs\Mayar\Products\Product;

$paginator = Product::search(keyword: 'course')->paginate(page: 1, perPage: 10);
```

API reference: [Search Product](https://docs.mayar.id/api-reference/product/search.md)

### Type Filter + Search

```php
use Bensondevs\Mayar\Products\Enums\ProductType;
use Bensondevs\Mayar\Products\Product;

$paginator = Product::type(ProductType::Ebook)
    ->search(keyword: 'journal')
    ->paginate(page: 1, perPage: 10);
```

API references: [Type Filter](https://docs.mayar.id/api-reference/product/productpagewithfilter) · [Search](https://docs.mayar.id/api-reference/product/search.md)

### Get Detail Product

```php
use Bensondevs\Mayar\Products\Product;

$product = Product::find('uuid');
$product = Product::findOrFail('uuid');
```

API reference: [Get Detail Product](https://docs.mayar.id/api-reference/product/detail.md)

### Close Product

```php
$product = Product::findOrFail('uuid');
$success = $product->close();
```

API reference: [Close Product](https://docs.mayar.id/api-reference/product/close.md)

### Re-open Product

```php
$product = Product::findOrFail('uuid');
$success = $product->reopen();
```

API reference: [Re-open Product](https://docs.mayar.id/api-reference/product/reopen.md)
