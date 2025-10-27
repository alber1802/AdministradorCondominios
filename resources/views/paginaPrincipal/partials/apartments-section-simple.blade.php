<!-- Apartments Section -->
<section id="apartments" class="py-20 bg-gradient-to-b from-black to-gray-900">
    <div class="container mx-auto px-6">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 text-gradient">
                Apartamentos de Lujo
            </h2>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                Descubre nuestra colección de espacios habitacionales meticulosamente diseñados, cada uno creado para brindar lo máximo en
                comodidad y sofisticación.
            </p>
        </div>

        <!-- Apartments Grid -->
        <div class="apartments-grid">
            <!-- Studio Apartment -->
            <div class="apartment-card cursor-pointer" onclick="openApartmentModal('studio')">
                <div class="p-6">
                    <div class="apartment-image mb-6 relative overflow-hidden rounded-xl">
                        <img src="{{ asset('mis_imagenes/apartments/studio.jpg') }}" alt="Studio Apartment"
                            class="w-full h-56 object-cover">
                        <div
                            class="absolute top-4 right-4 bg-blue-600 text-white text-sm font-semibold px-3 py-1 rounded-full">
                            Disponible
                        </div>
                    </div>
                    <div class="apartment-info">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-2xl font-bold text-white">Estudio</h3>
                            <span class="text-sm text-gray-400 bg-gray-800 px-2 py-1 rounded">42 m²</span>
                        </div>
                        <p class="text-gray-300 mb-4 text-sm">Vida de lujo moderna con integración de hogar inteligente y
                            acabados premium en todo el espacio.</p>

                        <!-- Features -->
                        <div class="mb-6">
                            <div class="flex flex-wrap gap-3">
                                <span class="feature-tag">Hogar Inteligente</span>
                                <span class="feature-tag">Vista a la Ciudad</span>
                                <span class="feature-tag">Acabados Premium</span>
                            </div>
                        </div>

                        <!-- Price and CTA -->
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-3xl font-bold text-blue-400">$1,200</span>
                                <span class="text-sm text-gray-400">/mes</span>
                            </div>
                            <button class="btn-primary">
                                Ver Detalles
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- One Bedroom Apartment -->
            <div class="apartment-card cursor-pointer" onclick="openApartmentModal('onebr')">
                <div class="p-6">
                    <div class="apartment-image mb-6 relative overflow-hidden rounded-xl">
                        <img src="{{ asset('mis_imagenes/apartments/one-bedroom.jpg') }}" alt="One Bedroom Apartment"
                            class="w-full h-56 object-cover">
                        <div
                            class="absolute top-4 right-4 bg-green-600 text-white text-sm font-semibold px-3 py-1 rounded-full">
                            Popular
                        </div>
                    </div>
                    <div class="apartment-info">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-2xl font-bold text-white">Una Habitación</h3>
                            <span class="text-sm text-gray-400 bg-gray-800 px-2 py-1 rounded">60 m²</span>
                        </div>
                        <p class="text-gray-300 mb-4 text-sm">Espacio elegante con balcón privado y electrodomésticos
                            premium en la cocina.</p>

                        <!-- Features -->
                        <div class="mb-6">
                            <div class="flex flex-wrap gap-3">
                                <span class="feature-tag">Hogar Inteligente</span>
                                <span class="feature-tag">Balcón</span>
                                <span class="feature-tag">Cocina Premium</span>
                            </div>
                        </div>

                        <!-- Price and CTA -->
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-3xl font-bold text-blue-400">$1,800</span>
                                <span class="text-sm text-gray-400">/mes</span>
                            </div>
                            <button class="btn-primary">
                                Ver Detalles
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Bedroom Apartment -->
            <div class="apartment-card cursor-pointer" onclick="openApartmentModal('twobr')">
                <div class="p-6">
                    <div class="apartment-image mb-6 relative overflow-hidden rounded-xl">
                        <img src="{{ asset('mis_imagenes/apartments/two-bedroom.webp') }}" alt="Two Bedroom Apartment"
                            class="w-full h-56 object-cover">
                        <div
                            class="absolute top-4 right-4 bg-purple-600 text-white text-sm font-semibold px-3 py-1 rounded-full">
                            Premium
                        </div>
                    </div>
                    <div class="apartment-info">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-2xl font-bold text-white">Dos Habitaciones</h3>
                            <span class="text-sm text-gray-400 bg-gray-800 px-2 py-1 rounded">88 m²</span>
                        </div>
                        <p class="text-gray-300 mb-4 text-sm">Comodidad espaciosa con suite principal, balcón amplio y
                            amenidades premium.</p>

                        <!-- Features -->
                        <div class="mb-6">
                            <div class="flex flex-wrap gap-3">
                                <span class="feature-tag">Hogar Inteligente</span>
                                <span class="feature-tag">Balcón Amplio</span>
                                <span class="feature-tag">Suite Principal</span>
                            </div>
                        </div>

                        <!-- Price and CTA -->
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-3xl font-bold text-blue-400">$2,500</span>
                                <span class="text-sm text-gray-400">/mes</span>
                            </div>
                            <button class="btn-primary">
                                Ver Detalles
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de Apartamentos -->
<div id="apartment-modal" class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="modal-content max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-700">
                <h3 class="modal-title text-3xl font-bold text-white"></h3>
                <button id="close-modal" class="text-gray-400 hover:text-white text-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Imagen -->
                    <div>
                        <img id="modal-image" src="" alt=""
                            class="w-full h-64 lg:h-80 object-cover rounded-xl">
                    </div>

                    <!-- Detalles -->
                    <div>
                        <div class="mb-6">
                            <div class="flex justify-between py-2 border-b border-gray-700">
                                <span class="text-gray-400">Precio:</span>
                                <span id="modal-price" class="text-white font-semibold"></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-700">
                                <span class="text-gray-400">Tamaño:</span>
                                <span id="modal-size" class="text-white font-semibold"></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-700">
                                <span class="text-gray-400">Habitaciones:</span>
                                <span id="modal-bedrooms" class="text-white font-semibold"></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-700">
                                <span class="text-gray-400">Baños:</span>
                                <span id="modal-bathrooms" class="text-white font-semibold"></span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h4 class="text-xl font-semibold text-white mb-3">Características</h4>
                            <div id="modal-features" class="flex flex-wrap gap-2">
                                <!-- Features will be populated by JavaScript -->
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button class="btn-primary flex-1">Agendar Visita</button>
                            <button class="btn-primary flex-1">Contactar Agente</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
