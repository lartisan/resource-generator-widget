<?php

namespace Lartisan\ResourceGenerator\Generators;

use Illuminate\Support\Facades\Artisan;
use Lartisan\ResourceGenerator\Helpers\MigrationHelpers;

class MigrationGenerator extends BaseGenerator
{
    public function __construct(
        private readonly MigrationHelpers $helpers,
    ) {
    }

    public function handle(array $data): void
    {
        $tableName = str($data['table_name'])->plural()->snake();
        $targetPath = database_path('migrations/'.now()->format('Y_m_d_His').'_create_'.$tableName.'_table.php');

        $this->copyStubToApp('migration.create', $targetPath, [
            'table' => $tableName,
            'columns' => $this->setMigrationColumns(data_get($data, 'database_columns')),
        ]);

        if (data_get($data, 'run_migrations')) {
            Artisan::call('migrate');
        }
    }

    public function setMigrationColumns(array $columns): string
    {
        $string = '';

        foreach ($columns as $index => $column) {
            $columnType = data_get($column, 'data_type');
            $columnName = data_get($column, 'column_name');
            $defaultValues = $this->helpers->getColumnDefaultValues($column);
            $modifiers = $this->helpers->setModifiers($column);

            $string .= <<<PHP
                    \$table->$columnType('$columnName'$defaultValues)$modifiers;
                    PHP;

            if ($index < count($columns) - 1) {
                $string .= "\n\t\t\t";
            }
        }

        return $string;
    }
}
