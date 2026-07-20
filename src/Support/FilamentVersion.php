<?php

namespace FilamentCloudFileLinks\Support;

use Composer\InstalledVersions;

final class FilamentVersion
{
    public static function major(): int
    {
        if (! InstalledVersions::isInstalled('filament/forms')) {
            return class_exists(\Filament\Actions\Action::class) && ! class_exists(\Filament\Support\Enums\ActionSize::class)
                ? 5
                : 3;
        }

        $version = InstalledVersions::getVersion('filament/forms')
            ?? InstalledVersions::getPrettyVersion('filament/forms')
            ?? '3.0.0';

        return (int) explode('.', ltrim($version, 'v'))[0];
    }

    public static function actionClass(): string
    {
        if (self::major() >= 4) {
            return \Filament\Actions\Action::class;
        }

        return \Filament\Forms\Components\Actions\Action::class;
    }

    public static function smallSize(): mixed
    {
        if (enum_exists(\Filament\Support\Enums\Size::class)) {
            return \Filament\Support\Enums\Size::Small;
        }

        return \Filament\Support\Enums\ActionSize::Small;
    }

    /**
     * @param  array<string, mixed>  $arguments
     */
    public static function actionWithArguments(object $action, array $arguments): object
    {
        if (self::major() >= 4 && method_exists($action, '__invoke')) {
            return $action($arguments);
        }

        return $action->arguments($arguments);
    }
}
