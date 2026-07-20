<?php

namespace FilamentCloudFileLinks\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use FilamentCloudFileLinks\Support\FilamentVersion;

class CloudFileLinks extends Field
{
    use HasExtraAlpineAttributes;
    /**
     * @var view-string
     */
    protected string $view = 'filament-cloud-file-links::components.cloud-file-links';

    protected string | Closure | null $addActionLabel = null;

    protected string | Closure | null $fileLabel = null;

    protected string | Closure | null $emptyLabel = null;

    protected string | Closure | null $editActionLabel = null;

    protected string | Closure | null $nameFieldLabel = null;

    protected string | Closure | null $urlFieldLabel = null;

    protected string | Closure | null $nameFieldPlaceholder = null;

    protected string | Closure | null $urlFieldPlaceholder = null;

    protected bool | Closure $isAddable = true;

    protected bool | Closure $isDeletable = true;

    protected bool | Closure $canEditKeys = true;

    protected bool | Closure $canEditValues = true;

    protected bool | Closure $deleteRequiresConfirmation = false;

    protected string | Closure | null $deleteConfirmationModalHeading = null;

    protected string | Closure | null $deleteConfirmationModalDescription = null;

    protected string | Closure | null $deleteConfirmationModalSubmitActionLabel = null;

    protected string | Closure | null $deleteConfirmationModalCancelActionLabel = null;

    protected ?Closure $modifyAddActionUsing = null;

    protected ?Closure $modifyEditActionUsing = null;

    protected ?Closure $modifyDeleteActionUsing = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([]);

        $this->dehydrateStateUsing(static function (?array $state): array {
            return collect($state ?? [])
                ->filter(static fn (?string $value, ?string $key): bool => filled($key) && filled($value))
                ->mapWithKeys(static fn (?string $value, ?string $key): array => [
                    (string) $key => (string) $value,
                ])
                ->all();
        });

        $this->registerActions([
            fn (CloudFileLinks $component) => $component->getAddAction(),
            fn (CloudFileLinks $component) => $component->getEditAction(),
            fn (CloudFileLinks $component) => $component->getDeleteAction(),
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

    public function getAddAction(): mixed
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

    public function getEditAction(): mixed
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

    public function getDeleteAction(): mixed
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

        if ($this->isDeleteConfirmationRequired()) {
            $action->requiresConfirmation();

            if (filled($heading = $this->getDeleteConfirmationModalHeading())) {
                $action->modalHeading($heading);
            }

            if (filled($description = $this->getDeleteConfirmationModalDescription())) {
                $action->modalDescription($description);
            }

            if (filled($submitLabel = $this->getDeleteConfirmationModalSubmitActionLabel())) {
                $action->modalSubmitActionLabel($submitLabel);
            }

            if (filled($cancelLabel = $this->getDeleteConfirmationModalCancelActionLabel())) {
                $action->modalCancelActionLabel($cancelLabel);
            }
        }

        if ($this->modifyDeleteActionUsing) {
            $action = $this->evaluate($this->modifyDeleteActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function getAddActionName(): string
    {
        return 'add';
    }

    public function getEditActionName(): string
    {
        return 'edit';
    }

    public function getDeleteActionName(): string
    {
        return 'delete';
    }

    public function addAction(?Closure $callback): static
    {
        $this->modifyAddActionUsing = $callback;

        return $this;
    }

    public function editAction(?Closure $callback): static
    {
        $this->modifyEditActionUsing = $callback;

        return $this;
    }

    public function deleteAction(?Closure $callback): static
    {
        $this->modifyDeleteActionUsing = $callback;

        return $this;
    }

    public function addActionLabel(string | Closure | null $label): static
    {
        $this->addActionLabel = $label;

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

    public function addable(bool | Closure $condition = true): static
    {
        $this->isAddable = $condition;

        return $this;
    }

    public function deletable(bool | Closure $condition = true): static
    {
        $this->isDeletable = $condition;

        return $this;
    }

    public function deleteRequiresConfirmation(bool | Closure $condition = true): static
    {
        $this->deleteRequiresConfirmation = $condition;

        return $this;
    }

    public function deleteConfirmationModalHeading(string | Closure | null $heading): static
    {
        $this->deleteConfirmationModalHeading = $heading;

        return $this;
    }

    public function deleteConfirmationModalDescription(string | Closure | null $description): static
    {
        $this->deleteConfirmationModalDescription = $description;

        return $this;
    }

    public function deleteConfirmationModalSubmitActionLabel(string | Closure | null $label): static
    {
        $this->deleteConfirmationModalSubmitActionLabel = $label;

        return $this;
    }

    public function deleteConfirmationModalCancelActionLabel(string | Closure | null $label): static
    {
        $this->deleteConfirmationModalCancelActionLabel = $label;

        return $this;
    }

    public function editableKeys(bool | Closure $condition = true): static
    {
        $this->canEditKeys = $condition;

        return $this;
    }

    public function editableValues(bool | Closure $condition = true): static
    {
        $this->canEditValues = $condition;

        return $this;
    }

    public function isAddable(): bool
    {
        return (bool) $this->evaluate($this->isAddable);
    }

    public function isDeletable(): bool
    {
        return (bool) $this->evaluate($this->isDeletable);
    }

    public function isDeleteConfirmationRequired(): bool
    {
        return (bool) $this->evaluate($this->deleteRequiresConfirmation);
    }

    public function getDeleteConfirmationModalHeading(): ?string
    {
        return $this->evaluate($this->deleteConfirmationModalHeading)
            ?? __('filament-cloud-file-links::cloud-file-links.actions.delete.modal_heading');
    }

    public function getDeleteConfirmationModalDescription(): ?string
    {
        return $this->evaluate($this->deleteConfirmationModalDescription)
            ?? __('filament-cloud-file-links::cloud-file-links.actions.delete.modal_description');
    }

    public function getDeleteConfirmationModalSubmitActionLabel(): ?string
    {
        return $this->evaluate($this->deleteConfirmationModalSubmitActionLabel)
            ?? __('filament-cloud-file-links::cloud-file-links.actions.delete.modal_submit');
    }

    public function getDeleteConfirmationModalCancelActionLabel(): ?string
    {
        return $this->evaluate($this->deleteConfirmationModalCancelActionLabel)
            ?? __('filament-cloud-file-links::cloud-file-links.actions.delete.modal_cancel');
    }

    public function canEditKeys(): bool
    {
        return (bool) $this->evaluate($this->canEditKeys);
    }

    public function canEditValues(): bool
    {
        return (bool) $this->evaluate($this->canEditValues);
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
}
