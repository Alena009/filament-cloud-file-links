@php
    $hasInlineLabel = $hasInlineLabel();
    $isAddable = $isAddable();
    $isDeletable = $isDeletable();
    $isDisabled = $isDisabled();
    $isEditable = $isEditable();
    $statePath = $getStatePath();
    $rows = $getState() ?? [];
    $showActions = (! $isDisabled) && ($isEditable || $isDeletable);
    $editAction = $isEditable ? $getAction('edit') : null;
    $deleteAction = $isDeletable ? $getAction('delete') : null;
@endphp

@once
    <style>
        .fi-fo-cloud-file-links {
            width: 100%;
        }

        .fi-fo-cloud-file-links__table {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .fi-fo-cloud-file-links__row {
            display: flex;
            flex-direction: row;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
            border-bottom: 1px solid rgb(229 231 235);
        }

        .fi-fo-cloud-file-links__link {
            flex: 1 1 auto;
            min-width: 0;
            padding: 10px 12px 8px 10px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .fi-fo-cloud-file-links__file-icon {
            flex: 0 0 auto;
            width: 1rem;
            height: 1rem;
            color: rgb(107 114 128);
        }

        .dark .fi-fo-cloud-file-links__file-icon {
            color: rgb(156 163 175);
        }

        .fi-fo-cloud-file-links__link a {
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
            color: var(--primary-600);
            text-decoration: underline;
            text-underline-offset: 2px;
            overflow-wrap: anywhere;
        }

        .fi-fo-cloud-file-links__link a:hover {
            color: var(--primary-500);
        }

        .dark .fi-fo-cloud-file-links__link a {
            color: var(--primary-400);
        }

        .dark .fi-fo-cloud-file-links__link a:hover {
            color: var(--primary-300);
        }

        .fi-fo-cloud-file-links__link-empty {
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: rgb(107 114 128);
        }

        .fi-fo-cloud-file-links__actions {
            flex: 0 0 auto;
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: flex-end;
            gap: 0.55rem;
            padding: 10px 8px 8px;
            margin-inline-start: auto;
            box-sizing: border-box;
        }

        .fi-fo-cloud-file-links__empty {
            width: 100%;
            padding: 1rem 0.75rem;
            text-align: center;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: rgb(107 114 128);
            box-sizing: border-box;
        }

        .fi-fo-cloud-file-links__add {
            display: flex;
            justify-content: center;
            padding: 0.5rem 0.75rem;
        }

        .dark .fi-fo-cloud-file-links__row {
            border-color: rgb(255 255 255 / 0.1);
        }

        .dark .fi-fo-cloud-file-links__link-empty,
        .dark .fi-fo-cloud-file-links__empty {
            color: rgb(156 163 175);
        }
    </style>
@endonce

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
    class="fi-fo-key-value-wrp"
>
    <x-slot
        name="label"
        @class([
            'sm:pt-1.5' => $hasInlineLabel,
        ])
    >
        {{ $getLabel() }}
    </x-slot>

    <x-filament::input.wrapper
        :disabled="$isDisabled"
        :valid="! $errors->has($statePath)"
        :attributes="
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class(['fi-fo-cloud-file-links'])
        "
    >
        <div
            {{
                $attributes
                    ->merge($getExtraAlpineAttributes(), escape: false)
                    ->class(['fi-fo-cloud-file-links__table'])
            }}
            wire:key="{{ $this->getId() }}.{{ $statePath }}.{{ $field::class }}.{{
                substr(md5(serialize([
                    $isDisabled,
                    $rows,
                ])), 0, 64)
            }}"
        >
            @forelse ($rows as $name => $url)
                <div
                    class="fi-fo-cloud-file-links__row"
                    wire:key="{{ $this->getId() }}.{{ $statePath }}.row.{{ md5((string) $name) }}"
                >
                    <div class="fi-fo-cloud-file-links__link">
                        <x-filament::icon
                            :icon="$getFileIcon((string) $name)"
                            class="fi-fo-cloud-file-links__file-icon"
                        />

                        @if (filled($url))
                            <a
                                href="{{ $url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                {{ $name }}
                            </a>
                        @else
                            <span class="fi-fo-cloud-file-links__link-empty">
                                {{ $name }}
                            </span>
                        @endif
                    </div>

                    @if ($showActions)
                        <div class="fi-fo-cloud-file-links__actions">
                            @if ($isEditable && $editAction)
                                {{ \FilamentCloudFileLinks\Support\FilamentVersion::actionWithArguments($editAction, ['key' => $name]) }}
                            @endif

                            @if ($isDeletable && $deleteAction)
                                {{ \FilamentCloudFileLinks\Support\FilamentVersion::actionWithArguments($deleteAction, ['key' => $name]) }}
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="fi-fo-cloud-file-links__empty">
                    {{ $getEmptyLabel() }}
                </div>
            @endforelse

            @if ($isAddable && (! $isDisabled))
                <div class="fi-fo-cloud-file-links__add">
                    {{ $getAction('add') }}
                </div>
            @endif
        </div>
    </x-filament::input.wrapper>
</x-dynamic-component>
