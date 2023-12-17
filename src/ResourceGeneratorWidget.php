<?php

namespace Lartisan\ResourceGenerator;

use Lartisan\ResourceGenerator\Fields\MigrationStepFields;
use Lartisan\ResourceGenerator\Fields\FactoryStepFields;
use Lartisan\ResourceGenerator\Fields\FilamentResourceStepFields;
use Lartisan\ResourceGenerator\Fields\ModelStepFields;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets\Widget;

class ResourceGeneratorWidget extends Widget implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static string $view = 'resource-generator-widget::resource-generator-widget';

    public function generate()
    {
        return Action::make('generate')
            ->modalHeading('Generate a new resource')
            ->modalDescription(
                'This operation will create the model, the migration, the factory and the Filament resource'
            )
            ->label('Generate')
            ->icon('heroicon-m-plus')
            ->color('gray')
            ->steps([
                MigrationStepFields::make()->generate(),
                ModelStepFields::make()->generate(),
                FactoryStepFields::make()->generate(),
                FilamentResourceStepFields::make()->generate(),
            ])
            ->action(fn(array $data) => ResourceGeneratorManager::make()->handle($data))
            ->modalWidth(MaxWidth::SixExtraLarge);
    }
}
