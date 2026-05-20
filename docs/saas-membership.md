# ☁️ SaaS Membership

Module namespace: `Bensondevs\Mayar\SaaSMembership\`

Manage SaaS licenses via verify, activate, and deactivate workflows.

## Verify License SaaS Subscription

```php
use Bensondevs\Mayar\SaaSMembership\SaaSMembership;

$result = SaaSMembership::verify(
    licenseCode: 'YOUR-LICENSE-CODE',
    productId: 'YOUR-PRODUCT-ID',
);
```

API reference: [Verify License SaaS Subscription](https://docs.mayar.id/api-reference/saas/verify)

## Activate License

```php
use Bensondevs\Mayar\SaaSMembership\SaaSMembership;

$success = SaaSMembership::activate(
    licenseCode: 'YOUR-LICENSE-CODE',
    productId: 'YOUR-PRODUCT-ID',
);
```

API reference: [Activate License](https://docs.mayar.id/api-reference/saas/activate)

## Deactivate License

```php
use Bensondevs\Mayar\SaaSMembership\SaaSMembership;

$success = SaaSMembership::deactivate(
    licenseCode: 'YOUR-LICENSE-CODE',
    productId: 'YOUR-PRODUCT-ID',
);
```

API reference: [Deactivate License](https://docs.mayar.id/api-reference/saas/deactivate)
