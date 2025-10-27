<!-- Navegación Responsive Optimizada -->
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 navbar-premium transition-all duration-500">
    <div class="container mx-auto px-4 sm:px-6 py-3 sm:py-4">
        <div class="flex items-center justify-between">
            <!-- Logo Responsive -->
            <div class="logo-container flex-shrink-0">
                <h1 class="text-xl sm:text-2xl font-bold text-white logo-text">
                    IntelliTower
                </h1>
            </div>

            <!-- Navegación Desktop -->
            <div class="hidden lg:flex items-center space-x-6 xl:space-x-8">
                <a href="#hero" class="nav-link-premium text-sm xl:text-base">Inicio</a>
                <a href="#apartments" class="nav-link-premium text-sm xl:text-base">Apartamentos</a>
                <a href="#common-areas" class="nav-link-premium text-sm xl:text-base">Amenidades</a>
                <a href="#smart-living" class="nav-link-premium text-sm xl:text-base">Vida Inteligente</a>
                <a href="#neighborhood" class="nav-link-premium text-sm xl:text-base">Ubicación</a>
            </div>

            <!-- Botones de Autenticación Desktop -->
            <div class="hidden lg:flex items-center space-x-3">
                @auth
                    <a href="{{ route('filament.intelliTower.pages.dashboard') }}"
                        class="auth-btn auth-btn-login text-sm px-4 py-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2zm0 0V9a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('filament.intelliTower.auth.login') }}"
                        class="auth-btn auth-btn-login text-sm px-4 py-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Iniciar Sesión
                    </a>
                    
                @endauth
            </div>

            <!-- Botón Menu Hamburguesa -->
            <div class="lg:hidden">
                <button id="mobile-menu-btn"
                    class="mobile-menu-button p-2 rounded-md text-white hover:bg-white/10 transition-colors duration-200">
                    <svg id="menu-icon" class="w-6 h-6 transition-transform duration-300" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden transition-transform duration-300" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menu Móvil Mejorado -->
        <div id="mobile-menu" class="lg:hidden show hidden">
            <div class="pt-4 pb-6 space-y-1">
                <!-- Enlaces de Navegación Móvil -->
                <div class="mobile-nav-links space-y-2 mb-6">
                    <a href="#hero"
                        class="mobile-nav-link block px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-colors duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            Inicio
                        </span>
                    </a>
                    <a href="#apartments"
                        class="mobile-nav-link block px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-colors duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            Apartamentos
                        </span>
                    </a>
                    <a href="#common-areas"
                        class="mobile-nav-link block px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-colors duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                </path>
                            </svg>
                            Amenidades
                        </span>
                    </a>
                    <a href="#smart-living"
                        class="mobile-nav-link block px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-colors duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                            Vida Inteligente
                        </span>
                    </a>
                    <a href="#neighborhood"
                        class="mobile-nav-link block px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-colors duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ubicación
                        </span>
                    </a>
                </div>

                <!-- Botones Auth Móvil -->
                <div class="mobile-auth-buttons space-y-3 px-4 pt-4 border-t border-white/20">
                    <a href="{{ route('filament.intelliTower.auth.login') }}"
                        class="mobile-auth-btn mobile-auth-login w-full flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('filament.intelliTower.auth.login') }}"
                        class="mobile-auth-btn mobile-auth-register w-full flex items-center justify-center px-4 py-3 border-2 border-white/50 text-white hover:bg-white hover:text-black rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                        Registrarse
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Estilos CSS y JavaScript integrados -->
<style>
    /* Estilos para la navegación responsive */
    .navbar-premium {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.8) 100%);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-link-premium {
        color: #ffffff;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
        padding: 0.5rem 0;
    }

    .nav-link-premium:hover {
        color: #60a5fa;
        transform: translateY(-1px);
    }

    .nav-link-premium::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 50%;
        background: linear-gradient(90deg, #60a5fa, #3b82f6);
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .nav-link-premium:hover::after {
        width: 100%;
    }

    .auth-btn {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .auth-btn-login {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .auth-btn-login:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
    }

    .auth-btn-register {
        background: transparent;
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
    }

    .auth-btn-register:hover {
        background: white;
        color: #1f2937;
        border-color: white;
        transform: translateY(-2px);
    }

    .mobile-menu-container {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.9) 100%);
        backdrop-filter: blur(15px);
        border-radius: 1rem;
        margin-top: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
    }

    .mobile-menu-container.show {
        max-height: 650px;
        opacity: 1 !important;
        visibility: visible !important;
    }

    /* Animaciones para el menú hamburguesa */
    .mobile-menu-button:hover {
        transform: scale(1.05);
    }

    #menu-icon.rotate {
        transform: rotate(90deg);
    }

    #close-icon.rotate {
        transform: rotate(90deg);
    }

    /* Responsive breakpoints adicionales */
    @media (max-width: 640px) {
        .navbar-premium {
            padding: 0.75rem 0;
        }

        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .logo-text {
            font-size: 1.25rem;
        }
    }

    @media (min-width: 1024px) and (max-width: 1280px) {
        .nav-link-premium {
            font-size: 0.875rem;
        }

        .auth-btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos del DOM
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        const navbar = document.getElementById('navbar');

        // Estado del menú
        let isMenuOpen = false;

        // Toggle del menú móvil
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            isMenuOpen = !isMenuOpen;

            if (isMenuOpen) {
                // Abrir menú
                mobileMenu.classList.add('show');
                menuIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
                closeIcon.classList.add('rotate');
                // Prevenir scroll del body cuando el menú está abierto
                document.body.style.overflow = 'hidden';
            } else {
                // Cerrar menú
                mobileMenu.classList.remove('show');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                closeIcon.classList.remove('rotate');
                // Restaurar scroll del body
                document.body.style.overflow = '';
            }
        });

        // Cerrar menú al hacer click en un enlace
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-link, .mobile-auth-btn');
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (isMenuOpen) {
                    isMenuOpen = false;
                    mobileMenu.classList.remove('show');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    closeIcon.classList.remove('rotate');
                    document.body.style.overflow = '';
                }
            });
        });

        // Cerrar menú al hacer click fuera de él
        document.addEventListener('click', function(event) {
            if (isMenuOpen && !navbar.contains(event.target)) {
                isMenuOpen = false;
                mobileMenu.classList.remove('show');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                closeIcon.classList.remove('rotate');
                document.body.style.overflow = '';
            }
        });

        // Efecto de scroll en la navbar
        let lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > 100) {
                navbar.style.background =
                    'linear-gradient(135deg, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.9) 100%)';
                navbar.style.backdropFilter = 'blur(15px)';
            } else {
                navbar.style.background =
                    'linear-gradient(135deg, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.8) 100%)';
                navbar.style.backdropFilter = 'blur(10px)';
            }

            // Auto-hide navbar en scroll hacia abajo (opcional)
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                navbar.style.transform = 'translateY(-100%)';
            } else {
                navbar.style.transform = 'translateY(0)';
            }

            lastScrollTop = scrollTop;
        });

        // Smooth scroll para los enlaces de navegación
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offsetTop = target.offsetTop - 80; // Ajuste para navbar fija
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
