<div class="flex justify-center items-center p-4">
    <div class="text-center">
        @if(file_exists(public_path('mis_imagenes/icono-codigo-qr.jpg')))
            <img src="{{ asset('mis_imagenes/icono-codigo-qr.jpg') }}" 
                 alt="Código QR" 
                 class="mx-auto max-w-xs h-auto border border-gray-300 rounded-lg shadow-sm">
        @else
            <div class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm text-gray-500">No hay código QR disponible</p>
            </div>
        @endif
    </div>
</div>