@props(['areaId' => null])

@php
    $horarios = [];
    $areaComun = null;
    
    if ($areaId) {
        $areaComun = \App\Models\AreaComun::with('horariosDisponibles')->find($areaId);
        if ($areaComun) {
            $horarios = $areaComun->horariosDisponibles->keyBy('dia_semana');
        }
    }
    
    $dias = [
        1 => ['nombre' => 'Lunes', 'corto' => 'Lun'],
        2 => ['nombre' => 'Martes', 'corto' => 'Mar'],
        3 => ['nombre' => 'Miércoles', 'corto' => 'Mié'],
        4 => ['nombre' => 'Jueves', 'corto' => 'Jue'],
        5 => ['nombre' => 'Viernes', 'corto' => 'Vie'],
        6 => ['nombre' => 'Sábado', 'corto' => 'Sáb'],
        0 => ['nombre' => 'Domingo', 'corto' => 'Dom'],
    ];
@endphp

<div class="w-full">
    @if($areaId && $areaComun)
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-950 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 dark:from-primary-700 dark:to-primary-800 px-4 py-3 sm:px-6">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-white dark:text-gray-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-white dark:text-gray-100 truncate">
                            Horarios Disponibles
                        </h3>
                        <p class="text-sm text-primary-100 dark:text-gray-300 truncate">
                            {{ $areaComun->nombre }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4 sm:p-6">
                @if($horarios->isEmpty())
                    <!-- Empty State -->
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Sin horarios configurados
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Esta área común no tiene horarios disponibles definidos.
                        </p>
                    </div>
                @else
                    <!-- Desktop View -->
                    <div class="hidden lg:block">
                        <div class="grid grid-cols-7 gap-3">
                            @foreach($dias as $diaNum => $dia)
                                @php
                                    $horario = $horarios->get($diaNum);
                                    $disponible = $horario !== null;
                                @endphp
                                
                                <div class="relative group">
                                    <div class="rounded-lg border-2 transition-all duration-200 {{ $disponible 
                                        ? 'border-success-500 dark:border-success-500 bg-success-50 dark:bg-success-500/10 hover:shadow-lg hover:scale-105 hover:dark:bg-success-500/20' 
                                        : 'border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 opacity-60' }}">
                                        
                                        <div class="p-4 text-center">
                                            <!-- Day Name -->
                                            <div class="mb-3">
                                                <span class="text-xs font-medium uppercase tracking-wider {{ $disponible 
                                                    ? 'text-success-700 dark:text-success-300' 
                                                    : 'text-gray-500 dark:text-gray-400' }}">
                                                    {{ $dia['corto'] }}
                                                </span>
                                            </div>
                                            
                                            @if($disponible)
                                                <!-- Available Icon -->
                                                <div class="mb-2">
                                                    <svg class="w-8 h-8 mx-auto text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                
                                                <!-- Hours -->
                                                <div class="space-y-1">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <svg class="w-3 h-3 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                                        </svg>
                                                        <span class="text-xs font-semibold text-success-700 dark:text-success-300">
                                                            {{ \Carbon\Carbon::parse($horario->hora_apertura)->format('H:i') }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center justify-center gap-1">
                                                        <svg class="w-3 h-3 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                        </svg>
                                                        <span class="text-xs font-semibold text-success-700 dark:text-success-300">
                                                            {{ \Carbon\Carbon::parse($horario->hora_cierre)->format('H:i') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <!-- Unavailable Icon -->
                                                <div class="mb-2">
                                                    <svg class="w-8 h-8 mx-auto text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                    Cerrado
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tablet View -->
                    <div class="hidden md:block lg:hidden">
                        <div class="space-y-2">
                            @foreach($dias as $diaNum => $dia)
                                @php
                                    $horario = $horarios->get($diaNum);
                                    $disponible = $horario !== null;
                                @endphp
                                
                                <div class="flex items-center gap-3 p-3 rounded-lg border transition-all duration-200 {{ $disponible 
                                    ? 'border-success-500 dark:border-success-500 bg-success-50 dark:bg-success-500/10 hover:shadow-md hover:dark:bg-success-500/20' 
                                    : 'border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 opacity-60' }}">
                                    
                                    <!-- Icon -->
                                    <div class="flex-shrink-0">
                                        @if($disponible)
                                            <svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    
                                    <!-- Day Name -->
                                    <div class="flex-1 min-w-0">
                                        <span class="text-sm font-semibold {{ $disponible 
                                            ? 'text-success-700 dark:text-success-300' 
                                            : 'text-gray-500 dark:text-gray-400' }}">
                                            {{ $dia['nombre'] }}
                                        </span>
                                    </div>
                                    
                                    <!-- Hours -->
                                    <div class="flex-shrink-0">
                                        @if($disponible)
                                            <div class="flex items-center gap-2 text-xs font-medium text-success-700 dark:text-success-300">
                                                <span>{{ \Carbon\Carbon::parse($horario->hora_apertura)->format('H:i') }}</span>
                                                <span>-</span>
                                                <span>{{ \Carbon\Carbon::parse($horario->hora_cierre)->format('H:i') }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                Cerrado
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Mobile View -->
                    <div class="block md:hidden">
                        <div class="space-y-2">
                            @foreach($dias as $diaNum => $dia)
                                @php
                                    $horario = $horarios->get($diaNum);
                                    $disponible = $horario !== null;
                                @endphp
                                
                                <div class="rounded-lg border transition-all duration-200 {{ $disponible 
                                    ? 'border-success-500 dark:border-success-500 bg-success-50 dark:bg-success-500/10 hover:dark:bg-success-500/20' 
                                    : 'border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 opacity-60' }}">
                                    
                                    <div class="p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2">
                                                @if($disponible)
                                                    <svg class="w-5 h-5 text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                                <span class="text-sm font-semibold {{ $disponible 
                                                    ? 'text-success-700 dark:text-success-300' 
                                                    : 'text-gray-500 dark:text-gray-400' }}">
                                                    {{ $dia['nombre'] }}
                                                </span>
                                            </div>
                                            
                                            @if($disponible)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-success-100 dark:bg-success-500/20 text-success-700 dark:text-success-300">
                                                    Disponible
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                                                    Cerrado
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($disponible)
                                            <div class="flex items-center gap-4 text-xs pl-7">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3 h-3 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="font-medium text-success-700 dark:text-success-300">
                                                        {{ \Carbon\Carbon::parse($horario->hora_apertura)->format('H:i') }}
                                                    </span>
                                                    <span class="text-success-600 dark:text-success-400">-</span>
                                                    <span class="font-medium text-success-700 dark:text-success-300">
                                                        {{ \Carbon\Carbon::parse($horario->hora_cierre)->format('H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-wrap items-center justify-center gap-4 text-xs">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-success-500 dark:bg-success-400"></div>
                                <span class="text-gray-600 dark:text-gray-300">Disponible</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-gray-400 dark:bg-gray-500"></div>
                                <span class="text-gray-600 dark:text-gray-300">Cerrado</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- No Area Selected -->
        <div class="rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-8">
            <div class="text-center">
                <svg class="mx-auto h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                    Seleccione un área común
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Elija un área común para ver sus horarios disponibles
                </p>
            </div>
        </div>
    @endif
</div>
