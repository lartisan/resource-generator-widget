<?php

namespace Lartisan\ResourceGenerator\Fields;

use Lartisan\ResourceGenerator\Traits\Resolvable;
use Lartisan\ResourceGenerator\Helpers\ResourceGeneratorFakerHelpers;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class FactoryStepFields
{
    use Resolvable;

    public function generate()
    {
        return Forms\Components\Wizard\Step::make('Factory')
            ->columns()
            ->icon('heroicon-o-wrench-screwdriver')
            ->description('Create the factory for the model')
            ->schema([
                Forms\Components\Toggle::make('create_factory')
                    ->lazy()
                    ->default(true)
                    ->inline(false),

                Forms\Components\Group::make()
                    ->columnSpan(1)
                    ->columns(4)
                    ->visible(fn (Forms\Get $get) => $get('create_factory') === true)
                    ->schema([
                        Forms\Components\Toggle::make('create_seeder')
                            ->columnSpan(1)
                            ->lazy()
                            ->default(false)
                            ->inline(false),

                        Forms\Components\TextInput::make('seeders_count')
                            ->numeric()
                            ->visible(fn (Forms\Get $get) => $get('create_seeder') === true)
                            ->rule('min:1')
                            ->required(),

                        /* TODO: Add this feature */
                        /*Forms\Components\Toggle::make('seed_database')
                            ->columnSpan(1)
                            ->lazy()
                            ->default(false)
                            ->disabled(fn (Forms\Get $get) => $get('create_seeder') === false)
                            ->inline(false),*/
                    ]),

                Forms\Components\Group::make()
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        Forms\Components\TextInput::make('factory_name')
                            ->visible(fn (Forms\Get $get) => $get('create_factory') === true)
                            ->label('Factory name')
                            ->required(fn (Forms\Get $get) => $get('create_factory') === true)
                            ->columnSpan(2)
                            ->hintColor('primary')
                            ->hint(
                                fn () => new HtmlString(
                                    'The name should follow the <a href="https://laravel.com/docs/8.x/database-testing#factory-and-model-discovery-conventions" target="_blank" class="underline">Laravel conventions</a>'
                                )
                            )
                            ->helperText('The factory name is auto-generated from the model name')
                            ->placeholder('E.g.: MyModelFactory'),

                        Forms\Components\TextInput::make('seeder_name')
                            ->visible(fn (Forms\Get $get) => $get('create_seeder') === true)
                            ->label('Seeder name')
                            ->required(fn (Forms\Get $get) => $get('create_seeder') === true)
                            ->columnSpan(2)
                            ->hintColor('primary')
                            ->helperText('The seeder name is auto-generated from the model name')
                            ->placeholder('E.g.: MyModelSeeder'),
                    ]),

                Forms\Components\Repeater::make('factory_fields')
                    ->visible(fn (Forms\Get $get) => $get('create_factory') === true)
                    ->label('Factory fields')
                    ->addable(false)
                    ->columnSpanFull()
                    ->columns(12)
                    ->itemLabel(fn (array $state): ?string => str($state['column_name'])->headline() ?? null)
                    ->live()
                    ->deletable(false)
                    ->schema([
                        Forms\Components\TextInput::make('column_name')
                            ->label('Column name')
                            ->columnSpan(3)
                            ->readOnly()
                            ->lazy()
                            ->default(function (Forms\Get $get, Component $component) {
                                $state = collect($component->getContainer()->getComponents())
                                    ->filter(function (Component $component) {
                                        return $component->getStatePath() == "mountedActionsData.0.factory_fields";
                                    })
                                    ->last()
                                    ?->getState();

                                return $state['column_name'] ?? null;
                            }),

                        Forms\Components\Select::make('factory_type')
                            ->label('Factory type')
                            ->columnSpan(3)
                            ->options(
                                $this->setFactoryTypeOptions()
                            )
                            ->live()
                            ->searchable()
                            ->required(),

                        ...$this->addFieldsForFakerTypes(),
                    ])
            ]);
    }


    private function setFactoryTypeOptions(): array
    {
        $factoryTypes = collect(config('resource-generator-widget.factory.faker_types'));

        return $factoryTypes->mapWithKeys(function ($item, $key) {
            return [$key => $key];
        })->toArray();
    }

    private function addFieldsForFakerTypes(): array
    {
        $factoryTypes = collect(config('resource-generator-widget.factory.faker_types'));

        return $this->generateComponents($factoryTypes);
    }

    private function generateComponents(Collection $factoryTypes): array
    {
        $components = collect();

        foreach ($factoryTypes as $method => $attributes) {
            if (is_array($attributes)) {
                $components->push(
                    ...ResourceGeneratorFakerHelpers::make()->{$method}($attributes)
                );
            }
        }

        return $components->toArray();
    }
}