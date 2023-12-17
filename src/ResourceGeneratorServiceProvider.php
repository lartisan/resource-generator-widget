<?php

namespace Lartisan\ResourceGenerator;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ResourceGeneratorServiceProvider extends PackageServiceProvider
{
    public static string $name = 'resource-generator-widget';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasViews();
    }
}
