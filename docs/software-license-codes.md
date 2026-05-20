# 🔑 Software License Codes

Module namespace: `Bensondevs\Mayar\SoftwareLicenseCodes\`

Use this module to verify software license codes through the Mayar software API (`/software/v1`).

## Verify License

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

API reference: [Verify License](https://docs.mayar.id/api-reference/licensecode/verifylicense)
