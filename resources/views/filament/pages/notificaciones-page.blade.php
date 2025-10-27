<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header con estadísticas -->
        <div class="grid grid-cols-1 gap-6">
            @foreach ($this->getHeaderWidgets() as $widget)
                @livewire($widget)
            @endforeach
        </div>

        <!-- Tabla de notificaciones -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>