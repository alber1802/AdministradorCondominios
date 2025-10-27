<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-calendar-days class="h-5 w-5 text-success-500" />
                Reservas y Disponibilidad
            </div>
        </x-slot>

        <div class="space-y-4">
            @if($hasData || $areasStatus->isNotEmpty())
                {{-- Quick Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                    <div class="text-center p-2 rounded-lg bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800">
                        <p class="text-lg font-bold text-success-700 dark:text-success-300">{{ $stats['total_activas'] }}</p>
                        <p class="text-xs text-success-600 dark:text-success-400">Activas</p>
                    </div>
                    <div class="text-center p-2 rounded-lg bg-info-50 dark:bg-info-900/20 border border-info-200 dark:border-info-800">
                        <p class="text-lg font-bold text-info-700 dark:text-info-300">{{ $stats['total_proximas'] }}</p>
                        <p class="text-xs text-info-600 dark:text-info-400">Próximas</p>
                    </div>
                    <div class="text-center p-2 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <p class="text-lg font-bold text-success-700 dark:text-success-300">{{ $stats['areas_disponibles'] }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Disponibles</p>
                    </div>
                    <div class="text-center p-2 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <p class="text-lg font-bold text-warning-700 dark:text-warning-300">{{ $stats['areas_ocupadas'] }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Ocupadas</p>
                    </div>
                </div>

                {{-- Active Reservations --}}
                @if($activeReservations->isNotEmpty())
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <x-heroicon-o-clock class="h-4 w-4 text-success-500" />
                            Reservas Activas
                        </h4>
                        <div class="space-y-2">
                            @foreach($activeReservations as $reserva)
                                <div class="flex items-center justify-between p-3 rounded-lg bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-success-900 dark:text-success-100">
                                            {{ $reserva['area'] }}
                                        </p>
                                        <p class="text-xs text-success-700 dark:text-success-300">
                                            {{ $reserva['usuario'] }}
                                        </p>
                                        <p class="text-xs text-success-600 dark:text-success-400">
                                            {{ $reserva['fecha_inicio']->format('H:i') }} - {{ $reserva['fecha_fin']->format('H:i') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <x-filament::badge color="success" size="sm">
                                            En uso
                                        </x-filament::badge>
                                        <p class="text-xs text-success-600 dark:text-success-400 mt-1">
                                            Termina {{ $reserva['tiempo_restante'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Upcoming Reservations --}}
                @if($upcomingReservations->isNotEmpty())
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <x-heroicon-o-calendar class="h-4 w-4 text-info-500" />
                            Próximas Reservas (7 días)
                        </h4>
                        <div class="space-y-2">
                            @foreach($upcomingReservations as $reserva)
                                <div class="flex items-center justify-between p-2 rounded-lg bg-info-50 dark:bg-info-900/20 border border-info-200 dark:border-info-800">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-info-900 dark:text-info-100">
                                            {{ $reserva['area'] }}
                                        </p>
                                        <p class="text-xs text-info-700 dark:text-info-300">
                                            {{ $reserva['usuario'] }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-info-600 dark:text-info-400">
                                            {{ $reserva['fecha_inicio']->format('d/m H:i') }}
                                        </p>
                                        <p class="text-xs text-info-500 dark:text-info-500">
                                            {{ $reserva['tiempo_hasta'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Areas Status --}}
                @if($areasStatus->isNotEmpty())
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <x-heroicon-o-building-office class="h-4 w-4 text-primary-500" />
                            Estado de Áreas Comunes
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($areasStatus as $area)
                                <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full {{ $area['disponible'] ? 'bg-success-500' : 'bg-warning-500' }}"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $area['nombre'] }}
                                            </p>
                                            @if($area['reservas_hoy'] > 0)
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    {{ $area['reservas_hoy'] }} reserva(s) hoy
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <x-filament::badge 
                                        color="{{ $area['disponible'] ? 'success' : 'warning' }}" 
                                        size="sm"
                                    >
                                        {{ $area['estado'] }}
                                    </x-filament::badge>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-6">
                    {{-- <x-heroicon-o-calendar-x class="h-12 w-12 text-gray-400 mx-auto mb-3" /> --}}
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @if(isset($error))
                            {{ $error }}
                        @else
                            No hay reservas activas o próximas
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>