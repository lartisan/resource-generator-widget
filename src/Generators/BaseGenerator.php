<?php

namespace Lartisan\ResourceGenerator\Generators;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Lartisan\ResourceGenerator\Traits\Resolvable;

class BaseGenerator
{
    use Resolvable;
    use CanManipulateFiles;

    protected function getDefaultStubPath(): string
    {
        return __DIR__ . '/../../resources/stubs';
    }
}