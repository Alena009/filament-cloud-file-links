# Filament Cloud File Links

A local Filament form field based on **KeyValue**. Stores an array of `link name => URL` for cloud storage file links.

## UI

Looks like KeyValue, but with:

1. **File / Link** — clickable name that opens the hidden URL
2. **Edit** — modal with the same form as when adding
3. **Delete** — removes the row

## Requirements

- PHP 8.2+
- Filament Forms 5.x

## Local setup (without Packagist)

### Option A — path repository (recommended)

In your Laravel app's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../filament-cloud-file-links",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
        "local/filament-cloud-file-links": "^2.0"
    }
}
```

Adjust the `url` path relative to your project root (e.g. `C:/work/filament-cloud-file-links`).

Then:

```bash
composer update local/filament-cloud-file-links
```

The service provider is registered via Laravel package discovery.

### Option B — PSR-4 only (no package `require`)

In your app's `composer.json`:

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "FilamentCloudFileLinks\\": "../filament-cloud-file-links/src/"
        }
    }
}
```

```bash
composer dump-autoload
```

Register the provider manually.

**Laravel 11+** — in `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    FilamentCloudFileLinks\CloudFileLinksServiceProvider::class,
];
```

**Laravel 10** — in `config/app.php` → `providers`:

```php
FilamentCloudFileLinks\CloudFileLinksServiceProvider::class,
```

Views and translations live in the package; the provider is required to load them.

## Usage

```php
use FilamentCloudFileLinks\Forms\Components\CloudFileLinks;

CloudFileLinks::make('cloud_files')
    ->label('Cloud files')
```

Cast the model attribute to `array` (same as KeyValue):

```php
protected $casts = [
    'cloud_files' => 'array',
];
```

Stored value shape:

```php
[
    'Contract.pdf' => 'https://storage.example.com/files/abc123',
    'Passport scan' => 'https://storage.example.com/files/def456',
]
```

## Customization

```php
CloudFileLinks::make('cloud_files')
    ->fileLabel('Documents')
    ->emptyLabel('No documents yet')
    ->nameFieldLabel('Name')
    ->urlFieldLabel('URL')
    ->nameFieldPlaceholder('e.g. Certificate of completion')
    ->urlFieldPlaceholder('https://...')
    ->addActionLabel('Add document')
    ->editActionLabel('Edit document')
    ->addable()
    ->deletable()
    ->editableKeys()   // together with editableValues controls the Edit button
    ->editableValues();
```
