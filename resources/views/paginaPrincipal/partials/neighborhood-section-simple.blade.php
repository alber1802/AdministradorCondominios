<!-- Neighborhood Section Refactorizada -->
<section id="neighborhood" class="py-20">
    <div class="container mx-auto px-6">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                <span class="text-gradient">Ubicación</span> Premium
            </h2>
            <p class="text-xl text-gray-200 max-w-3xl mx-auto">
                Ubicado en el corazón de la ciudad, IntelliTower ofrece acceso incomparable a los mejores restaurantes, centros comerciales y lugares de entretenimiento.
            </p>
        </div>
        
        <!-- Grid de Mapa y Video -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-7xl mx-auto">
            <!-- Sección del Mapa -->
            <div class="neighborhood-map-container">
                <div class="glassmorphism-card p-6 h-full">
                    <h3 class="text-2xl font-bold text-white mb-6 text-center">Nuestra Ubicación</h3>
                    
                    <!-- Mapa Embebido -->
                    <div class="map-wrapper rounded-xl overflow-hidden shadow-2xl">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509374!2d144.9537353153167!3d-37.81720997975171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d43f1f3c5c7%3A0x5045675218ce7e33!2sMelbourne%20VIC%2C%20Australia!5e0!3m2!1sen!2sus!4v1635959999999!5m2!1sen!2sus" 
                            width="100%" 
                            height="400" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="rounded-xl">
                        </iframe>
                    </div>
                    
                    <!-- Información de Ubicación -->
                    <div class="mt-6 text-center">
                        <div class="space-y-2">
                            <p class="text-white font-semibold text-lg">IntelliTower</p>
                            <p class="text-gray-300">123 Avenida de Lujo</p>
                            <p class="text-gray-300">Distrito Centro, Ciudad</p>
                            <p class="text-gray-300">Estado 12345</p>
                        </div>
                        
                        <button class="btn-primary mt-4">
                            Ver Direcciones
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Sección del Video -->
            <div class="neighborhood-video-container">
                <div class="glassmorphism-card p-6 h-full">
                    <h3 class="text-2xl font-bold text-white mb-6 text-center">Descubre el Vecindario</h3>
                    
                    <!-- Video Container -->
                    <div class="video-wrapper rounded-xl overflow-hidden shadow-2xl">
                        <video 
                            class="w-full h-96 object-cover rounded-xl" 
                            controls 
                            poster="{{ asset('mis_imagenes/neighborhood/city-view.jpg') }}"
                            preload="metadata">
                            <source src="{{ asset('mis_imagenes/videos/video_logo_2.mp4') }}" type="video/mp4">
                            Tu navegador no soporta el elemento de video.
                        </video>
                    </div>
                    
                    <!-- Video Description -->
                    <div class="mt-6 text-center">
                        <p class="text-gray-300 text-lg mb-4">
                            Explora nuestro vibrante vecindario y descubre todo lo que hace de esta ubicación el lugar perfecto para vivir.
                        </p>
                        
                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-400">5 min</div>
                                <div class="text-sm text-gray-300">Al centro comercial</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-400">1 min</div>
                                <div class="text-sm text-gray-300">Al metro</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-400">3 min</div>
                                <div class="text-sm text-gray-300">A restaurantes</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-400">20 min</div>
                                <div class="text-sm text-gray-300">Al aeropuerto</div>
                            </div>
                        </div>
                        
                        <button class="btn-primary mt-6">
                            Agendar Tour del Vecindario
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>