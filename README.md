# Filament Cloud File Links

A Filament form field based on **KeyValue**. Stores an array of `link name => URL` for cloud storage file links.

## UI

Looks like KeyValue, but with:

1. **File / Link** — clickable name that opens the hidden URL
2. **Edit** — modal with the same form as when adding
3. **Delete** — removes the row

## Requirements

- PHP 8.1+
- Filament Forms 3.x, 4.x, or 5.x

## Installation

```bash
composer require alena009/filament-cloud-file-links
```

The service provider is registered via Laravel package discovery.

### Local path development (optional)

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
        "alena009/filament-cloud-file-links": "^2.0"
    }
}
```

```bash
composer update alena009/filament-cloud-file-links
```

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
    ->deleteRequiresConfirmation()
    ->deleteConfirmationModalHeading('Delete document')
    ->deleteConfirmationModalDescription('This cannot be undone.')
    ->deleteConfirmationModalSubmitActionLabel('Yes, delete')
    ->deleteConfirmationModalCancelActionLabel('Cancel')
    ->editableKeys()   // together with editableValues controls the Edit button
    ->editableValues();
```

## License

MIT
