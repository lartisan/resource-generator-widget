<?php

namespace Lartisan\ResourceGenerator;

use Filament\Notifications\Notification;
use Lartisan\ResourceGenerator\Generators\FactoryGenerator;
use Lartisan\ResourceGenerator\Generators\FilamentResourceGenerator;
use Lartisan\ResourceGenerator\Generators\MigrationGenerator;
use Lartisan\ResourceGenerator\Generators\ModelGenerator;
use Lartisan\ResourceGenerator\Generators\SeederGenerator;
use Lartisan\ResourceGenerator\Traits\Resolvable;

class ResourceGeneratorManager
{
    use Resolvable;

    public function handle(array $data): void
    {
        MigrationGenerator::make()->handle($data);

//        ModelGenerator::make()->handle($data);

        if (data_get($data, 'create_factory', false)) {
            FactoryGenerator::make()->handle($data);

            if (data_get($data, 'create_seeder', false)) {
                SeederGenerator::make()->handle($data);
            }
        }

//        FilamentResourceGenerator::make()->handle($data);

        Notification::make()
            ->success()
            ->title('Resource generated!')
            ->body('Your resource has been generated successfully.')
            ->send();
    }
}
