<?php

namespace FilamentCloudFileLinks;

use Illuminate\Support\ServiceProvider;

class CloudFileLinksServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-cloud-file-links');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-cloud-file-links');
    }
}
