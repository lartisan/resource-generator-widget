<?php

namespace Lartisan\ResourceGenerator\Generators;

use Lartisan\ResourceGenerator\Helpers\ModelHelpers;

class ModelGenerator extends BaseGenerator
{
    public function __construct(
        private readonly ModelHelpers $helpers,
    ) {
    }

    public function handle(array $data): void
    {
        $rawModel = data_get($data, 'model_name');

        $className = str($rawModel)
            ->replace('/', '\\')
            ->afterLast('\\')
            ->studly();

        $namespace = str($rawModel)->replace('/', '\\')->explode('\\')->count() > 1
            ? str($rawModel)->replace('/', '\\')->before($className)->substr(0, -1)
            : 'App\\Models';

        $this->createModel($className, $namespace, $data);
    }

    private function createModel(string $className, string $namespace, array $data): void
    {
        $modelStub = 'model';
        $attributes = data_get($data, 'attributes');

        $modelData = [
            'namespace' => $namespace,
            'class' => $className,
            'fillable' => $this->setFillableAttributes($attributes),
            'castable' => $this->setCastableAttributes($attributes),
            'soft_deletes_import' => $this->setSoftDeletesImport($data),
            'soft_deletes_trait' => $this->setSoftDeletesTrait($data),
            'has_factory_import' => $this->setHasFactoryImport($data),
            'has_factory_trait' => $this->setHasFactoryTrait($data),
        ];

        if ($this->helpers->hasPrimaryKey($data)) {
            $modelStub = 'model.uuids';
            $modelData['primaryKey'] = data_get($this->helpers->getPrimaryKey($data), 'column_name');
        }

        $targetPath = app_path('Models/'.$className.'.php');

        $this->copyStubToApp($modelStub, $targetPath, $modelData);
    }

    private function setFillableAttributes(array $attributes): ?string
    {
        $attributes = collect($attributes)
            ->filter(
                fn ($attribute) => ! $this->helpers->isExcluded($attribute) && $this->helpers->isFillableField($attribute)
            );

        if ($attributes->isEmpty()) {
            return null;
        }

        $string = '[';

        foreach ($attributes as $attribute) {
            $attributeName = data_get($attribute, 'attribute_name');

            $string .= <<<PHP
                    \n\t\t'$attributeName',
                    PHP;
        }

        $string .= "\n\t]";

        return <<<PHP
                \n\n\tprotected \$fillable = $string;
                PHP;
    }

    private function setCastableAttributes(array $attributes): ?string
    {
        $attributes = collect($attributes)
            ->filter(
                fn ($attribute) => $this->helpers->isCastableColumn($attribute)
            );

        if ($attributes->isEmpty()) {
            return null;
        }

        $string = '[';

        foreach ($attributes as $attribute) {
            $attributeName = data_get($attribute, 'attribute_name');
            $castType = data_get($attribute, 'cast_type');

            if ($castType === 'decimal:precision') {
                $castType = 'decimal:'.data_get($attribute, 'decimal_precision');
            }

            $string .= <<<PHP
                    \n\t\t'$attributeName' => '$castType',
                    PHP;
        }

        $string .= "\n\t]";

        return <<<PHP
                \n\n\tprotected \$casts = $string;
                PHP;
    }

    private function setSoftDeletesImport(array $data): string
    {
        $string = <<<PHP
                use Illuminate\Database\Eloquent\SoftDeletes;
                PHP;

        return $this->helpers->hasFactory($data)
            ? $string
            : '';
    }

    private function setSoftDeletesTrait(array $data): string
    {
        $string = <<<PHP
                \n\tuse SoftDeletes;
                PHP;

        return $this->helpers->hasSoftDeletes($data)
            ? $string
            : '';
    }

    private function setHasFactoryImport(array $data): string
    {
        $string = <<<PHP
                use Illuminate\Database\Eloquent\Factories\HasFactory;
                PHP;

        return $this->helpers->hasFactory($data)
            ? $string
            : '';
    }

    private function setHasFactoryTrait(array $data): string
    {
        $string = <<<PHP
                \n\tuse HasFactory;
                PHP;

        return $this->helpers->hasFactory($data)
            ? $string
            : '';
    }
}
