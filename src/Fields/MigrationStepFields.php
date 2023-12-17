<?php

namespace Lartisan\ResourceGenerator\Fields;

use Illuminate\Support\Facades\Schema;
use Lartisan\ResourceGenerator\Traits\Resolvable;
use Filament\Forms;

class MigrationStepFields
{
    use Resolvable;

    public function generate()
    {
        return Forms\Components\Wizard\Step::make('Migration')
            ->columns()
            ->icon('heroicon-o-circle-stack')
            ->description('Define the table schema')
            ->schema([
                Forms\Components\Group::make()
                    ->columnSpanFull()
                    ->columns()
                    ->schema([
                        Forms\Components\TextInput::make('table_name')
                            ->lazy()
                            ->autofocus()
                            ->columnSpan(1)
                            ->label('Table name')
                            ->required()
                            ->rules([
                                function (Forms\Get $get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                                        if (Schema::hasTable($value)) {
                                            $fail("The table '$value' already exists.");
                                        }
                                    };
                                }
                            ])
                            ->helperText(fn () => str('The table name should use the **snake_case** convention')->inlineMarkdown()->toHtmlString())
                            ->afterStateUpdated(
                                function (Forms\Set $set, Forms\Components\TextInput $component, ?string $state, $livewire) {
                                    $livewire->validateOnly($component->getStatePath());

                                    $component->state(str($state)->snake()->toString());

                                    $modelName = str($state)->studly()->singular();
                                    $set('model_name', $modelName);
                                    $set('factory_name', $modelName->append('Factory'));
                                    $set('seeder_name', $modelName->append('Seeder'));
                                }
                            )
                            ->placeholder('E.g.: my_table_name'),

                        Forms\Components\Toggle::make('run_migrations')
                            ->label('Run the migration?')
                            ->lazy()
                            ->helperText(
                                fn (bool $state) => $state === true
                                    ? 'Uncheck if you want to double check the migration before running it.'
                                    : str('Check if you want to **run the migration** after submitting the form.')->inlineMarkdown()->toHtmlString()
                            )
                            ->default(false)
                            ->columnSpan(1)
                            ->inline(false),
                    ]),

                /*$this->indexesGroup(),*/ // todo: Phase 2 - Add composite indexes?

                $this->databaseColumnsRepeater()
            ])
            ->afterValidation(function (Forms\Get $get, Forms\Set $set) {
                $databaseColumns = $get('database_columns');

                $this->mutateFillableFields($databaseColumns, $set);
                $this->mutateFactoryColumns($databaseColumns, $set);
                $set('resource_name', str($get('table_name'))->studly()->singular()->append('Resource'));
            });
    }


    // todo: Phase 2 - Add composite indexes?
    private function indexesGroup(): Forms\Components\Fieldset
    {
        return Forms\Components\Fieldset::make('Indexes')
            ->columnSpanFull()
            ->columns(3)
            ->schema([
                Forms\Components\Group::make()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Toggle::make('is_primary_key')
                            ->label('Primary Key(s)')
                            ->lazy()
                            ->default(true)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_unique')
                            ->label('Unique')
                            ->lazy(),

                        Forms\Components\Toggle::make('has_index')
                            ->label('Index')
                            ->lazy(),
                    ]),

                Forms\Components\Group::make()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('primary_key_values')
                            ->label(false)
                            ->placeholder('E.g.: column,another_column,...')
                            ->disabled(fn(Forms\Get $get) => $get('is_primary_key') === false)
                            ->helperText('Comma separated values for multiple columns'),

                        Forms\Components\TextInput::make('unique_values')
                            ->label(false)
                            ->placeholder('E.g.: column,another_column,...')
                            ->disabled(fn(Forms\Get $get) => $get('is_unique') === false)
                            ->helperText('Comma separated values for multiple columns'),

                        Forms\Components\TextInput::make('index_values')
                            ->label(false)
                            ->placeholder('E.g.: column,another_column,...')
                            ->disabled(fn(Forms\Get $get) => $get('has_index') === false)
                            ->helperText('Comma separated values for multiple columns'),
                    ]),
            ]);

    }

    private function databaseColumnsRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('database_columns')
            ->label('Database column names')
            ->addActionLabel('Add column')
            ->columnSpanFull()
            ->columns(16)
            ->itemLabel(fn(array $state): ?string => str($state['column_name'])->headline() ?? null)
            ->live() // Important to make the state available to the model and factory steps
            ->schema([
                // DataType Group
                Forms\Components\Group::make()
                    ->columnSpanFull()
                    ->columns(12)
                    ->schema([
                        Forms\Components\Select::make('data_type')
                            ->label('Data type')
                            ->lazy()
                            ->columnSpan(4)
                            ->options(config('resource-generator-widget.database.column_types'))
                            ->searchable()
                            ->required(),

                        Forms\Components\Group::make()
                            ->columnSpan(8)
                            ->columns(4)
                            ->schema(
                                fn (Forms\Get $get) => $this->buildParamsGroup($get('data_type'))
                            ),
                    ]),

                // Column Group
                Forms\Components\Group::make()
                    ->columnSpanFull()
                    ->columns(12)
                    ->schema([
                        Forms\Components\TextInput::make('column_name')
                            ->label('Column name')
                            ->columnSpan(4)
                            ->hidden(fn(Forms\Get $get) => $this->isColumnWithNoParams($get('data_type')))
                            ->lazy()
                            ->required(),

                        // Modifiers & Indexes Group
                        Forms\Components\Group::make()
                            ->columnSpan(8)
                            ->columns(8)
                            ->hidden(fn(Forms\Get $get) => $this->isColumnWithVoidReturn($get('data_type')))
                            ->schema([
                                // Index Types: Primary Key
                                Forms\Components\Toggle::make('is_primary_key')
                                    /*->afterStateUpdated(fn (Forms\Get $get, Forms\Set $set) => $set('is_unique', $get('is_primary_key')))*/
                                    /*->disabled(function (Forms\Get $get, Forms\Components\Toggle $component) {
                                        $repeaterColumns = collect($get('../../database_columns'));
                                        $statePath = str($component->getContainer()->getStatePath())
                                            ->afterLast('.')
                                            ->toString();

                                        return $repeaterColumns[$statePath]['is_primary_key'] !== true
                                            && $repeaterColumns
                                                ->filter(fn ($column) => $column['is_primary_key'] === true)
                                                ->count() > 0;
                                    })
                                    ->live()*/
                                    ->lazy()
                                    ->rules([
                                        function (Forms\Get $get) {
                                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                $hasNoPrimaryKey = collect($get('../../database_columns'))
                                                        ->filter(fn ($column) => $column['is_primary_key'] === true)
                                                        ->count() === 0;

                                                if ($value === false && $hasNoPrimaryKey) {
                                                    $fail('A column with primary key is required.');
                                                }
                                            };
                                        }
                                    ])
                                    ->fixIndistinctState()
                                    ->label('Primary')
                                    ->columnSpan(1)
                                    ->disabled(
                                        fn (Forms\Get $get) => $get('is_unique') === true
                                            || $get('has_index') === true
                                            || $get('is_nullable') === true
                                            || $get('is_unsigned') === true
                                    )
                                    ->inline(false),

                                // Index Types: Unique
                                Forms\Components\Toggle::make('is_unique')
                                    ->lazy()
                                    ->label('Unique')
                                    ->columnSpan(1)
                                    ->disabled(
                                        fn (Forms\Get $get) => $get('is_primary_key') === true
                                            || $get('has_index') === true
                                            || $get('is_nullable') === true
                                    )
                                    ->inline(false),

                                // Index Types: Index
                                Forms\Components\Toggle::make('has_index')
                                    ->label('Index')
                                    ->lazy()
                                    ->columnSpan(1)
                                    ->disabled(
                                        fn (Forms\Get $get) => $get('is_primary_key') === true
                                            || $get('is_unique') === true
                                    )
                                    ->inline(false),

                                // Modifiers: Nullable
                                Forms\Components\Toggle::make('is_nullable')
                                    ->label('Nullable')
                                    ->lazy()
                                    ->columnSpan(1)
                                    ->disabled(
                                        fn (Forms\Get $get) => $get('is_primary_key') === true
                                            || $get('is_unique') === true
                                    )
                                    ->inline(false),

                                // Modifiers: Unsigned
                                Forms\Components\Toggle::make('is_unsigned')
                                    ->label('Unsigned')
                                    ->lazy()
                                    ->columnSpan(1)
                                    ->disabled(
                                        fn (Forms\Get $get) => $get('is_primary_key') === true
                                    )
                                    ->inline(false),

                                // Modifiers: Default
                                Forms\Components\Toggle::make('has_default')
                                    ->label('Default')
                                    ->lazy()
                                    ->columnSpan(1)
                                    ->disabled(
                                        fn (Forms\Get $get) => $get('is_primary_key') === true
                                    )
                                    ->inline(false),

                                Forms\Components\TextInput::make('default_value')
                                    ->label('Default value')
                                    ->columnSpan(2)
                                    ->hidden(fn(Forms\Get $get) => $get('has_default') === false)
                                    ->disabled(
                                        fn (Forms\Get $get) => $get('is_primary_key') === true
                                    )
                                    ->lazy()
                                    ->placeholder('E.g.: 0,1,2,...'),
                            ]),
                    ])
            ])
            ->cloneable()
            ->collapsible()
            ->minItems(1);
    }

    // Helpers
    private function mutateFillableFields(mixed $databaseColumns, Forms\Set $set): void
    {
        $attributes = collect();

        collect($databaseColumns)
            ->pluck('is_primary_key', 'column_name')
            ->each(function ($isPrimaryKey, $columnName) use ($attributes) {
                $attributes->push([
                    'attribute_name' => $columnName,
                    'is_primary_key' => $isPrimaryKey,
                    'is_fillable_column' => false,
                    'is_castable_column' => false,
                ]);
            });

        $set('attributes', $attributes->toArray());
    }

    private function mutateFactoryColumns(mixed $databaseColumns, Forms\Set $set): void
    {
        $attributes = collect();
        $defaultFields = collect();

        collect(config('resource-generator-widget.factory.faker_types'))
            ->keys()
            ->map(fn($item) => $defaultFields->put($item, null))
            ->toArray();

        collect($databaseColumns)
            ->pluck('column_name')
            ->each(fn($columnName) => $attributes->push([
                'column_name' => $columnName,
                'factory_type' => null,
                ...$defaultFields
            ]));

        $set('factory_fields', $attributes->toArray());
    }

    private function isColumnWithNoParams(?string $column = null): bool
    {
        if ($column === null) {
            return false;
        }

        return in_array(
            $column,
            config('resource-generator-widget.database.columns_with_no_params')
        );
    }

    private function isColumnWithVoidReturn(?string $column = null): bool
    {
        if ($column === null) {
            return false;
        }

        return in_array(
            $column,
            config('resource-generator-widget.database.columns_with_return_void')
        );
    }

    private function buildParamsGroup(?string $dataType): array
    {
        if ($dataType === null) {
            return [];
        }

        $fields = collect();

        foreach($this->getParamsForDataType($dataType) as $field => $value) {
            if (is_bool($value)) {
                ray([$field => $value]);
            }
            match (true) {
                is_bool($value) => $fields->push(
                    Forms\Components\Checkbox::make('params.' . $field)
                        ->label(str($field)->title())
                        ->default($value)
                        ->lazy()
                        ->inline(false)
                ),
                default => $fields->push(
                    Forms\Components\TextInput::make('params.' . $field)
                        ->label(str($field)->title())
                        ->placeholder('Default: ' . $value)
                )
            };
        }

        return $fields->toArray();
    }

    private function getParamsForDataType(string $dataType): array
    {
        return collect(config('resource-generator-widget.database.columns_with_default_values'))
            ->get($dataType) ?? [];
    }
}