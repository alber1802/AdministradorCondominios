<!-- Sección de Amenidades Premium -->
<section id="common-areas" class="py-16 sm:py-20 bg-gradient-to-b from-gray-900 via-black to-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado de Sección -->
        <div class="text-center mb-12 sm:mb-16">
            <div class="inline-block mb-4">
                <span class="text-sm font-semibold text-blue-400 tracking-wider uppercase">Espacios Exclusivos</span>
            </div>
            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                <span class="bg-gradient-to-r from-blue-400 via-purple-400 to-teal-400 bg-clip-text text-transparent">Amenidades</span> Premium
            </h2>
            <p class="text-lg sm:text-xl text-gray-300 max-w-4xl mx-auto leading-relaxed">
                Experimenta amenidades de clase mundial diseñadas para mejorar tu estilo de vida y brindar comodidad incomparable en cada momento de tu día.
            </p>
        </div>
        
        <!-- Grid de Amenidades Responsive -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-6 sm:gap-8 lg:gap-10">
            
            <!-- Sala de Estar de Lujo -->
            <div class="amenity-card group relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-sm border border-gray-700/50 hover:border-blue-500/50 transition-all duration-500 hover:transform hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-500/20">
                <!-- Imagen de fondo con overlay -->
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" 
                     style="background-image: linear-gradient(45deg, rgba(0,0,0,0.6), rgba(0,0,0,0.4)), url('{{ asset('mis_imagenes/common-areas/living-room.jpeg') }}');">
                </div>
                
                <!-- Overlay de gradiente -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-90 group-hover:opacity-95 transition-opacity duration-500"></div>
                
                <!-- Contenido de la tarjeta -->
                <div class="relative z-10 p-6 sm:p-8 h-80 sm:h-96 flex flex-col justify-end">
                    <!-- Icono decorativo -->
                    <div class="absolute top-6 right-6 w-12 h-12 bg-blue-600/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-blue-500/30 group-hover:bg-blue-600/30 transition-all duration-300">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v0"></path>
                        </svg>
                    </div>
                    
                    <h3 class="text-2xl sm:text-3xl font-bold text-white mb-3 sm:mb-4 group-hover:text-blue-300 transition-colors duration-300">
                        Sala de Estar de Lujo
                    </h3>
                    <p class="text-gray-200 mb-4 sm:mb-6 text-sm sm:text-base leading-relaxed">
                        Espacio comunitario elegante con mobiliario premium, sistemas de entretenimiento de última generación y vistas panorámicas de la ciudad.
                    </p>
                    
                    <!-- Tags de características -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="feature-tag px-3 py-1 bg-blue-600/20 text-blue-300 text-xs font-medium rounded-full border border-blue-500/30 backdrop-blur-sm">
                            Asientos Premium
                        </span>
                        <span class="feature-tag px-3 py-1 bg-blue-600/20 text-blue-300 text-xs font-medium rounded-full border border-blue-500/30 backdrop-blur-sm">
                            Sistema de Entretenimiento
                        </span>
                        <span class="feature-tag px-3 py-1 bg-blue-600/20 text-blue-300 text-xs font-medium rounded-full border border-blue-500/30 backdrop-blur-sm">
                            Acceso 24/7
                        </span>
                    </div>
                    
                    <!-- Botón de acción -->
                    <button class="self-start px-4 py-2 bg-blue-600/80 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-all duration-300 backdrop-blur-sm border border-blue-500/50 hover:border-blue-400 transform hover:scale-105">
                        Ver Detalles
                    </button>
                </div>
            </div>
            
            <!-- Espacio de Coworking -->
            <div class="amenity-card group relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-sm border border-gray-700/50 hover:border-green-500/50 transition-all duration-500 hover:transform hover:scale-[1.02] hover:shadow-2xl hover:shadow-green-500/20">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" 
                     style="background-image: linear-gradient(45deg, rgba(0,0,0,0.6), rgba(0,0,0,0.4)), url('{{ asset('mis_imagenes/common-areas/coworking.jpg') }}');">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-90 group-hover:opacity-95 transition-opacity duration-500"></div>
                
                <div class="relative z-10 p-6 sm:p-8 h-80 sm:h-96 flex flex-col justify-end">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-green-600/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-green-500/30 group-hover:bg-green-600/30 transition-all duration-300">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                    </div>
                    
                    <h3 class="text-2xl sm:text-3xl font-bold text-white mb-3 sm:mb-4 group-hover:text-green-300 transition-colors duration-300">
                        Espacio de Coworking
                    </h3>
                    <p class="text-gray-200 mb-4 sm:mb-6 text-sm sm:text-base leading-relaxed">
                        Espacio de trabajo moderno con internet de alta velocidad, salas de reuniones privadas y ambiente profesional para la productividad.
                    </p>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="feature-tag px-3 py-1 bg-green-600/20 text-green-300 text-xs font-medium rounded-full border border-green-500/30 backdrop-blur-sm">
                            WiFi Alta Velocidad
                        </span>
                        <span class="feature-tag px-3 py-1 bg-green-600/20 text-green-300 text-xs font-medium rounded-full border border-green-500/30 backdrop-blur-sm">
                            Salas de Reuniones
                        </span>
                        <span class="feature-tag px-3 py-1 bg-green-600/20 text-green-300 text-xs font-medium rounded-full border border-green-500/30 backdrop-blur-sm">
                            Servicios de Impresión
                        </span>
                    </div>
                    
                    <button class="self-start px-4 py-2 bg-green-600/80 hover:bg-green-600 text-white text-sm font-semibold rounded-lg transition-all duration-300 backdrop-blur-sm border border-green-500/50 hover:border-green-400 transform hover:scale-105">
                        Ver Detalles
                    </button>
                </div>
            </div>
            
            <!-- Centro de Fitness -->
            <div class="amenity-card group relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-sm border border-gray-700/50 hover:border-purple-500/50 transition-all duration-500 hover:transform hover:scale-[1.02] hover:shadow-2xl hover:shadow-purple-500/20">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" 
                     style="background-image: linear-gradient(45deg, rgba(0,0,0,0.6), rgba(0,0,0,0.4)), url('{{ asset('mis_imagenes/common-areas/gym.jpg') }}');">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-90 group-hover:opacity-95 transition-opacity duration-500"></div>
                
                <div class="relative z-10 p-6 sm:p-8 h-80 sm:h-96 flex flex-col justify-end">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-purple-600/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-purple-500/30 group-hover:bg-purple-600/30 transition-all duration-300">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    
                    <h3 class="text-2xl sm:text-3xl font-bold text-white mb-3 sm:mb-4 group-hover:text-purple-300 transition-colors duration-300">
                        Centro de Fitness
                    </h3>
                    <p class="text-gray-200 mb-4 sm:mb-6 text-sm sm:text-base leading-relaxed">
                        Instalación de fitness de última generación con equipos premium, entrenamiento personal y programas de bienestar.
                    </p>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="feature-tag px-3 py-1 bg-purple-600/20 text-purple-300 text-xs font-medium rounded-full border border-purple-500/30 backdrop-blur-sm">
                            Equipos Premium
                        </span>
                        <span class="feature-tag px-3 py-1 bg-purple-600/20 text-purple-300 text-xs font-medium rounded-full border border-purple-500/30 backdrop-blur-sm">
                            Entrenamiento Personal
                        </span>
                        <span class="feature-tag px-3 py-1 bg-purple-600/20 text-purple-300 text-xs font-medium rounded-full border border-purple-500/30 backdrop-blur-sm">
                            Programas de Bienestar
                        </span>
                    </div>
                    
                    <button class="self-start px-4 py-2 bg-purple-600/80 hover:bg-purple-600 text-white text-sm font-semibold rounded-lg transition-all duration-300 backdrop-blur-sm border border-purple-500/50 hover:border-purple-400 transform hover:scale-105">
                        Ver Detalles
                    </button>
                </div>
            </div>
            
            <!-- Piscina en Azotea -->
            <div class="amenity-card group relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-sm border border-gray-700/50 hover:border-teal-500/50 transition-all duration-500 hover:transform hover:scale-[1.02] hover:shadow-2xl hover:shadow-teal-500/20">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" 
                     style="background-image: linear-gradient(45deg, rgba(0,0,0,0.6), rgba(0,0,0,0.4)), url('{{ asset('mis_imagenes/common-areas/rooftop-pool.jpg') }}');">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-90 group-hover:opacity-95 transition-opacity duration-500"></div>
                
                <div class="relative z-10 p-6 sm:p-8 h-80 sm:h-96 flex flex-col justify-end">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-teal-600/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-teal-500/30 group-hover:bg-teal-600/30 transition-all duration-300">
                        <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    
                    <h3 class="text-2xl sm:text-3xl font-bold text-white mb-3 sm:mb-4 group-hover:text-teal-300 transition-colors duration-300">
                        Piscina en Azotea
                    </h3>
                    <p class="text-gray-200 mb-4 sm:mb-6 text-sm sm:text-base leading-relaxed">
                        Impresionante piscina en azotea con vistas panorámicas de la ciudad, áreas de descanso y servicio junto a la piscina para la relajación definitiva.
                    </p>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="feature-tag px-3 py-1 bg-teal-600/20 text-teal-300 text-xs font-medium rounded-full border border-teal-500/30 backdrop-blur-sm">
                            Vistas de la Ciudad
                        </span>
                        <span class="feature-tag px-3 py-1 bg-teal-600/20 text-teal-300 text-xs font-medium rounded-full border border-teal-500/30 backdrop-blur-sm">
                            Áreas de Descanso
                        </span>
                        <span class="feature-tag px-3 py-1 bg-teal-600/20 text-teal-300 text-xs font-medium rounded-full border border-teal-500/30 backdrop-blur-sm">
                            Servicio de Piscina
                        </span>
                    </div>
                    
                    <button class="self-start px-4 py-2 bg-teal-600/80 hover:bg-teal-600 text-white text-sm font-semibold rounded-lg transition-all duration-300 backdrop-blur-sm border border-teal-500/50 hover:border-teal-400 transform hover:scale-105">
                        Ver Detalles
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Sección de estadísticas adicionales -->
        <div class="mt-16 sm:mt-20">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-bold text-blue-400 mb-2">24/7</div>
                    <div class="text-gray-300 text-sm sm:text-base">Acceso Disponible</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-bold text-green-400 mb-2">4</div>
                    <div class="text-gray-300 text-sm sm:text-base">Espacios Premium</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-bold text-purple-400 mb-2">100%</div>
                    <div class="text-gray-300 text-sm sm:text-base">Equipamiento Moderno</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-bold text-teal-400 mb-2">∞</div>
                    <div class="text-gray-300 text-sm sm:text-base">Experiencias Únicas</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Estilos CSS integrados para las amenidades -->
<style>
/* Estilos para las tarjetas de amenidades */
.amenity-card {
    position: relative;
    min-height: 320px;
}

.amenity-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 1rem;
    z-index: 1;
}

.amenity-card:hover::before {
    opacity: 1;
}

.feature-tag {
    transition: all 0.3s ease;
}

.feature-tag:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

/* Animaciones de entrada */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.amenity-card {
    animation: fadeInUp 0.6s ease-out;
}

.amenity-card:nth-child(1) { animation-delay: 0.1s; }
.amenity-card:nth-child(2) { animation-delay: 0.2s; }
.amenity-card:nth-child(3) { animation-delay: 0.3s; }
.amenity-card:nth-child(4) { animation-delay: 0.4s; }

/* Responsive adjustments */
@media (max-width: 640px) {
    .amenity-card {
        min-height: 280px;
    }
    
    .feature-tag {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
    }
}

@media (min-width: 1280px) {
    .amenity-card {
        min-height: 400px;
    }
}

/* Efectos de glassmorphism mejorados */
.amenity-card {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.amenity-card:hover {
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
}
</style>