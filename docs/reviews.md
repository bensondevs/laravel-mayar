# ⭐ Reviews

Module namespace: `Bensondevs\Mayar\Api\Reviews\`

Reviews are read-only and support paginated listing.

## Get All Reviews

```php
use Bensondevs\Mayar\Api\Reviews\Review;

$paginator = Review::paginate(page: 1, perPage: 10);

foreach ($paginator as $review) {
    echo $review->message;
    echo $review->rating;
}

echo $paginator->total();
echo $paginator->perPage();
echo $paginator->currentPage();
echo $paginator->lastPage();
```

Returns: `LengthAwarePaginator<Review>` with item attributes such as `id`, `message`, `rating`, `createdAt`.

API reference: [Get All Reviews](https://docs.mayar.id/api-reference/reviews/getallreviews)
