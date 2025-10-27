// JavaScript simplificado para IntelliTower
document.addEventListener('DOMContentLoaded', function() {
    
    // Inicializar componentes
    initNavigation();
    initHeroSection();
    initApartmentCards();
    initCommonAreas();
    initSmartFeatures();
    initModal();
    
   // console.log('IntelliTower - Página cargada correctamente');
});

// Navegación Mejorada
function initNavigation() {
    const navbar = document.getElementById('navbar');
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    // Efecto de scroll mejorado en navbar
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
    
    // Menu móvil con animación
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
                setTimeout(() => {
                    mobileMenu.style.opacity = '1';
                    mobileMenu.style.transform = 'translateY(0)';
                }, 10);
            } else {
                mobileMenu.style.opacity = '0';
                mobileMenu.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
            }
        });
    }
    
    // Smooth scroll para enlaces de navegación
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                // Cerrar menu móvil si está abierto
                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.style.opacity = '0';
                    mobileMenu.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 300);
                }
                
                // Scroll suave
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Actualizar enlace activo
                updateActiveNavLink(this);
            }
        });
    });
    
    // Detectar sección activa al hacer scroll
    window.addEventListener('scroll', debounce(updateActiveNavOnScroll, 100));
}

// Función para actualizar enlace activo
function updateActiveNavLink(activeLink) {
    // Remover clase activa de todos los enlaces
    document.querySelectorAll('.nav-link-premium, .mobile-nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    // Añadir clase activa al enlace actual
    activeLink.classList.add('active');
    
    // Si es móvil, también actualizar el enlace correspondiente en desktop
    const href = activeLink.getAttribute('href');
    const correspondingLink = document.querySelector(`.nav-link-premium[href="${href}"], .mobile-nav-link[href="${href}"]`);
    if (correspondingLink && correspondingLink !== activeLink) {
        correspondingLink.classList.add('active');
    }
}

// Función para detectar sección activa en scroll
function updateActiveNavOnScroll() {
    const sections = document.querySelectorAll('section[id]');
    const scrollPos = window.scrollY + 100;
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute('id');
        
        if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
            const activeLink = document.querySelector(`a[href="#${sectionId}"]`);
            if (activeLink) {
                updateActiveNavLink(activeLink);
            }
        }
    });
}

// Sección Hero
function initHeroSection() {
    const heroTitle = document.querySelector('.hero-title');
    const heroSubtitle = document.querySelector('.hero-subtitle');
    const heroCta = document.querySelector('.hero-cta');
    const heroVideo = document.getElementById('hero-video');
    const heroFallback = document.getElementById('hero-fallback');
    
    // Animación simple de entrada
    setTimeout(() => {
        if (heroTitle) {
            heroTitle.style.opacity = '1';
            heroTitle.classList.add('fade-in');
        }
    }, 300);
    
    setTimeout(() => {
        if (heroSubtitle) {
            heroSubtitle.style.opacity = '1';
            heroSubtitle.classList.add('fade-in');
        }
    }, 600);
    
    setTimeout(() => {
        if (heroCta) {
            heroCta.style.opacity = '1';
            heroCta.classList.add('fade-in');
        }
    }, 900);
    
    // Fallback para video
    if (heroVideo && heroFallback) {
        heroVideo.addEventListener('error', function() {
            heroVideo.style.display = 'none';
            heroFallback.classList.remove('hidden');
        });
    }
}

// Tarjetas de apartamentos
function initApartmentCards() {
    const apartmentCards = document.querySelectorAll('.apartment-card');
    
    // Usar Intersection Observer para animaciones suaves
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('slide-up');
                }, index * 150);
            }
        });
    }, { threshold: 0.1 });
    
    apartmentCards.forEach(card => {
        observer.observe(card);
        
        // Efecto hover mejorado
        card.addEventListener('mouseenter', function() {
            const img = card.querySelector('img');
            if (img) {
                img.style.transform = 'scale(1.08)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const img = card.querySelector('img');
            if (img) {
                img.style.transform = 'scale(1)';
            }
        });
    });
}

