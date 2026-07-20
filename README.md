# Filament Cloud File Links

Локальный Filament-компонент формы на базе **KeyValue**: хранит массив `имя ссылки => URL` в облачном хранилище.

## Как выглядит

Таблица как у KeyValue, но с тремя колонками:

1. **Файл / Ссылка** — кликабельное имя, открывает скрытый URL
2. **Edit** — модалка с той же формой, что и при добавлении
3. **Delete** — удаление записи

## Требования

- PHP 8.2+
- Filament Forms 5.x

## Подключение без Packagist (локально)

### Вариант A — path-репозиторий (рекомендуется)

В `composer.json` вашего Laravel-проекта:

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

Путь `url` поправьте относительно корня проекта (например `C:/work/filament-cloud-file-links`).

Затем:

```bash
composer update local/filament-cloud-file-links
```

Service Provider подхватится через Laravel package discovery.

### Вариант B — только PSR-4 (без `require` пакета)

В `composer.json` приложения:

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

Зарегистрируйте провайдер вручную.

**Laravel 11+** — в `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    FilamentCloudFileLinks\CloudFileLinksServiceProvider::class,
];
```

**Laravel 10** — в `config/app.php` → `providers`:

```php
FilamentCloudFileLinks\CloudFileLinksServiceProvider::class,
```

Views и переводы лежат в пакете; без провайдера они не подключатся — провайдер обязателен.

## Использование

```php
use FilamentCloudFileLinks\Forms\Components\CloudFileLinks;

CloudFileLinks::make('cloud_files')
    ->label('Файлы в облаке')
```

В модели поле должно кастоваться в `array` (как у KeyValue):

```php
protected $casts = [
    'cloud_files' => 'array',
];
```

Сохранённое значение:

```php
[
    'Договор.pdf' => 'https://storage.example.com/files/abc123',
    'Скан паспорта' => 'https://storage.example.com/files/def456',
]
```

## Кастомизация

```php
CloudFileLinks::make('cloud_files')
    ->fileLabel('Документы')
    ->nameFieldLabel('Название')
    ->urlFieldLabel('Ссылка')
    ->nameFieldPlaceholder('Например, Акт выполненных работ')
    ->urlFieldPlaceholder('https://...')
    ->addActionLabel('Добавить документ')
    ->editActionLabel('Изменить документ')
    ->addable()
    ->deletable()
    ->editableKeys()   // вместе с editableValues управляет кнопкой Edit
    ->editableValues();
```
