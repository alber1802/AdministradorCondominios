<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Selector de Área Común --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    {{ $this->form }}
                </div>

                @if ($this->getAreaComun())
                    <div class="flex-shrink-0 text-right">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Capacidad</div>
                        <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                            {{ $this->getAreaComun()->capacidad }}
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Estado</div>
                        <div class="text-lg font-semibold">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if ($this->getAreaComun()->estado === 'Disponible') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($this->getAreaComun()->estado === 'Mantenimiento') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($this->getAreaComun()->estado === 'Fuera de Servicio') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                                {{ $this->getAreaComun()->estado }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Leyenda de Colores --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-center gap-6 flex-wrap" style="color: rgb(130 147 173);">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded" style="background-color: #10b981;"></div>
                    <span class="text-sm text-gray-450 dark:text-gray-300">Confirmada</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded" style="background-color: #f59e0b;"></div>
                    <span class="text-sm  text-gray-450 dark:text-gray-300">Pendiente</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded" style="background-color: #ef4444;"></div>
                    <span class="text-sm  text-gray-450 dark:text-gray-300">Cancelada</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded" style="background-color: #6b7280;"></div>
                    <span class="text-sm  text-gray-450 dark:text-gray-300">Completada</span>
                </div>
            </div>
        </div>

        {{-- Calendario --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div id="calendar" wire:ignore></div>
        </div>
    </div>

    {{-- Modal de Detalles --}}
    <div x-data="{ open: false, reserva: null }" @open-reserva-modal.window="open = true; reserva = $event.detail"
        @close-modal.window="open = false" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Overlay --}}
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75"
                @click="open = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            {{-- Modal Panel --}}
            <div x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Detalles de la Reserva
                    </h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div x-show="reserva" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Residente
                            </label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white"
                                x-text="reserva?.extendedProps?.residente"></p>
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Área Común
                            </label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white"
                                x-text="reserva?.extendedProps?.area"></p>
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Fecha y Hora de Inicio
                            </label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white"
                                x-text="reserva?.extendedProps?.inicio"></p>
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Fecha y Hora de Fin
                            </label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white"
                                x-text="reserva?.extendedProps?.fin"></p>
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                Costo Total
                            </label>
                            <p class="text-2xl font-bold text-primary-600 dark:text-primary-400"
                                x-text="reserva?.extendedProps?.costo"></p>
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Estado
                            </label>
                            <p class="text-lg font-semibold capitalize"
                                :class="{
                                    'text-green-600 dark:text-green-400': reserva?.extendedProps
                                        ?.estado === 'Confirmada' || reserva?.extendedProps?.estado === 'confirmada',
                                    'text-yellow-600 dark:text-yellow-400': reserva?.extendedProps
                                        ?.estado === 'Pendiente' || reserva?.extendedProps?.estado === 'pendiente',
                                    'text-red-600 dark:text-red-400': reserva?.extendedProps?.estado === 'Cancelada' ||
                                        reserva?.extendedProps?.estado === 'cancelada',
                                    'text-gray-600 dark:text-gray-400': reserva?.extendedProps
                                        ?.estado === 'Completada' || reserva?.extendedProps?.estado === 'completada'
                                }"
                                x-text="reserva?.extendedProps?.estado"></p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a :href="`/admin/reservas/${reserva?.id}`"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Ver Detalles Completos
                        </a>
                        <button @click="open = false"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-lg transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Estilos generales del calendario */
            .fc {
                font-family: inherit;
            }

            /* Modo Dark - Fondo y bordes */
            .dark .fc {
                --fc-border-color: rgb(55, 65, 81);
                --fc-button-bg-color: rgb(59, 130, 246);
                --fc-button-border-color: rgb(59, 130, 246);
                --fc-button-hover-bg-color: rgb(37, 99, 235);
                --fc-button-hover-border-color: rgb(37, 99, 235);
                --fc-button-active-bg-color: rgb(29, 78, 216);
                --fc-button-active-border-color: rgb(29, 78, 216);
                --fc-today-bg-color: rgba(59, 130, 246, 0.15);
            }

            /* Encabezado del calendario */
            .fc .fc-toolbar-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: rgb(17, 24, 39);
            }

            .dark .fc .fc-toolbar-title {
                color: rgb(243, 244, 246);
            }

            /* Botones */
            .fc .fc-button {
                padding: 0.5rem 1rem;
                font-weight: 500;
                text-transform: capitalize;
                border-radius: 0.5rem;
                transition: all 0.2s;
            }

            .fc .fc-button:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .fc .fc-button-primary:not(:disabled).fc-button-active,
            .fc .fc-button-primary:not(:disabled):active {
                background-color: rgb(29, 78, 216);
                border-color: rgb(29, 78, 216);
            }

            /* Días de la semana */
            .fc .fc-col-header-cell {
                background-color: rgb(249, 250, 251);
                border-color: rgb(229, 231, 235);
                padding: 0.75rem 0.5rem;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
            }

            .dark .fc .fc-col-header-cell {
                background-color: rgb(31, 41, 55);
                border-color: rgb(55, 65, 81);
                color: rgb(209, 213, 219);
            }

            .fc .fc-col-header-cell-cushion {
                color: rgb(75, 85, 99);
                text-decoration: none;
            }

            .dark .fc .fc-col-header-cell-cushion {
                color: rgb(209, 213, 219);
            }

            /* Celdas de días */
            .fc .fc-daygrid-day {
                background-color: white;
            }

            .dark .fc .fc-daygrid-day {
                background-color: rgb(17, 24, 39);
            }

            .fc .fc-daygrid-day-frame {
                min-height: 100px;
            }

            .fc .fc-daygrid-day-top {
                padding: 0.5rem;
            }

            .fc .fc-daygrid-day-number {
                color: rgb(17, 24, 39);
                font-weight: 500;
                padding: 0.25rem 0.5rem;
                text-decoration: none;
            }

            .dark .fc .fc-daygrid-day-number {
                color: rgb(243, 244, 246);
            }

            /* Día actual */
            .fc .fc-day-today {
                background-color: rgba(59, 130, 246, 0.1) !important;
            }

            .dark .fc .fc-day-today {
                background-color: rgba(59, 130, 246, 0.15) !important;
            }

            .fc .fc-day-today .fc-daygrid-day-number {
                background-color: rgb(59, 130, 246);
                color: white;
                border-radius: 0.375rem;
                font-weight: 700;
            }

            /* Días de otros meses */
            .fc .fc-day-other .fc-daygrid-day-number {
                color: rgb(156, 163, 175);
            }

            .dark .fc .fc-day-other .fc-daygrid-day-number {
                color: rgb(75, 85, 99);
            }

            /* Eventos */
            .fc-event {
                border-radius: 0.375rem;
                padding: 0.25rem 0.5rem;
                margin: 0.125rem 0.25rem;
                font-size: 0.875rem;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s;
                border: none !important;
            }

            .fc-event:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
                filter: brightness(1.1);
            }

            .fc-event-title {
                font-weight: 600;
            }

            .fc-event-time {
                font-weight: 500;
                opacity: 0.9;
            }

            /* Vista de lista */
            .fc .fc-list-event:hover td {
                background-color: rgb(243, 244, 246);
            }

            .dark .fc .fc-list-event:hover td {
                background-color: rgb(31, 41, 55);
            }

            .fc .fc-list-day-cushion {
                background-color: rgb(249, 250, 251);
            }

            .dark .fc .fc-list-day-cushion {
                background-color: rgb(31, 41, 55);
                color: rgb(243, 244, 246);
            }

            .fc .fc-list-event-title {
                color: rgb(17, 24, 39);
            }

            .dark .fc .fc-list-event-title {
                color: rgb(243, 244, 246);
            }

            .fc .fc-list-event-time {
                color: rgb(75, 85, 99);
            }

            .dark .fc .fc-list-event-time {
                color: rgb(156, 163, 175);
            }

            /* Vista de semana/día */
            .fc .fc-timegrid-slot {
                height: 3rem;
            }

            .fc .fc-timegrid-slot-label {
                color: rgb(75, 85, 99);
                font-size: 0.875rem;
            }

            .dark .fc .fc-timegrid-slot-label {
                color: rgb(156, 163, 175);
            }

            .fc .fc-timegrid-axis {
                background-color: rgb(249, 250, 251);
            }

            .dark .fc .fc-timegrid-axis {
                background-color: rgb(31, 41, 55);
            }

            /* Línea de tiempo actual */
            .fc .fc-timegrid-now-indicator-line {
                border-color: rgb(239, 68, 68);
                border-width: 2px;
            }

            .fc .fc-timegrid-now-indicator-arrow {
                border-color: rgb(239, 68, 68);
            }

            /* Scrollbar personalizado para dark mode */
            .dark .fc-scroller::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            .dark .fc-scroller::-webkit-scrollbar-track {
                background: rgb(31, 41, 55);
                border-radius: 4px;
            }

            .dark .fc-scroller::-webkit-scrollbar-thumb {
                background: rgb(75, 85, 99);
                border-radius: 4px;
            }

            .dark .fc-scroller::-webkit-scrollbar-thumb:hover {
                background: rgb(107, 114, 128);
            }

            /* Popover */
            .fc .fc-popover {
                background-color: white;
                border-color: rgb(229, 231, 235);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            }

            .dark .fc .fc-popover {
                background-color: rgb(31, 41, 55);
                border-color: rgb(55, 65, 81);
            }

            .fc .fc-popover-header {
                background-color: rgb(249, 250, 251);
                color: rgb(17, 24, 39);
            }

            .dark .fc .fc-popover-header {
                background-color: rgb(17, 24, 39);
                color: rgb(243, 244, 246);
            }

            /* Más eventos link */
            .fc .fc-daygrid-more-link {
                color: rgb(59, 130, 246);
                font-weight: 600;
            }

            .dark .fc .fc-daygrid-more-link {
                color: rgb(96, 165, 250);
            }

            /* Animación de carga */
            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.5;
                }
            }

            .fc-loading {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
        </style>
    @endpush

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let calendar;
                const calendarEl = document.getElementById('calendar');

                function initCalendar() {
                    if (calendar) {
                        calendar.destroy();
                    }

                    calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'es',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                        },
                        buttonText: {
                            today: 'Hoy',
                            month: 'Mes',
                            week: 'Semana',
                            day: 'Día',
                            list: 'Lista'
                        },
                        height: 'auto',
                        navLinks: true,
                        editable: false,
                        dayMaxEvents: true,
                        nowIndicator: true,
                        events: @js($this->getReservas()),
                        eventClick: function(info) {
                            window.dispatchEvent(new CustomEvent('open-reserva-modal', {
                                detail: info.event
                            }));
                        },
                        eventTimeFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        },
                        slotLabelFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        },
                        eventDidMount: function(info) {
                            // Agregar tooltip
                            info.el.title =
                                `${info.event.extendedProps.residente}\n${info.event.extendedProps.inicio} - ${info.event.extendedProps.fin}\nEstado: ${info.event.extendedProps.estado}`;
                        }
                    });

                    calendar.render();
                }

                initCalendar();

                // Recargar calendario cuando cambia el área
                Livewire.on('areaChanged', () => {
                    setTimeout(() => {
                        initCalendar();
                    }, 100);
                });

                // Escuchar cambios en el formulario
                window.addEventListener('areaChanged', () => {
                    setTimeout(() => {
                        initCalendar();
                    }, 100);
                });
            });
        </script>
    @endpush
</x-filament-panels::page>
