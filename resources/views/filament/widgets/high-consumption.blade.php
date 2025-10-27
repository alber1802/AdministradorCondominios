<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-warning-500" />
                Alto Consumo - {{ $currentMonth }}
            </div>
        </x-slot>

        <div class="space-y-4">
            @if($hasData)
                {{-- Consumption Alerts --}}
                @if($alerts->isNotEmpty())
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <x-heroicon-o-bell class="h-4 w-4 text-danger-500" />
                            Alertas Activas
                        </h4>
                        <div class="space-y-2">
                            @foreach($alerts as $alert)
                                <div class="flex items-center justify-between p-2 rounded-lg bg-danger-50 dark:bg-danger-900/20 border border-danger-200 dark:border-danger-800">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-danger-900 dark:text-danger-100">
                                            {{ $alert['departamento'] }}
                                        </p>
                                        <p class="text-xs text-danger-700 dark:text-danger-300">
                                            {{ $alert['tipo'] }}: {{ number_format($alert['lectura'], 2) }} - ${{ number_format($alert['costo'], 2) }}
                                        </p>
                                    </div>
                                    <div class="text-xs text-danger-600 dark:text-danger-400">
                                        {{ $alert['fecha']->format('d/m') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Top Consumers --}}
                @if($highConsumption->isNotEmpty())
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <x-heroicon-o-chart-bar class="h-4 w-4 text-primary-500" />
                            Mayor Consumo del Mes
                        </h4>
                        <div class="space-y-2">
                            @foreach($highConsumption as $index => $consumption)
                                @php
                                    $isAboveAverage = $consumption['total_costo'] > $averageConsumption;
                                    $badgeColor = match($index) {
                                        0 => 'danger',
                                        1 => 'warning', 
                                        2 => 'info',
                                        default => 'gray'
                                    };
                                @endphp
                                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <x-filament::badge color="{{ $badgeColor }}" size="sm">
                                            #{{ $index + 1 }}
                                        </x-filament::badge>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $consumption['departamento'] }}
                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ $consumption['propietario'] }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="text-sm font-semibold {{ $isAboveAverage ? 'text-danger-600 dark:text-danger-400' : 'text-gray-900 dark:text-gray-100' }}">
                                            ${{ number_format($consumption['total_costo'], 2) }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">
                                            {{ number_format($consumption['total_lectura'], 2) }} unidades
                                        </p>
                                        @if($isAboveAverage)
                                            <p class="text-xs text-danger-600 dark:text-danger-400">
                                                Sobre promedio
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($averageConsumption > 0)
                            <div class="mt-3 p-2 rounded bg-info-50 dark:bg-info-900/20 border border-info-200 dark:border-info-800">
                                <p class="text-xs text-info-700 dark:text-info-300">
                                    <x-heroicon-o-information-circle class="h-3 w-3 inline mr-1" />
                                    Consumo promedio del mes: ${{ number_format($averageConsumption, 2) }}
                                </p>
                            </div>
                        @endif
                    </div>
                @endif
            @else
                <div class="text-center py-6">
                    <x-heroicon-o-chart-bar-square class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @if(isset($error))
                            {{ $error }}
                        @else
                            No hay datos de consumo para mostrar
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>