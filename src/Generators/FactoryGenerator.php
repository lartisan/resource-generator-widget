<?php

namespace Lartisan\ResourceGenerator\Generators;

class FactoryGenerator extends BaseGenerator
{
    public function handle(array $data): void
    {
        $rawName = data_get($data, 'factory_name');

        $className = str($rawName)
            ->replace('/', '\\')
            ->afterLast('\\')
            ->studly();

        $namespace = str($rawName)->replace('/', '\\')->explode('\\')->count() > 1
            ? str($rawName)->replace('/', '\\')->before($className)->substr(0, -1)
            : 'Database\\Factories';

        $this->createFactory($className, $namespace, $data);
    }

    private function createFactory(string $className, string $namespace, array $data): void
    {
        $factoryStub = 'factory';
        $factoryData = [
            'factoryNamespace' => $namespace,
            'factory' => $className,
            'columns' => $this->setFactoryColumns(data_get($data, 'factory_fields')),
        ];

        $targetPath = database_path('factories/'.$className.'.php');

        $this->copyStubToApp($factoryStub, $targetPath, $factoryData);
    }

    private function setFactoryColumns(array $columns): string
    {
        $string = '';

        foreach ($columns as $index => $columnData) {
            $columnName = data_get($columnData, 'column_name');
            $factoryType = data_get($columnData, 'factory_type');

            if ($this->isMethod($factoryType)) {
                $string .= $this->buildMethodWithParameters($factoryType, $columnData);
            } else {
                $string .= <<<PHP
                    '$columnName' => fake()->$factoryType,
                    PHP;
            }

            if ($index < count($columns) - 1) {
                $string .= "\n\t\t\t";
            }
        }

        return $string;
    }

    private function isMethod($factoryType): bool
    {
        return collect(config('resource-generator-widget.factory.faker_types'))
            ->filter(fn ($type, $key) => $key === $factoryType && is_array($type))
            ->count() > 0;
    }

    private function getDefaultParametersFor($factoryType): array
    {
        return collect(config('resource-generator-widget.factory.faker_types'))
            ->filter(fn ($type, $key) => $key === $factoryType && is_array($type))
            ->first();
    }

    private function buildMethodWithParameters(string $factoryType, array $columnData): string
    {
        $parameters = collect();
        $columnName = data_get($columnData, 'column_name');

        foreach ($this->getDefaultParametersFor($factoryType) as $key => $value) {
            if ($columnData[$key] !== null) {
                $value = $columnData[$key];
            }

            $value = match (true) {
                is_numeric($value) && intval($value) == $value => intval($value),
                $value === null || $value === 'null' => <<<PHP
                                                        null
                                                        PHP,
                $value === true || $value == 'true' => <<<PHP
                                                        true
                                                        PHP,
                $value === false || $value == 'false' => <<<PHP
                                                        false
                                                        PHP,
                default => str($value)->wrap("'"),
            };

            $parameters->put($key, $value);
        }

        $parameters = $parameters->implode(', ');

        return <<<PHP
                '$columnName' => fake()->$factoryType($parameters),
                PHP;
    }
}
