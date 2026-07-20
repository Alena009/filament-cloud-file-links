<?php

namespace FilamentCloudFileLinks\Forms\Components;

use Closure;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use FilamentCloudFileLinks\Support\FilamentVersion;

class CloudFileLinks extends KeyValue
{
    /**
     * @var view-string
     */
    protected string $view = 'filament-cloud-file-links::components.cloud-file-links';

    protected string | Closure | null $fileLabel = null;

    protected string | Closure | null $emptyLabel = null;

    protected string | Closure | null $editActionLabel = null;

    protected string | Closure | null $nameFieldLabel = null;

    protected string | Closure | null $urlFieldLabel = null;

    protected string | Closure | null $nameFieldPlaceholder = null;

    protected string | Closure | null $urlFieldPlaceholder = null;

    protected ?Closure $modifyEditActionUsing = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reorderable(false);

        $this->dehydrateStateUsing(static function (?array $state): array {
            return collect($state ?? [])
                ->filter(static fn (?string $value, ?string $key): bool => filled($key) && filled($value))
                ->mapWithKeys(static fn (?string $value, ?string $key): array => [
                    (string) $key => (string) $value,
                ])
                ->all();
        });

        $this->registerActions([
            fn (CloudFileLinks $component) => $component->getEditAction(),
        ]);
    }

    /**
     * @return array<int, TextInput>
     */
    public function getLinkFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label($this->getNameFieldLabel())
                ->placeholder($this->getNameFieldPlaceholder())
                ->required()
                ->maxLength(255),
            TextInput::make('url')
                ->label($this->getUrlFieldLabel())
                ->placeholder($this->getUrlFieldPlaceholder())
                ->url()
                ->required()
                ->maxLength(2048),
        ];
    }

    public function getAddAction(): object
    {
        $actionClass = FilamentVersion::actionClass();

        $action = $actionClass::make($this->getAddActionName())
            ->label(fn (CloudFileLinks $component): string => $component->getAddActionLabel())
            ->color('gray')
            ->link()
            ->form(fn (CloudFileLinks $component): array => $component->getLinkFormSchema())
            ->modalHeading(fn (CloudFileLinks $component): string => $component->getAddActionLabel())
            ->modalSubmitActionLabel(__('filament-cloud-file-links::cloud-file-links.actions.add.submit'))
            ->action(function (array $data, CloudFileLinks $component): void {
                $state = $component->getState() ?? [];
                $state[$data['name']] = $data['url'];
                $component->state($state);
            })
            ->visible(fn (): bool => $this->isAddable());

        if ($this->modifyAddActionUsing) {
            $action = $this->evaluate($this->modifyAddActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function getEditAction(): object
    {
        $actionClass = FilamentVersion::actionClass();

        $action = $actionClass::make($this->getEditActionName())
            ->label(fn (CloudFileLinks $component): string => $component->getEditActionLabel())
            ->icon('heroicon-m-pencil-square')
            ->color('gray')
            ->iconButton()
            ->size(FilamentVersion::smallSize())
            ->form(fn (CloudFileLinks $component): array => $component->getLinkFormSchema())
            ->fillForm(function (array $arguments, CloudFileLinks $component): array {
                $key = $arguments['key'] ?? null;
                $state = $component->getState() ?? [];

                if (! filled($key) || ! array_key_exists($key, $state)) {
                    return [
                        'name' => null,
                        'url' => null,
                    ];
                }

                return [
                    'name' => $key,
                    'url' => $state[$key],
                ];
            })
            ->modalHeading(fn (CloudFileLinks $component): string => $component->getEditActionLabel())
            ->modalSubmitActionLabel(__('filament-cloud-file-links::cloud-file-links.actions.edit.submit'))
            ->action(function (array $data, array $arguments, CloudFileLinks $component): void {
                $state = $component->getState() ?? [];
                $oldKey = $arguments['key'] ?? null;

                if (filled($oldKey) && array_key_exists($oldKey, $state)) {
                    unset($state[$oldKey]);
                }

                $state[$data['name']] = $data['url'];
                $component->state($state);
            })
            ->visible(fn (): bool => $this->isEditable());

        if ($this->modifyEditActionUsing) {
            $action = $this->evaluate($this->modifyEditActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function getDeleteAction(): object
    {
        $actionClass = FilamentVersion::actionClass();

        $action = $actionClass::make($this->getDeleteActionName())
            ->label(__('filament-forms::components.key_value.actions.delete.label'))
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->iconButton()
            ->size(FilamentVersion::smallSize())
            ->action(function (array $arguments, CloudFileLinks $component): void {
                $key = $arguments['key'] ?? null;

                if (! filled($key)) {
                    return;
                }

                $state = $component->getState() ?? [];
                unset($state[$key]);
                $component->state($state);
            })
            ->visible(fn (): bool => $this->isDeletable());

        if ($this->modifyDeleteActionUsing) {
            $action = $this->evaluate($this->modifyDeleteActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function getEditActionName(): string
    {
        return 'edit';
    }

    public function editAction(?Closure $callback): static
    {
        $this->modifyEditActionUsing = $callback;

        return $this;
    }

    public function editActionLabel(string | Closure | null $label): static
    {
        $this->editActionLabel = $label;

        return $this;
    }

    public function fileLabel(string | Closure | null $label): static
    {
        $this->fileLabel = $label;

        return $this;
    }

    public function emptyLabel(string | Closure | null $label): static
    {
        $this->emptyLabel = $label;

        return $this;
    }

    public function nameFieldLabel(string | Closure | null $label): static
    {
        $this->nameFieldLabel = $label;

        return $this;
    }

    public function urlFieldLabel(string | Closure | null $label): static
    {
        $this->urlFieldLabel = $label;

        return $this;
    }

    public function nameFieldPlaceholder(string | Closure | null $placeholder): static
    {
        $this->nameFieldPlaceholder = $placeholder;

        return $this;
    }

    public function urlFieldPlaceholder(string | Closure | null $placeholder): static
    {
        $this->urlFieldPlaceholder = $placeholder;

        return $this;
    }

    public function getEditActionLabel(): string
    {
        return $this->evaluate($this->editActionLabel)
            ?? __('filament-cloud-file-links::cloud-file-links.actions.edit.label');
    }

    public function getFileLabel(): string
    {
        return $this->evaluate($this->fileLabel)
            ?? __('filament-cloud-file-links::cloud-file-links.fields.file.label');
    }

    public function getEmptyLabel(): string
    {
        return $this->evaluate($this->emptyLabel)
            ?? __('filament-cloud-file-links::cloud-file-links.empty');
    }

    public function getNameFieldLabel(): string
    {
        return $this->evaluate($this->nameFieldLabel)
            ?? __('filament-cloud-file-links::cloud-file-links.fields.name.label');
    }

    public function getUrlFieldLabel(): string
    {
        return $this->evaluate($this->urlFieldLabel)
            ?? __('filament-cloud-file-links::cloud-file-links.fields.url.label');
    }

    public function getNameFieldPlaceholder(): ?string
    {
        return $this->evaluate($this->nameFieldPlaceholder);
    }

    public function getUrlFieldPlaceholder(): ?string
    {
        return $this->evaluate($this->urlFieldPlaceholder);
    }

    public function isEditable(): bool
    {
        return $this->canEditKeys() && $this->canEditValues();
    }

    public function getAddActionLabel(): string
    {
        return $this->evaluate($this->addActionLabel)
            ?? __('filament-cloud-file-links::cloud-file-links.actions.add.label');
    }
}
