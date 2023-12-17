<?php

namespace Lartisan\ResourceGenerator\Helpers;

class MigrationHelpers
{
    use NeedsPrimaryKey;

    public function getColumnDefaultValues(array $column): ?string
    {
        $params = collect(data_get($column, 'params', []));

        if ($params->isEmpty()) {
            return null;
        }

        $string = collect();

        ray($params);
        foreach ($params as $key => $value) {
            if (is_null($value)) {
                continue;
            }

            $dataType = data_get($column, 'data_type');

            if ($value != $this->defaultParamForDataType($dataType, $key)) {
                $string->push($this->setDefaultValue($key, $value));
            }
        }

        if ($string->isEmpty()) {
            return null;
        }

        return ', '.$string->implode(', ');
    }

    public function setModifiers(array $column): ?string
    {
        $modifiers = '';

        if ($this->isPrimaryKey($column)) {
            $modifiers .= '->primary()';

            return $modifiers;
        }

        foreach ($column as $field => $value) {
            if ($value === true && in_array($field, $this->getModifiers())) {
                $methodName = str($field)->afterLast('_');
                $defaultValue = null;

                if ($field === 'has_default') {
                    $defaultValue = data_get($column, 'default_value');
                    $defaultValue = match (true) {
                        is_numeric($defaultValue) => $defaultValue,
                        $defaultValue === true || $defaultValue === 'true' => <<<'PHP'
                                                                            true
                                                                            PHP,
                        $defaultValue === false || $defaultValue === 'false' => <<<'PHP'
                                                                            false
                                                                            PHP,
                        $defaultValue === null || $defaultValue === 'null' => <<<'PHP'
                                                                            null
                                                                            PHP,
                        default => str($defaultValue)->wrap("'")
                    };
                }

                $modifiers .= '->'.$methodName.'('.$defaultValue.')';
            }
        }

        return $modifiers;
    }

    private function getModifiers(): array
    {
        return [
            'is_unique', 'has_index', 'is_nullable', 'is_unsigned', 'has_default',
        ];
    }

    private function setDefaultValue(string $key, mixed $value): float|int|string
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

        return "'$key:".$value."'";
    }

    private function defaultParamForDataType(string $dataType, string $key): mixed
    {
        $defaults = data_get(config('resource-generator-widget'), 'database.columns_with_default_values');

        return data_get($defaults, "$dataType.$key");
    }
}
