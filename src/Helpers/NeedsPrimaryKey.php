<?php

namespace Lartisan\ResourceGenerator\Helpers;

trait NeedsPrimaryKey
{
    public function getPrimaryKey(array $data): ?array
    {
        return collect(data_get($data, 'database_columns'))
            ->filter(fn ($column) => $this->isPrimaryKey($column))
            ->first();
    }

    public function hasPrimaryKey(array $data): bool
    {
        return $this->getPrimaryKey($data) !== null;
    }

    public function isPrimaryKey(array $column): bool
    {
        return data_get($column, 'is_primary_key', false);
    }
}
