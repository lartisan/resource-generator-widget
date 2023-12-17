<?php

namespace Lartisan\ResourceGenerator\Generators;

use Illuminate\Support\Facades\Artisan;

class FilamentResourceGenerator extends BaseGenerator
{
    public function handle(array $data): void
    {
        $flags = collect();

        collect($data)->only([
            'soft-deletes',
            'view',
            'simple',
            'generate',
        ])->each(function ($value, $key) use ($flags) {
            if ($value) {
                $flags->put("--{$key}", true);
            }
        });

        Artisan::call(
            'make:filament-resource',
            [
                'name' => data_get($data, 'model_name'),
                '--panel' => data_get($data, 'panel'),
                ...$flags->toArray(),
            ],
        );
    }
}
