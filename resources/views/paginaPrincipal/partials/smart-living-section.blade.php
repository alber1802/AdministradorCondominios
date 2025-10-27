<!-- Sección de Vida Inteligente -->
<section id="smart-living" class="relative py-20 bg-gradient-to-b from-gray-900 via-black to-gray-900 overflow-hidden">
    <!-- Efectos de Fondo -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500 rounded-full filter blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500 rounded-full filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <!-- Encabezado de Sección -->
        <div class="text-center mb-16 smart-living-header">
            <div class="inline-block mb-4">
                <span class="text-sm font-semibold text-blue-400 tracking-wider uppercase">Tecnología del Futuro</span>
            </div>
            <h2 class="text-4xl md:text-6xl font-bold text-white mb-6 bg-gradient-to-r from-blue-400 via-purple-400 to-teal-400 bg-clip-text text-transparent">
                Tecnología de Vida Inteligente
            </h2>
            <p class="text-xl text-gray-300 max-w-4xl mx-auto leading-relaxed">
                Experimenta el futuro de la vida con tecnología de vanguardia que anticipa tus necesidades y mejora cada momento de tu día.
            </p>
        </div>
        
        <!-- Demostración Interactiva de Tecnología -->
        <div class="smart-showcase-container mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Área de Demostración Interactiva -->
                <div class="smart-demo-area relative">
                    <div class="demo-screen bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-8 border border-gray-700 backdrop-filter backdrop-blur-lg">
                        <div class="demo-content" id="demo-content">
                            <!-- Contenido de Demostración por Defecto -->
                            <div class="text-center py-12">
                                <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mx-auto mb-6 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-4">Demostración Interactiva de Tecnología</h3>
                                <p class="text-gray-300">Haz clic en cualquier característica para verla en acción</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Panel de Selección de Características -->
                <div class="feature-selection-panel">
                    <h3 class="text-2xl font-bold text-white mb-8">Explora las Características Inteligentes</h3>
                    <div class="space-y-4">
                        <button class="feature-selector w-full text-left p-4 rounded-xl bg-white bg-opacity-5 border border-gray-700 hover:bg-opacity-10 transition-all duration-300 group" data-feature="access-control">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold">Control de Acceso Inteligente</h4>
                                    <p class="text-gray-400 text-sm">Sistema de entrada sin llaves</p>
                                </div>
                            </div>
                        </button>
                        
                        <button class="feature-selector w-full text-left p-4 rounded-xl bg-white bg-opacity-5 border border-gray-700 hover:bg-opacity-10 transition-all duration-300 group" data-feature="energy-monitoring">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold">Monitoreo de Energía</h4>
                                    <p class="text-gray-400 text-sm">Seguimiento de consumo en tiempo real</p>
                                </div>
                            </div>
                        </button>
                        
                        <button class="feature-selector w-full text-left p-4 rounded-xl bg-white bg-opacity-5 border border-gray-700 hover:bg-opacity-10 transition-all duration-300 group" data-feature="automation">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v0"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold">Automatización del Hogar</h4>
                                    <p class="text-gray-400 text-sm">Sistemas de control inteligente</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Grid de Características Inteligentes -->
        <div class="smart-features-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Control de Acceso Inteligente -->
            <div class="smart-feature-card group relative bg-gradient-to-br from-blue-900/20 to-blue-800/10 backdrop-filter backdrop-blur-lg rounded-2xl p-8 text-center border border-blue-500/20 hover:border-blue-400/40 transition-all duration-500 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="smart-icon mb-6 flex justify-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-blue-500/50 transition-all duration-500 group-hover:rotate-3 group-hover:scale-110">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-blue-300 transition-colors duration-300">Control de Acceso Inteligente</h3>
                    <p class="text-gray-300 mb-6 leading-relaxed">Entrada sin llaves con integración de aplicación móvil y sistema avanzado de gestión de visitantes.</p>
                    <div class="smart-features space-y-3">
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-blue-300">
                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                            <span>Control por App Móvil</span>
                        </div>
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-blue-300">
                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                            <span>Gestión de Visitantes</span>
                        </div>
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-blue-300">
                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                            <span>Registros de Seguridad</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Monitoreo de Energía -->
            <div class="smart-feature-card group relative bg-gradient-to-br from-green-900/20 to-green-800/10 backdrop-filter backdrop-blur-lg rounded-2xl p-8 text-center border border-green-500/20 hover:border-green-400/40 transition-all duration-500 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-green-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="smart-icon mb-6 flex justify-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-green-500/50 transition-all duration-500 group-hover:rotate-3 group-hover:scale-110">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-green-300 transition-colors duration-300">Monitoreo de Energía</h3>
                    <p class="text-gray-300 mb-6 leading-relaxed">Seguimiento de consumo energético en tiempo real con recomendaciones de optimización impulsadas por IA.</p>
                    <div class="smart-features space-y-3">
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-green-300">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span>Monitoreo en Tiempo Real</span>
                        </div>
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-green-300">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                            <span>Análisis de Uso</span>
                        </div>
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-green-300">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                            <span>Optimización de Costos</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Automatización del Hogar -->
            <div class="smart-feature-card group relative bg-gradient-to-br from-purple-900/20 to-purple-800/10 backdrop-filter backdrop-blur-lg rounded-2xl p-8 text-center border border-purple-500/20 hover:border-purple-400/40 transition-all duration-500 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="smart-icon mb-6 flex justify-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-purple-500/50 transition-all duration-500 group-hover:rotate-3 group-hover:scale-110">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v0"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-purple-300 transition-colors duration-300">Automatización del Hogar</h3>
                    <p class="text-gray-300 mb-6 leading-relaxed">Control inteligente de iluminación, clima y electrodomésticos a través de integración por voz y aplicación.</p>
                    <div class="smart-features space-y-3">
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-purple-300">
                            <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                            <span>Control por Voz</span>
                        </div>
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-purple-300">
                            <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                            <span>Gestión de Clima</span>
                        </div>
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-purple-300">
                            <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                            <span>Automatización de Iluminación</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Integración de Seguridad -->
            <div class="smart-feature-card group relative bg-gradient-to-br from-red-900/20 to-red-800/10 backdrop-filter backdrop-blur-lg rounded-2xl p-8 text-center border border-red-500/20 hover:border-red-400/40 transition-all duration-500 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-red-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="smart-icon mb-6 flex justify-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-red-500/50 transition-all duration-500 group-hover:rotate-3 group-hover:scale-110">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-red-300 transition-colors duration-300">Integración de Seguridad</h3>
                    <p class="text-gray-300 mb-6 leading-relaxed">Sistema de seguridad avanzado con monitoreo 24/7 y respuesta inteligente a emergencias.</p>
                    <div class="smart-features space-y-3">
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-red-300">
                            <div class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></div>
                            <span>Monitoreo 24/7</span>
                        </div>
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-red-300">
                            <div class="w-2 h-2 bg-red-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                            <span>Respuesta a Emergencias</span>
                        </div>
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-red-300">
                            <div class="w-2 h-2 bg-red-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                            <span>Cámaras Inteligentes</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tecnología de Bienestar -->
            <div class="smart-feature-card group relative bg-gradient-to-br from-teal-900/20 to-teal-800/10 backdrop-filter backdrop-blur-lg rounded-2xl p-8 text-center border border-teal-500/20 hover:border-teal-400/40 transition-all duration-500 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-teal-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="smart-icon mb-6 flex justify-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-teal-500/50 transition-all duration-500 group-hover:rotate-3 group-hover:scale-110">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-teal-300 transition-colors duration-300">Tecnología de Bienestar</h3>
                    <p class="text-gray-300 mb-6 leading-relaxed">Monitoreo de calidad del aire y optimización del bienestar para ambientes de vida más saludables.</p>
                    <div class="smart-features space-y-3">
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-teal-300">
                            <div class="w-2 h-2 bg-teal-400 rounded-full animate-pulse"></div>
                            <span>Monitoreo de Calidad del Aire</span>
                        </div>
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-teal-300">
                            <div class="w-2 h-2 bg-teal-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                            <span>Análisis de Salud</span>
                        </div>
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-teal-300">
                            <div class="w-2 h-2 bg-teal-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                            <span>Recomendaciones de Bienestar</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Estacionamiento Inteligente -->
            <div class="smart-feature-card group relative bg-gradient-to-br from-orange-900/20 to-orange-800/10 backdrop-filter backdrop-blur-lg rounded-2xl p-8 text-center border border-orange-500/20 hover:border-orange-400/40 transition-all duration-500 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="smart-icon mb-6 flex justify-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-orange-500/50 transition-all duration-500 group-hover:rotate-3 group-hover:scale-110">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-orange-300 transition-colors duration-300">Estacionamiento Inteligente</h3>
                    <p class="text-gray-300 mb-6 leading-relaxed">Sistema de estacionamiento automatizado con disponibilidad en tiempo real y características de reserva inteligente.</p>
                    <div class="smart-features space-y-3">
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-orange-300">
                            <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                            <span>Estacionamiento Automatizado</span>
                        </div>
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-orange-300">
                            <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                            <span>Disponibilidad en Tiempo Real</span>
                        </div>
                        <div class="feature-item flex items-center justify-center space-x-2 text-sm text-orange-300">
                            <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                            <span>Sistema de Reservas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
</section>