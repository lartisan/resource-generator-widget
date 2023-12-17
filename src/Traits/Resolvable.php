<?php

namespace Lartisan\ResourceGenerator\Traits;

trait Resolvable
{
    public static function make(array $parameters = []): static
    {
        return app(static::class, $parameters);
    }
}