// Áreas comunes
function initCommonAreas() {
    const commonAreaPanels = document.querySelectorAll('.common-area-panel');
    
    commonAreaPanels.forEach((panel, index) => {
        // Observador de intersección para animaciones
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('slide-up');
                    }, index * 150);
                }
            });
        }, { threshold: 0.1 });
        
        observer.observe(panel);
        
        // Click para mostrar detalles
        panel.addEventListener('click', function() {
            const details = panel.querySelector('.panel-details');
            if (details) {
                details.classList.toggle('hidden');
            }
        });
    });
}

// Características inteligentes
function initSmartFeatures() {
    const smartCards = document.querySelectorAll('.smart-feature-card');
    const featureSelectors = document.querySelectorAll('.feature-selector');
    
    // Animación de entrada para tarjetas
    smartCards.forEach((card, index) => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('slide-up');
                    }, index * 100);
                }
            });
        }, { threshold: 0.1 });
        
        observer.observe(card);
    });
    
    // Selectores de características
    featureSelectors.forEach(selector => {
        selector.addEventListener('click', function() {
            // Remover clase activa de todos
            featureSelectors.forEach(s => s.classList.remove('active'));
            // Agregar clase activa al seleccionado
            this.classList.add('active');
            
            // Cambiar contenido del demo (simplificado)
            const demoContent = document.querySelector('.demo-content');
            if (demoContent) {
                demoContent.style.opacity = '0.5';
                setTimeout(() => {
                    demoContent.style.opacity = '1';
                }, 300);
            }
        });
    });
}

// Modal de apartamentos
function initModal() {
    const modal = document.getElementById('apartment-modal');
    const closeBtn = document.getElementById('close-modal');
    
    // Función para abrir modal
    window.openApartmentModal = function(apartmentType) {
        if (!modal) return;
        
        // Datos de apartamentos simplificados
        const apartmentData = {
            studio: {
                title: 'Studio Apartment',
                price: '$1,200/month',
                size: '450 sq ft',
                bedrooms: 'Studio',
                bathrooms: '1',
                features: ['Modern Kitchen', 'City View', 'Smart Home', 'Gym Access'],
                image: '/mis_imagenes/apartments/studio.jpg'
            },
            onebr: {
                title: 'One Bedroom',
                price: '$1,800/month',
                size: '650 sq ft',
                bedrooms: '1',
                bathrooms: '1',
                features: ['Spacious Living', 'Balcony', 'Smart Home', 'Pool Access'],
                image: '/mis_imagenes/apartments/one-bedroom.jpg'
            },
            twobr: {
                title: 'Two Bedroom',
                price: '$2,500/month',
                size: '950 sq ft',
                bedrooms: '2',
                bathrooms: '2',
                features: ['Master Suite', 'Large Balcony', 'Smart Home', 'Premium Amenities'],
                image: '/mis_imagenes/apartments/two-bedroom.webp'
            }
        };
        
        const data = apartmentData[apartmentType];
        if (!data) return;
        
        // Actualizar contenido del modal
        modal.querySelector('.modal-title').textContent = data.title;
        modal.querySelector('#modal-price').textContent = data.price;
        modal.querySelector('#modal-size').textContent = data.size;
        modal.querySelector('#modal-bedrooms').textContent = data.bedrooms;
        modal.querySelector('#modal-bathrooms').textContent = data.bathrooms;
        
        const featuresContainer = modal.querySelector('#modal-features');
        if (featuresContainer) {
            featuresContainer.innerHTML = data.features.map(feature => 
                `<span class="feature-tag">${feature}</span>`
            ).join('');
        }
        
        const modalImage = modal.querySelector('#modal-image');
        if (modalImage) {
            modalImage.src = data.image;
            modalImage.alt = data.title;
        }
        
        // Mostrar modal
        modal.classList.remove('hidden');
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
        
        document.body.style.overflow = 'hidden';
    };
    
    // Función para cerrar modal
    window.closeApartmentModal = function() {
        if (!modal) return;
        
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    };
    
    // Event listeners
    if (closeBtn) {
        closeBtn.addEventListener('click', closeApartmentModal);
    }
    
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeApartmentModal();
            }
        });
    }
    
    // Cerrar con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeApartmentModal();
        }
    });
}

// Utilidades
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Lazy loading para imágenes
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// Inicializar lazy loading cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initLazyLoading);