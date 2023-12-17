<?php

namespace Lartisan\ResourceGenerator\Helpers;

class ModelHelpers
{
    use NeedsPrimaryKey;

    public function isFillableField(array $column)
    {
        return data_get($column, 'is_fillable_column', false);
    }

    public function isCastableColumn(array $column)
    {
        return data_get($column, 'is_castable_column', false);
    }

    public function isExcluded(array $column): bool
    {
        return in_array(
            data_get($column, 'column_name'),
            config('resource-generator-widget.model.not_fillable_fields')
        );
    }
}