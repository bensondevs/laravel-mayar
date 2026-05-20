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
```

API reference: [Get All Reviews](https://docs.mayar.id/api-reference/reviews/getallreviews)
