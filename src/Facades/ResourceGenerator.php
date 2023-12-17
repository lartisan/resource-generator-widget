<?php

namespace Lartisan\ResourceGenerator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lartisan\ResourceGenerator\ResourceGeneratorWidget
 */
class ResourceGenerator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Lartisan\ResourceGenerator\ResourceGeneratorWidget::class;
    }
}
