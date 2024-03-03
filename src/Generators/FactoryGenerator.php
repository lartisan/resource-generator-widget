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

        foreach ($columns as $index => $column) {
            $columnName = data_get($column, 'column_name');
            $factoryType = data_get($column, 'factory_type');

            if (! $factoryType) {
                continue;
            }

            if ($this->isMethod($factoryType)) {
                $string .= $this->buildMethodWithParameters($factoryType, $column);
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

    private function buildMethodWithParameters(string $factoryType, array $column): ?string
    {
        $parameters = collect();
        $columnName = data_get($column, 'column_name');
        $defaultParams = $this->getDefaultParametersFor($factoryType);

        foreach ($defaultParams as $key => $defaultValue) {
            $inputValue = data_get($column, $key);

            if ($inputValue != $defaultValue) {
                $parameters->push($this->getDefaultKeyValuePairs($key, $inputValue));
            }
        }

        if ($parameters->isEmpty()) {
            return null;
        }

        $parameters = $parameters->implode(', ');

        return <<<PHP
                '$columnName' => fake()->$factoryType($parameters),
                PHP;
    }

    private function getDefaultParametersFor($factoryType): array
    {
        return collect(config('resource-generator-widget.factory.faker_types'))
            ->filter(fn ($type, $key) => $key === $factoryType && is_array($type))
            ->first();
    }

    private function getDefaultKeyValuePairs(string $key, mixed $value): float|int|string
    {
        if (is_numeric($value)) {
            return "$key: ".$value;
        }

        if ($value === true || $value === 'true') {
            return "$key: true";
        }

        if ($value === false || $value === 'false') {
            return "$key: false";
        }

        if ($value === null || $value === 'null') {
            return "$key: null";
        }

        return "'$key:".$value."'";
    }
}
