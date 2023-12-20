<?php

namespace Lartisan\ResourceGenerator\Fields;

use Filament\Facades\Filament;
use Filament\Forms;
use Lartisan\ResourceGenerator\Traits\Resolvable;

class FilamentResourceStepFields
{
    use Resolvable;

    public function generate()
    {
        return Forms\Components\Wizard\Step::make('Filament Resource')
            ->columns()
            ->icon('heroicon-o-rectangle-stack')
            ->description('Create the factory for the model')
            ->schema([
                Forms\Components\TextInput::make('resource_name')
                    ->label('Name')
                    ->readOnly(),

                Forms\Components\Select::make('panel')
                    ->label('For Panel')
                    ->options(function () {
                        $options = collect();
                        $panels = Filament::getPanels();

                        foreach ($panels as $name => $panel) {
                            $options->put($name, str($name)->ucfirst()->toString());
                        }

                        return $options->toArray();
                    })
                    ->helperText('Select the panel for this resource')
                    ->required(),

                Forms\Components\Fieldset::make('Flags')
                    ->columns(4)
                    ->schema([
                        Forms\Components\Toggle::make('soft-deletes')
                            ->label('Has Soft Deletes')
                            ->visible(fn (Forms\Get $get) => $get('has_soft_deletes') === true)
                            ->default(false),

                        Forms\Components\Toggle::make('view')
                            ->label('Has View')
                            ->helperText('Check if you need to generate a view for this resource')
                            ->default(false),

                        Forms\Components\Toggle::make('generate')
                            ->label('Generate')
                            ->helperText('Check if you want to generate the resource')
                            ->default(false),

                        Forms\Components\Toggle::make('simple')
                            ->label('Simple')
                            ->helperText('Check if you want to generate a simple resource')
                            ->default(false),
                    ]),
            ]);
    }
}
