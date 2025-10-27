// GSAP Animation Controller for IntelliTower Landing Page
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import ParallaxEffects from './parallax-effects';
import PerformanceOptimizer from './performance-optimizer';

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

class AnimationController {
    constructor() {
        this.isInitialized = false;
        this.reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.init();
    }

    init() {
        if (this.isInitialized) return;
        
        console.log('GSAP Animation Controller initialized');
        
        // Set GSAP defaults for smooth animations
        gsap.defaults({
            duration: 1,
            ease: "power2.out"
        });

        // Initialize performance optimizer first
        this.performanceOptimizer = new PerformanceOptimizer();
        
        // Initialize parallax and 3D effects
        this.parallaxEffects = new ParallaxEffects();

        // Initialize scroll-triggered animations
        this.initScrollAnimations();
        
        this.isInitialized = true;
    }

    initScrollAnimations() {
        // Create main scroll timeline
        this.mainTimeline = gsap.timeline({
            scrollTrigger: {
                trigger: "body",
                start: "top top",
                end: "bottom bottom",
                scrub: 1
            }
        });

        // Initialize section-specific animations
        this.initHeroAnimations();
        this.initApartmentAnimations();
        this.initCommonAreasAnimations();
        this.initSmartLivingAnimations();
        this.initNeighborhoodAnimations();
    }

    initHeroAnimations() {
        const heroTitle = document.querySelector('.hero-title');
        const heroSubtitle = document.querySelector('.hero-subtitle');
        const heroCta = document.querySelector('.hero-cta');

        if (heroTitle) {
            gsap.fromTo(heroTitle, 
                {
                    opacity: 0,
                    y: 50,
                    scale: 0.9
                },
                {
                    opacity: 1,
                    y: 0,
                    scale: 1,
                    duration: 1.5,
                    ease: "power3.out",
                    delay: 0.5
                }
            );
        }

        if (heroSubtitle) {
            gsap.fromTo(heroSubtitle,
                {
                    opacity: 0,
                    y: 30
                },
                {
                    opacity: 1,
                    y: 0,
                    duration: 1.2,
                    ease: "power2.out",
                    delay: 1
                }
            );
        }

        if (heroCta) {
            gsap.fromTo(heroCta,
                {
                    opacity: 0,
                    y: 20
                },
                {
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    ease: "power2.out",
                    delay: 1.5
                }
            );
        }
    }

    initApartmentAnimations() {
        const apartmentCards = document.querySelectorAll('.apartment-card');
        
        if (apartmentCards.length > 0) {
            gsap.fromTo(apartmentCards,
                {
                    opacity: 0,
                    y: 80,
                    scale: 0.8,
                    rotationX: 15
                },
                {
                    opacity: 1,
                    y: 0,
                    scale: 1,
                    rotationX: 0,
                    duration: 1,
                    ease: "power3.out",
                    stagger: {
                        amount: 0.8,
                        from: "start"
                    },
                    scrollTrigger: {
                        trigger: ".apartments-grid",
                        start: "top 75%",
                        end: "bottom 25%",
                        toggleActions: "play none none reverse"
                    }
                }
            );
        }

        // Add parallax effect to apartment section
        const apartmentsSection = document.getElementById('apartments');
        if (apartmentsSection) {
            gsap.to(apartmentsSection, {
                backgroundPosition: "50% 100%",
                ease: "none",
                scrollTrigger: {
                    trigger: apartmentsSection,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: true
                }
            });
        }
    }

    initCommonAreasAnimations() {
        const commonAreaPanels = document.querySelectorAll('.common-area-panel');
        
        if (commonAreaPanels.length > 0) {
            gsap.fromTo(commonAreaPanels,
                {
                    opacity: 0,
                    x: -100,
                    y: 50,
                    scale: 0.9,
                    rotationY: -15
                },
                {
                    opacity: 1,
                    x: 0,
                    y: 0,
                    scale: 1,
                    rotationY: 0,
                    duration: 1.2,
                    ease: "power3.out",
                    stagger: {
                        amount: 0.6,
                        from: "start"
                    },
                    scrollTrigger: {
                        trigger: ".common-areas-grid",
                        start: "top 80%",
                        end: "bottom 20%",
                        toggleActions: "play none none reverse"
                    }
                }
            );
        }

        // Add parallax effects to panel backgrounds
        commonAreaPanels.forEach((panel, index) => {
            const background = panel.querySelector('.panel-background');
            if (background) {
                const speed = 0.3 + (index * 0.1);
                
                gsap.to(background, {
                    yPercent: -30 * speed,
                    ease: "none",
                    scrollTrigger: {
                        trigger: panel,
                        start: "top bottom",
                        end: "bottom top",
                        scrub: 1
                    }
                });
            }
        });
    }

    initSmartLivingAnimations() {
        const smartFeatures = document.querySelectorAll('.smart-feature');
        
        if (smartFeatures.length > 0) {
            gsap.fromTo(smartFeatures,
                {
                    opacity: 0,
                    y: 60,
                    scale: 0.8
                },
                {
                    opacity: 1,
                    y: 0,
                    scale: 1,
                    duration: 1,
                    ease: "power3.out",
                    stagger: {
                        amount: 0.6,
                        from: "center"
                    },
                    scrollTrigger: {
                        trigger: ".smart-features-grid",
                        start: "top 75%",
                        end: "bottom 25%",
                        toggleActions: "play none none reverse"
                    }
                }
            );
        }
    }

    initNeighborhoodAnimations() {
        // Parallax effect for neighborhood background
        const neighborhoodBg = document.querySelector('#neighborhood .parallax-bg');
        if (neighborhoodBg) {
            gsap.to(neighborhoodBg, {
                yPercent: -30,
                ease: "none",
                scrollTrigger: {
                    trigger: "#neighborhood",
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 1
                }
            });
        }

        // Animate location highlights
        const locationHighlights = document.querySelectorAll('.location-highlight');
        if (locationHighlights.length > 0) {
            gsap.fromTo(locationHighlights,
                {
                    opacity: 0,
                    y: 80,
                    scale: 0.8
                },
                {
                    opacity: 1,
                    y: 0,
                    scale: 1,
                    duration: 1.2,
                    ease: "power3.out",
                    stagger: {
                        amount: 0.8,
                        from: "start"
                    },
                    scrollTrigger: {
                        trigger: ".location-info-grid",
                        start: "top 80%",
                        end: "bottom 20%",
                        toggleActions: "play none none reverse"
                    }
                }
            );
        }
    }

    // Get performance metrics from optimizer
    getPerformanceMetrics() {
        return this.performanceOptimizer ? this.performanceOptimizer.getPerformanceMetrics() : null;
    }

    // Method to refresh ScrollTrigger (useful for dynamic content)
    refresh() {
        ScrollTrigger.refresh();
    }

    // Method to kill all animations (cleanup)
    destroy() {
        if (this.performanceOptimizer) {
            this.performanceOptimizer.destroy();
        }
        
        if (this.parallaxEffects) {
            this.parallaxEffects.destroy();
        }
        
        ScrollTrigger.killAll();
        gsap.killTweensOf("*");
        this.isInitialized = false;
    }
}

// Export the animation controller
export default AnimationController;