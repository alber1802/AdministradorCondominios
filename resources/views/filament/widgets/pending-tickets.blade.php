<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-ticket class="h-5 w-5 text-warning-500" />
                Tickets Pendientes
                @if($stats['total_pendientes'] > 0)
                    <x-filament::badge color="warning" size="sm">
                        {{ $stats['total_pendientes'] }}
                    </x-filament::badge>
                @endif
                @if($hasUrgentTickets)
                    <x-filament::badge color="danger" size="sm">
                        Urgente
                    </x-filament::badge>
                @endif
            </div>
        </x-slot>

        <div class="space-y-4">
            {{-- Quick Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-4">
                <div class="text-center p-2 rounded-lg bg-danger-50 dark:bg-danger-900/20 border border-danger-200 dark:border-danger-800">
                    <p class="text-lg font-bold text-danger-700 dark:text-danger-300">{{ $stats['abiertos'] }}</p>
                    <p class="text-xs text-danger-600 dark:text-danger-400">Abiertos</p>
                </div>
                <div class="text-center p-2 rounded-lg bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800">
                    <p class="text-lg font-bold text-warning-700 dark:text-warning-300">{{ $stats['en_proceso'] }}</p>
                    <p class="text-xs text-warning-600 dark:text-warning-400">En Proceso</p>
                </div>
                <div class="text-center p-2 rounded-lg bg-info-50 dark:bg-info-900/20 border border-info-200 dark:border-info-800">
                    <p class="text-lg font-bold text-info-700 dark:text-info-300">{{ $stats['alta_prioridad'] }}</p>
                    <p class="text-xs text-info-600 dark:text-info-400">Alta Prioridad</p>
                </div>
                <div class="text-center p-2 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <p class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ $stats['sin_asignar'] }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Sin Asignar</p>
                </div>
            </div>

            @if($stats['vencidos'] > 0)
                <div class="p-3 rounded-lg bg-danger-50 dark:bg-danger-900/20 border border-danger-200 dark:border-danger-800">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-exclamation-triangle class="h-4 w-4 text-danger-500" />
                        <p class="text-sm font-medium text-danger-900 dark:text-danger-100">
                            {{ $stats['vencidos'] }} ticket(s) de alta prioridad vencidos (más de 24 horas)
                        </p>
                    </div>
                </div>
            @endif

            @if($hasTickets)
                <div class="space-y-2">
                    @foreach($pendingTickets as $ticket)
                        @php
                            $priorityColor = match($ticket['prioridad']) {
                                'Alta' => 'danger',
                                'Media' => 'warning',
                                'Baja' => 'info',
                                default => 'gray'
                            };
                            
                            $statusColor = match($ticket['estado']) {
                                'Abierto' => 'danger',
                                'En Proceso' => 'warning',
                                'Resuelto' => 'success',
                                default => 'gray'
                            };
                        @endphp
                        
                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 {{ $ticket['es_urgente'] ? 'ring-2 ring-danger-200 dark:ring-danger-800' : '' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                            {{ $ticket['titulo'] }}
                                        </h4>
                                        @if($ticket['es_urgente'])
                                            <x-heroicon-o-exclamation-triangle class="h-4 w-4 text-danger-500 flex-shrink-0" />
                                        @endif
                                    </div>
                                    
                                    @if($ticket['descripcion'])
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                            {{ Str::limit($ticket['descripcion'], 80) }}
                                        </p>
                                    @endif
                                    
                                    <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-500">
                                        <span>{{ $ticket['usuario'] }}</span>
                                        <span>{{ $ticket['tiempo_abierto'] }}</span>
                                        @if($ticket['tecnico'] !== 'Sin asignar')
                                            <span>Asignado: {{ $ticket['tecnico'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-end gap-1 ml-3">
                                    <x-filament::badge color="{{ $priorityColor }}" size="sm">
                                        {{ $ticket['prioridad'] }}
                                    </x-filament::badge>
                                    <x-filament::badge color="{{ $statusColor }}" size="sm">
                                        {{ $ticket['estado'] }}
                                    </x-filament::badge>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($stats['total_pendientes'] > 5)
                    <div class="text-center pt-2">
                        <p class="text-xs text-gray-500 dark:text-gray-500">
                            Mostrando 5 de {{ $stats['total_pendientes'] }} tickets pendientes
                        </p>
                    </div>
                @endif
            @else
                <div class="text-center py-6">
                    <x-heroicon-o-check-circle class="h-8 w-8 text-success-400 mx-auto mb-3" />
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @if(isset($error))
                            {{ $error }}
                        @else
                            No hay tickets Enviados
                        @endif
                    </p>
                    @if($stats['resueltos'] > 0)
                        <p class="text-xs text-success-600 dark:text-success-400 mt-1">
                            {{ $stats['resueltos'] }} tickets resueltos
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>