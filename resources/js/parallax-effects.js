// Parallax and 3D Effects Module for IntelliTower Landing Page
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

class ParallaxEffects {
    constructor() {
        this.parallaxElements = [];
        this.transform3DElements = [];
        this.init();
    }

    init() {
        // Register ScrollTrigger plugin
        gsap.registerPlugin(ScrollTrigger);
        
        // Initialize parallax effects
        this.initParallaxBackgrounds();
        this.initParallaxElements();
        
        // Initialize 3D transformations
        this.init3DTransformations();
        this.initInteractive3DElements();
        
        // Initialize cinematic easing functions
        this.initCinematicEasing();
    }

    initParallaxBackgrounds() {
        // Hero video parallax
        const heroVideo = document.querySelector('#hero video');
        if (heroVideo) {
            gsap.to(heroVideo, {
                yPercent: -20,
                ease: "none",
                scrollTrigger: {
                    trigger: "#hero",
                    start: "top top",
                    end: "bottom top",
                    scrub: 1
                }
            });
        }

        // Apartment section background parallax
        const apartmentsBg = document.querySelector('#apartments .parallax-bg');
        if (apartmentsBg) {
            gsap.to(apartmentsBg, {
                yPercent: -30,
                scale: 1.1,
                ease: "none",
                scrollTrigger: {
                    trigger: "#apartments",
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 1
                }
            });
        }

        // Common areas background parallax
        const commonAreasBg = document.querySelector('#common-areas .parallax-bg');
        if (commonAreasBg) {
            gsap.to(commonAreasBg, {
                yPercent: -25,
                ease: "none",
                scrollTrigger: {
                    trigger: "#common-areas",
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 1
                }
            });
        }

        // Smart living section background parallax
        const smartLivingBg = document.querySelector('#smart-living .parallax-bg');
        if (smartLivingBg) {
            gsap.to(smartLivingBg, {
                yPercent: -35,
                rotationZ: 2,
                ease: "none",
                scrollTrigger: {
                    trigger: "#smart-living",
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 1
                }
            });
        }

        // Neighborhood section background parallax
        const neighborhoodBg = document.querySelector('#neighborhood .parallax-bg');
        if (neighborhoodBg) {
            gsap.to(neighborhoodBg, {
                yPercent: -40,
                scale: 1.05,
                ease: "none",
                scrollTrigger: {
                    trigger: "#neighborhood",
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 1
                }
            });
        }
    }

    initParallaxElements() {
        // Multi-layer parallax for depth
        const parallaxLayers = document.querySelectorAll('[data-parallax]');
        
        parallaxLayers.forEach(layer => {
            const speed = parseFloat(layer.dataset.parallax) || 0.5;
            const direction = layer.dataset.parallaxDirection || 'up';
            const rotation = parseFloat(layer.dataset.parallaxRotation) || 0;
            
            let yPercent = speed * -50;
            if (direction === 'down') yPercent = speed * 50;
            
            gsap.to(layer, {
                yPercent: yPercent,
                rotationZ: rotation,
                ease: "none",
                scrollTrigger: {
                    trigger: layer,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 1
                }
            });
        });

        // Floating elements parallax
        const floatingElements = document.querySelectorAll('.floating-element');
        floatingElements.forEach((element, index) => {
            const speed = 0.3 + (index * 0.1);
            const rotationSpeed = 0.5 + (index * 0.2);
            
            gsap.to(element, {
                yPercent: -30 * speed,
                rotationZ: 360 * rotationSpeed,
                ease: "none",
                scrollTrigger: {
                    trigger: element,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 1
                }
            });
        });
    }

    init3DTransformations() {
        // 3D card transformations for apartments
        const apartmentCards = document.querySelectorAll('.apartment-card');
        apartmentCards.forEach((card, index) => {
            // Entrance animation with 3D effect
            gsap.fromTo(card,
                {
                    opacity: 0,
                    y: 100,
                    rotationX: 45,
                    rotationY: 15,
                    scale: 0.8,
                    transformPerspective: 1000
                },
                {
                    opacity: 1,
                    y: 0,
                    rotationX: 0,
                    rotationY: 0,
                    scale: 1,
                    duration: 1.2,
                    ease: "power3.out",
                    delay: index * 0.2,
                    scrollTrigger: {
                        trigger: card,
                        start: "top 80%",
                        toggleActions: "play none none reverse"
                    }
                }
            );

            // 3D hover effects
            this.add3DHoverEffect(card);
        });

        // 3D transformations for common area panels
        const commonAreaPanels = document.querySelectorAll('.common-area-panel');
        commonAreaPanels.forEach((panel, index) => {
            gsap.fromTo(panel,
                {
                    opacity: 0,
                    x: -150,
                    rotationY: -30,
                    scale: 0.9,
                    transformPerspective: 1000
                },
                {
                    opacity: 1,
                    x: 0,
                    rotationY: 0,
                    scale: 1,
                    duration: 1.5,
                    ease: "power3.out",
                    delay: index * 0.3,
                    scrollTrigger: {
                        trigger: panel,
                        start: "top 75%",
                        toggleActions: "play none none reverse"
                    }
                }
            );

            this.add3DHoverEffect(panel, { intensity: 0.7 });
        });

        // 3D smart living features
        const smartFeatures = document.querySelectorAll('.smart-feature');
        smartFeatures.forEach((feature, index) => {
            gsap.fromTo(feature,
                {
                    opacity: 0,
                    y: 80,
                    rotationX: 30,
                    scale: 0.8,
                    transformPerspective: 1000
                },
                {
                    opacity: 1,
                    y: 0,
                    rotationX: 0,
                    scale: 1,
                    duration: 1,
                    ease: "back.out(1.7)",
                    delay: index * 0.15,
                    scrollTrigger: {
                        trigger: feature,
                        start: "top 80%",
                        toggleActions: "play none none reverse"
                    }
                }
            );

            this.add3DHoverEffect(feature, { intensity: 1.2 });
        });
    }

