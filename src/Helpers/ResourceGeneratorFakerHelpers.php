<?php

namespace Lartisan\ResourceGenerator\Helpers;

use Filament\Forms;
use Lartisan\ResourceGenerator\Traits\Resolvable;

class ResourceGeneratorFakerHelpers
{
    use Resolvable;

    public function __call(string $name, array $attributes): array
    {
        $components = collect();

        foreach (data_get($attributes, '0', []) as $field => $defaultValue) {
            $component = $this->baseComponent($field, $defaultValue)
                ->label($field)
                ->visible(fn (Forms\Get $get) => $get('factory_type') === $name);

            $components->push($component);
        }

        return $components->toArray();
    }

    // Base methods
    private function baseComponent(
        int|string $field,
        mixed $defaultValue
    ): Forms\Components\TagsInput|Forms\Components\Toggle|Forms\Components\TextInput {
        return match (true) {
            is_bool($defaultValue) => $this->toggleComponent($field),
            is_numeric($defaultValue) => $this->numericInputComponent($field),
            //            is_array($defaultValue) => $this->tagsInputComponent($field),
            default => $this->textInputComponent($field),
        };
    }

    private function toggleComponent(int|string $field): Forms\Components\Toggle
    {
        return Forms\Components\Toggle::make($field)
            ->inline(false)
            ->columnSpan(2);
    }

    private function numericInputComponent(int|string $field): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($field)
            ->numeric()
            ->columnSpan(2);
    }

    private function tagsInputComponent(int|string $field): Forms\Components\TagsInput
    {
        return Forms\Components\TagsInput::make($field)
            ->separator()
            ->helperText('Comma separated values')
            ->columnSpan(3);
    }

    private function selectComponent(int|string $field, array $defaultValue)
    {
        return Forms\Components\Select::make($field)
            ->options($defaultValue)
            ->columnSpan(2);
    }

    private function textInputComponent(int|string $field): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($field)
            ->columnSpan(2);
    }
}
