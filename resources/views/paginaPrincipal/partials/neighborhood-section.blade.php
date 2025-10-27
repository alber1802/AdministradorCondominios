<!-- Sección de Contexto del Vecindario -->
<section id="neighborhood" class="relative min-h-screen overflow-hidden">
    <!-- Contenedor de Fondo Panorámico -->
    <div class="panoramic-background absolute inset-0 w-full h-full">
        <div class="absolute inset-0 bg-cover bg-center bg-fixed parallax-bg"
            style="background-image: url('{{ asset('mis_imagenes/neighborhood/panoramic-cityview.jpg') }}')">
        </div>
        <!-- Overlay oscuro para legibilidad del texto -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/60 to-black/80"></div>
    </div>

    <!-- Contenedor de Contenido -->
    <div class="relative z-10 flex items-center min-h-screen">
        <div class="container mx-auto px-6 py-20">

            <!-- Estructura Principal de Superposición de Texto -->
            <div class="text-overlay-container max-w-4xl mx-auto text-center">
                <!-- Encabezado Principal -->
                <div class="neighborhood-title opacity-0 transform translate-y-8">
                    <h2 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                        Ubicación <span class="text-blue-400">Premium</span>
                    </h2>
                    <p class="text-xl md:text-2xl text-gray-200 mb-12 leading-relaxed">
                        Situado en el corazón del distrito metropolitano, IntelliTower ofrece acceso incomparable a las
                        mejores amenidades de la ciudad
                    </p>
                </div>

                <!-- Grid de Información de Ubicación -->
                <div class="location-info-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">

                    <!-- Centro de Transporte -->
                    <div class="location-highlight opacity-0 transform translate-y-8" data-delay="0.2">
                        <div
                            class="glassmorphism-card p-6 rounded-xl backdrop-blur-md bg-white/10 border border-white/20">
                            <div class="highlight-icon mb-4 flex justify-center">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-3">Transporte</h3>
                            <div class="space-y-1 text-sm text-gray-300">
                                <p>Estación de Metro: 2 min caminando</p>
                                <p>Terminal de Autobuses: 5 min caminando</p>
                                <p>Aeropuerto Internacional: 25 min</p>
                                <p>Acceso a Autopista: 3 min</p>
                            </div>
                        </div>
                    </div>

                    <!-- Gastronomía y Cultura -->
                    <div class="location-highlight opacity-0 transform translate-y-8" data-delay="0.4">
                        <div
                            class="glassmorphism-card p-6 rounded-xl backdrop-blur-md bg-white/10 border border-white/20">
                            <div class="highlight-icon mb-4 flex justify-center">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-3">Gastronomía y Cultura</h3>
                            <div class="space-y-1 text-sm text-gray-300">
                                <p>Restaurantes Estrella Michelin</p>
                                <p>Galerías de Arte y Museos</p>
                                <p>Distrito Teatral</p>
                                <p>Bares y Lounges en Azoteas</p>
                            </div>
                        </div>
                    </div>

                    <!-- Compras y Comercio -->
                    <div class="location-highlight opacity-0 transform translate-y-8" data-delay="0.6">
                        <div
                            class="glassmorphism-card p-6 rounded-xl backdrop-blur-md bg-white/10 border border-white/20">
                            <div class="highlight-icon mb-4 flex justify-center">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-700 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-3">Compras</h3>
                            <div class="space-y-1 text-sm text-gray-300">
                                <p>Centro Comercial de Lujo</p>
                                <p>Boutiques de Diseñador</p>
                                <p>Mercados Artesanales</p>
                                <p>Tiendas de Conveniencia 24/7</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recreación y Bienestar -->
                    <div class="location-highlight opacity-0 transform translate-y-8" data-delay="0.8">
                        <div
                            class="glassmorphism-card p-6 rounded-xl backdrop-blur-md bg-white/10 border border-white/20">
                            <div class="highlight-icon mb-4 flex justify-center">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-700 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-3">Recreación</h3>
                            <div class="space-y-1 text-sm text-gray-300">
                                <p>Parque Central: 3 cuadras</p>
                                <p>Centros de Fitness y Spas</p>
                                <p>Paseo Marítimo</p>
                                <p>Clubes Deportivos y de Tenis</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen de Ubicación -->
                <div class="location-summary opacity-0 transform translate-y-8" data-delay="1.0">
                    <div
                        class="glassmorphism-card p-8 rounded-2xl backdrop-blur-md bg-white/10 border border-white/20 max-w-3xl mx-auto">
                        <h3 class="text-2xl font-semibold text-white mb-4">
                            Donde el Lujo se Encuentra con la Comodidad
                        </h3>
                        <p class="text-gray-200 text-lg leading-relaxed mb-6">
                            Experimenta el equilibrio perfecto entre la sofisticación urbana y la tranquilidad
                            residencial.
                            La ubicación estratégica de IntelliTower te coloca en el centro del distrito más deseable de
                            la ciudad,
                            con amenidades de clase mundial a solo unos pasos de tu puerta.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="#contact"
                                class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105">
                                Programar Tour Privado
                            </a>
                            <a href="#apartments"
                                class="bg-transparent border-2 border-white/50 text-white hover:bg-white hover:text-black font-semibold py-3 px-8 rounded-lg transition-all duration-300 backdrop-blur-sm">
                                Explorar Planos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
