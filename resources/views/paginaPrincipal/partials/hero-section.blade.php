<!-- Hero Section with Video Background -->
<section id="hero" class="relative h-screen overflow-hidden">
    <!-- Video Background -->
    <video id="hero-video" class="absolute inset-0 w-full h-full object-cover" autoplay muted loop playsinline>
        <source src="{{ asset('mis_imagenes/videos/video_logo.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    
    <!-- Fallback Background Image (hidden by default) -->
    <div id="hero-fallback" class="absolute inset-0 bg-cover bg-center hidden" style="background-image: url('{{ asset('mis_imagenes/logo/logo.png') }}')"></div>
    
    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    
    <!-- Hero Content -->
    <div class="relative z-10 flex items-center justify-center h-full text-center">
        <div class="hero-content">
            <h1 class="hero-title opacity-0">
                IntelliTower
            </h1>
            <p class="hero-subtitle opacity-0">
                Vida de Lujo Redefinida
            </p>
            <div class="hero-cta opacity-0">
                <a href="#apartments" class="btn-primary">
                    Explorar Apartamentos
                </a>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10">
        <div class="scroll-indicator animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </div>
</section>