<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-bell class="h-5 w-5" />
                Notificaciones Recientes
                @if($totalUnread > 0)
                    <x-filament::badge color="danger" size="sm">
                        {{ $totalUnread }}
                    </x-filament::badge>
                @endif
            </div>
        </x-slot>

        <div class="space-y-3">
            @if($hasNotifications)
                @foreach($notifications as $notification)
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex-shrink-0">
                            @php
                                $iconClass = match($notification['color']) {
                                    'success' => 'text-success-500',
                                    'warning' => 'text-warning-500',
                                    'danger' => 'text-danger-500',
                                    default => 'text-primary-500'
                                };
                            @endphp
                            <x-dynamic-component 
                                :component="$notification['icon']" 
                                class="h-5 w-5 {{ $iconClass }}" 
                            />
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $notification['title'] }}
                            </h4>
                            
                            @if($notification['body'])
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ Str::limit($notification['body'], 100) }}
                                </p>
                            @endif
                            
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                {{ $notification['created_at']->diffForHumans() }}
                            </p>
                        </div>
                        
                        @if(!empty($notification['actions']))
                            <div class="flex-shrink-0">
                                @foreach($notification['actions'] as $action)
                                    @if(isset($action['url']))
                                        <a 
                                            href="{{ $action['url'] }}" 
                                            class="text-xs px-2 py-1 rounded bg-primary-100 text-primary-700 hover:bg-primary-200 dark:bg-primary-900 dark:text-primary-300"
                                        >
                                            {{ $action['label'] ?? 'Ver' }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="text-center py-6">
                    <x-heroicon-o-bell-slash class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @if(isset($error))
                            {{ $error }}
                        @else
                            No hay notificaciones recientes
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>