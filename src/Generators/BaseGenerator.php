<?php

namespace Lartisan\ResourceGenerator\Generators;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Lartisan\ResourceGenerator\Traits\Resolvable;

class BaseGenerator
{
    use CanManipulateFiles;
    use Resolvable;

    protected function getDefaultStubPath(): string
    {
        return __DIR__.'/../../resources/stubs';
    }
}