    add3DHoverEffect(element, options = {}) {
        const defaults = {
            intensity: 1,
            rotationRange: 10,
            scaleAmount: 1.05,
            duration: 0.4
        };
        
        const settings = { ...defaults, ...options };
        
        element.addEventListener('mouseenter', (e) => {
            const rect = element.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            
            const mouseX = e.clientX - centerX;
            const mouseY = e.clientY - centerY;
            
            const rotateX = (mouseY / rect.height) * settings.rotationRange * settings.intensity;
            const rotateY = -(mouseX / rect.width) * settings.rotationRange * settings.intensity;
            
            gsap.to(element, {
                rotationX: rotateX,
                rotationY: rotateY,
                scale: settings.scaleAmount,
                z: 50 * settings.intensity,
                duration: settings.duration,
                ease: "power2.out",
                transformPerspective: 1000
            });
        });

        element.addEventListener('mousemove', (e) => {
            const rect = element.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            
            const mouseX = e.clientX - centerX;
            const mouseY = e.clientY - centerY;
            
            const rotateX = (mouseY / rect.height) * settings.rotationRange * settings.intensity;
            const rotateY = -(mouseX / rect.width) * settings.rotationRange * settings.intensity;
            
            gsap.to(element, {
                rotationX: rotateX,
                rotationY: rotateY,
                duration: 0.1,
                ease: "power2.out"
            });
        });

        element.addEventListener('mouseleave', () => {
            gsap.to(element, {
                rotationX: 0,
                rotationY: 0,
                scale: 1,
                z: 0,
                duration: settings.duration,
                ease: "power2.out"
            });
        });
    }

    initInteractive3DElements() {
        // 3D navigation items
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            this.add3DHoverEffect(item, { 
                intensity: 0.5, 
                rotationRange: 5,
                scaleAmount: 1.1 
            });
        });

        // 3D buttons and CTAs
        const buttons = document.querySelectorAll('.btn-3d, .cta-button');
        buttons.forEach(button => {
            this.add3DHoverEffect(button, { 
                intensity: 0.8, 
                rotationRange: 8,
                scaleAmount: 1.08 
            });
            
            // Click animation
            button.addEventListener('click', () => {
                gsap.to(button, {
                    scale: 0.95,
                    duration: 0.1,
                    ease: "power2.out",
                    yoyo: true,
                    repeat: 1
                });
            });
        });

        // 3D location highlights
        const locationHighlights = document.querySelectorAll('.location-highlight');
        locationHighlights.forEach(highlight => {
            this.add3DHoverEffect(highlight, { 
                intensity: 0.9, 
                rotationRange: 12,
                scaleAmount: 1.06 
            });
        });
    }

    initCinematicEasing() {
        // Custom cinematic easing functions
        gsap.registerEase("cinematicIn", "power4.in");
        gsap.registerEase("cinematicOut", "power4.out");
        gsap.registerEase("cinematicInOut", "power4.inOut");
        gsap.registerEase("cinematicBack", "back.out(2.5)");
        gsap.registerEase("cinematicElastic", "elastic.out(1, 0.5)");
        gsap.registerEase("cinematicBounce", "bounce.out");

        // Apply cinematic easing to scroll-triggered animations
        ScrollTrigger.batch(".cinematic-element", {
            onEnter: (elements) => {
                gsap.fromTo(elements, 
                    {
                        opacity: 0,
                        y: 100,
                        scale: 0.8
                    },
                    {
                        opacity: 1,
                        y: 0,
                        scale: 1,
                        duration: 1.5,
                        ease: "cinematicOut",
                        stagger: 0.2
                    }
                );
            },
            onLeave: (elements) => {
                gsap.to(elements, {
                    opacity: 0.3,
                    scale: 0.95,
                    duration: 0.5,
                    ease: "cinematicIn"
                });
            },
            onEnterBack: (elements) => {
                gsap.to(elements, {
                    opacity: 1,
                    scale: 1,
                    duration: 0.5,
                    ease: "cinematicOut"
                });
            }
        });
    }

    // Advanced parallax with mouse movement
    initMouseParallax() {
        const parallaxElements = document.querySelectorAll('[data-mouse-parallax]');
        
        document.addEventListener('mousemove', (e) => {
            const mouseX = e.clientX / window.innerWidth - 0.5;
            const mouseY = e.clientY / window.innerHeight - 0.5;
            
            parallaxElements.forEach(element => {
                const speed = parseFloat(element.dataset.mouseParallax) || 1;
                
                gsap.to(element, {
                    x: mouseX * 50 * speed,
                    y: mouseY * 50 * speed,
                    duration: 0.5,
                    ease: "power2.out"
                });
            });
        });
    }

    // Depth-based parallax layers
    initDepthParallax() {
        const depthLayers = document.querySelectorAll('[data-depth]');
        
        depthLayers.forEach(layer => {
            const depth = parseFloat(layer.dataset.depth) || 1;
            const speed = depth * 0.5;
            
            gsap.to(layer, {
                yPercent: -50 * speed,
                ease: "none",
                scrollTrigger: {
                    trigger: layer,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 1
                }
            });
        });
    }

    // Refresh all parallax effects
    refresh() {
        ScrollTrigger.refresh();
    }

    // Destroy all parallax effects
    destroy() {
        this.parallaxElements = [];
        this.transform3DElements = [];
        ScrollTrigger.killAll();
    }
}

export default ParallaxEffects;