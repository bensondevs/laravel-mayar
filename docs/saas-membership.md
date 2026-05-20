# ☁️ SaaS Membership

Module namespace: `Bensondevs\Mayar\Api\SaaSMembership\`

Manage SaaS licenses via verify, activate, and deactivate workflows.

## Verify License SaaS Subscription

```php
use Bensondevs\Mayar\Api\SaaSMembership\SaaSMembership;

$result = SaaSMembership::verify(
    licenseCode: 'YOUR-LICENSE-CODE',
    productId: 'YOUR-PRODUCT-ID',
);
```

API reference: [Verify License SaaS Subscription](https://docs.mayar.id/api-reference/saas/verify)

## Activate License

```php
use Bensondevs\Mayar\Api\SaaSMembership\SaaSMembership;

$success = SaaSMembership::activate(
    licenseCode: 'YOUR-LICENSE-CODE',
    productId: 'YOUR-PRODUCT-ID',
);
```

Returns: `bool` (`true` when activation succeeds).

Failure cases can happen when:
- license code is invalid or expired
- license is already active for the product
- API validation/auth fails

```php
try {
    $success = SaaSMembership::activate(
        licenseCode: 'YOUR-LICENSE-CODE',
        productId: 'YOUR-PRODUCT-ID',
    );

    if (! $success) {
        // Handle API-level activation rejection
    }
} catch (\Throwable $e) {
    // Handle validation, network, or API errors
}
```

API reference: [Activate License](https://docs.mayar.id/api-reference/saas/activate)

## Deactivate License

```php
use Bensondevs\Mayar\Api\SaaSMembership\SaaSMembership;

$success = SaaSMembership::deactivate(
    licenseCode: 'YOUR-LICENSE-CODE',
    productId: 'YOUR-PRODUCT-ID',
);
```

Returns: `bool` (`true` when deactivation succeeds).

Failure cases can happen when:
- license code is invalid
- license is already inactive
- API validation/auth fails

```php
try {
    $success = SaaSMembership::deactivate(
        licenseCode: 'YOUR-LICENSE-CODE',
        productId: 'YOUR-PRODUCT-ID',
    );

    if (! $success) {
        // Handle API-level rejection
    }
} catch (\Throwable $e) {
    // Handle validation, network, or API errors
}
```

API reference: [Deactivate License](https://docs.mayar.id/api-reference/saas/deactivate)
