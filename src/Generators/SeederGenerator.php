<?php

namespace Lartisan\ResourceGenerator\Generators;

class SeederGenerator extends BaseGenerator
{
    public function handle(array $data): void
    {
        $rawModelClass = data_get($data, 'model_name');
        $rawSeederClass = data_get($data, 'seeder_name');

        $modelClassName = str($rawModelClass)
            ->replace('/', '\\')
            ->afterLast('\\')
            ->studly();

        $modelNamespace = str($rawModelClass)->replace('/', '\\')->explode('\\')->count() > 1
            ? str($rawModelClass)->replace('/', '\\')->before($modelClassName)->substr(0, -1)
            : 'App\\Models';

        $seederClassName = str($rawSeederClass)
            ->replace('/', '\\')
            ->afterLast('\\')
            ->studly();

        $seederNamespace = str($rawSeederClass)->replace('/', '\\')->explode('\\')->count() > 1
            ? str($rawSeederClass)->replace('/', '\\')->before($seederClassName)->substr(0, -1)
            : 'Database\\Seeders';

        $this->createSeeder($seederClassName, $seederNamespace, $modelClassName, $modelNamespace, $data);
    }


    private function createSeeder(
        string $seederClassName,
        string $seederNamespace,
        string $modelClassName,
        string $modelNamespace,
        array $data
    ): void {
        $factoryStub = 'seeder';
        $factoryData = [
            'seederNamespace' => $seederNamespace,
            'seederClass' => $seederClassName,
            'modelNamespace' => $modelNamespace . '\\' . $modelClassName,
            'modelClass' => $modelClassName,
            'count' => data_get($data, 'seeders_count'),
        ];

        $targetPath = database_path('seeders/' . $seederClassName . '.php');

        $this->copyStubToApp($factoryStub, $targetPath, $factoryData);
    }
}