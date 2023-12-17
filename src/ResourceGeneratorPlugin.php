<?php

namespace Lartisan\ResourceGenerator;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ResourceGeneratorPlugin implements Plugin
{
    public function getId(): string
    {
        return 'resource-generator-widget';
    }

    public function register(Panel $panel): void
    {
        $panel->widgets([
            ResourceGeneratorWidget::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
