<?php

namespace Lartisan\ResourceGenerator\Fields;

use Filament\Forms;
use Lartisan\ResourceGenerator\Helpers\MigrationHelpers;
use Lartisan\ResourceGenerator\Traits\Resolvable;

class ModelStepFields
{
    use Resolvable;

    public function generate()
    {
        return Forms\Components\Wizard\Step::make('Model')
            ->columns()
            ->icon('heroicon-o-document-text')
            ->description('Set the fillable fields')
            ->schema([
                Forms\Components\TextInput::make('model_name')
                    ->label('Model name')
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(
                        function (Forms\Set $set, ?string $state) {
                            $set('factory_name', str($state)->append('Factory'));
                            $set('seeder_name', str($state)->append('Seeder'));
                        }
                    )
                    ->helperText('The model name is auto-generated from the table name')
                    ->placeholder('E.g.: MyModel or App\Models\MyModel'),

                $this->attributesRepeater(),
            ]);
    }

    private function attributesRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('attributes')
            ->label('Fillable attributes')
            ->addable(false)
            ->columnSpanFull()
            ->columns(12)
            ->itemLabel(fn (array $state): ?string => str($state['attribute_name'])->headline() ?? null)
            ->live()
            ->deletable(false)
            ->schema([
                Forms\Components\TextInput::make('attribute_name')
                    ->label('Attribute name')
                    ->columnSpan(4)
                    ->readOnly()
                    ->lazy(),

                Forms\Components\Toggle::make('is_fillable_column')
                    ->label('Fillable')
                    ->columnSpan(1)
                    ->inline(false)
                    ->hidden(function (Forms\Get $get, $component) {
                        $state = collect($get('../../attributes'))
                            ->filter(function ($item, $index) use ($component) {
                                return $component->getStatePath() == "mountedActionsData.0.attributes.{$index}.is_fillable_column";
                            })
                            ->last();

                        return app(MigrationHelpers::class)->isPrimaryKey($state);
                    }),

                Forms\Components\Toggle::make('is_castable_column')
                    ->label('Needs casting')
                    ->live()
                    ->columnSpan(2)
                    ->inline(false)
                    ->hidden(function (Forms\Get $get, $component) {
                        $state = collect($get('../../attributes'))
                            ->filter(function ($item, $index) use ($component) {
                                return $component->getStatePath() == "mountedActionsData.0.attributes.{$index}.is_castable_column";
                            })
                            ->last();

                        return app(MigrationHelpers::class)->isPrimaryKey($state);
                    }),

                Forms\Components\Select::make('cast_type')
                    ->columnSpan(2)
                    ->hidden(fn (Forms\Get $get) => $get('is_castable_column') === false)
                    ->options($this->castableTypes()),

                Forms\Components\TextInput::make('decimal_precision')
                    ->label('Decimal precision')
                    ->columnSpan(2)
                    ->hidden(fn (Forms\Get $get) => $get('cast_type') !== 'decimal:precision'),
            ]);
    }

    private function castableTypes(): array
    {
        return [
            'array' => 'array',
            //            'AsStringable::class',
            'boolean' => 'boolean',
            'collection' => 'collection',
            'date' => 'date',
            'datetime' => 'datetime',
            'immutable_date' => 'immutable_date',
            'immutable_datetime' => 'immutable_datetime',
            'decimal:precision' => 'decimal:precision',
            'double' => 'double',
            'encrypted' => 'encrypted',
            'encrypted:array' => 'encrypted:array',
            'encrypted:collection' => 'encrypted:collection',
            'encrypted:object' => 'encrypted:object',
            'float' => 'float',
            'hashed' => 'hashed',
            'integer' => 'integer',
            'object' => 'object',
            'real' => 'real',
            'string' => 'string',
            'timestamp' => 'timestamp',
        ];
    }
}
