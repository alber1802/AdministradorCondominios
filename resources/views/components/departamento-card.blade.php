@props([
    'departamento' => null,
])

<div class="bg-gradient-to-r from-slate-800 to-slate-900 text-white rounded-xl shadow-xl p-6">
    @if($departamento)
        <div class="flex items-center justify-between">
            <div class="flex-1">
            <h3 class="text-lg font-bold text-cyan-300 mb-3">Departamento {{ $departamento->numero ?? 'no definido'}}</h3>
                
                <div class="flex items-center space-x-6">
                    <div class="space-y-1">
                    <div class="flex items-center space-x-2">
                            <span class="font-semibold text-emerald-400 text-sm">Piso:</span> 
                            <span class="text-yellow-300 font-medium text-sm">{{ $departamento->piso ?? 'N/A' }}</span>
                    </div>
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold text-emerald-400 text-sm">Bloque:</span> 
                            <span class="text-yellow-300 font-medium text-sm">{{ $departamento->bloque ?? 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('filament.admin.resources.departamentos.edit', $departamento->id) }}"
                       class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-medium text-sm rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        ✏️ Editar Departamento
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-4">
            <p class="italic text-red-400 text-lg">⚠️ Este usuario no tiene un departamento asignado.</p>
        </div>
    @endif
</div>
