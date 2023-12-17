<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <div class="flex flex-row items-center gap-x-3">
                <x-filament::icon
                        icon="heroicon-o-square-3-stack-3d"
                        class="w-8 h-8"
                        size="sm"
                />

                <div class="flex-1">
                    <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        {{ __('Filament Resource Generator') }}
                    </h2>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Generates all the necessary files to have a fully functional resource') }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col items-end gap-y-1">
                {{ $this->generate }}
            </div>
        </div>

        <x-filament-actions::modals />
    </x-filament::section>
</x-filament-widgets::widget>
