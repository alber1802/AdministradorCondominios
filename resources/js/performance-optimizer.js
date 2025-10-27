// Performance Optimizer for GSAP Animations
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

class PerformanceOptimizer {
    constructor() {
        this.frameRate = 60;
        this.frameCount = 0;
        this.lastTime = performance.now();
        this.averageFPS = 60;
        this.performanceMode = 'high'; // high, medium, low
        this.reducedMotion = false;
        this.deviceCapabilities = {};

        this.init();
    }

    init() {
        // Detect device capabilities
        this.detectDeviceCapabilities();

        // Check for reduced motion preference
        this.checkReducedMotionPreference();

        // Initialize performance monitoring
        this.initPerformanceMonitoring();

        // Set up efficient scroll handling
        this.initEfficientScrollHandling();

        // Optimize GSAP settings
        this.optimizeGSAPSettings();

        console.log(`Performance Optimizer initialized - Mode: ${this.performanceMode}`);
    }

    detectDeviceCapabilities() {
        // Detect GPU acceleration support
        const canvas = document.createElement('canvas');
        const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
        this.deviceCapabilities.webgl = !!gl;

        // Detect device memory (if available)
        this.deviceCapabilities.memory = navigator.deviceMemory || 4; // Default to 4GB

        // Detect hardware concurrency
        this.deviceCapabilities.cores = navigator.hardwareConcurrency || 4;

        // Detect connection speed
        const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
        this.deviceCapabilities.connectionType = connection ? connection.effectiveType : '4g';

        // Detect screen size and pixel ratio
        this.deviceCapabilities.screenWidth = window.screen.width;
        this.deviceCapabilities.screenHeight = window.screen.height;
        this.deviceCapabilities.pixelRatio = window.devicePixelRatio || 1;

        // Determine performance mode based on capabilities
        this.determinePerformanceMode();
    }

    determinePerformanceMode() {
        let score = 0;

        // GPU acceleration
        if (this.deviceCapabilities.webgl) score += 2;

        // Memory
        if (this.deviceCapabilities.memory >= 8) score += 2;
        else if (this.deviceCapabilities.memory >= 4) score += 1;

        // CPU cores
        if (this.deviceCapabilities.cores >= 8) score += 2;
        else if (this.deviceCapabilities.cores >= 4) score += 1;

        // Connection
        if (this.deviceCapabilities.connectionType === '4g') score += 1;

        // Screen resolution
        const totalPixels = this.deviceCapabilities.screenWidth * this.deviceCapabilities.screenHeight;
        if (totalPixels > 2073600) score -= 1; // 1920x1080

        // Set performance mode
        if (score >= 6) {
            this.performanceMode = 'high';
        } else if (score >= 3) {
            this.performanceMode = 'medium';
        } else {
            this.performanceMode = 'low';
        }
    }

    checkReducedMotionPreference() {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        this.reducedMotion = prefersReducedMotion.matches;

        // Listen for changes
        prefersReducedMotion.addEventListener('change', (e) => {
            this.reducedMotion = e.matches;
            this.applyReducedMotionSettings();
        });

        if (this.reducedMotion) {
            this.applyReducedMotionSettings();
        }
    }

    applyReducedMotionSettings() {
        if (this.reducedMotion) {
            // Disable complex animations
            gsap.globalTimeline.timeScale(0.1);

            // Disable parallax effects
            ScrollTrigger.getAll().forEach(trigger => {
                if (trigger.vars.scrub) {
                    trigger.kill();
                }
            });

            // Add reduced motion class to body
            document.body.classList.add('reduced-motion');

            console.log('Reduced motion preferences applied');
        } else {
            // Restore normal animation speed
            gsap.globalTimeline.timeScale(1);
            document.body.classList.remove('reduced-motion');
        }
    }

    initPerformanceMonitoring() {
        let frameCount = 0;
        let lastTime = performance.now();
        let fpsHistory = [];

        const monitorPerformance = () => {
            frameCount++;
            const currentTime = performance.now();

            if (currentTime - lastTime >= 1000) {
                const fps = Math.round((frameCount * 1000) / (currentTime - lastTime));
                fpsHistory.push(fps);

                // Keep only last 10 measurements
                if (fpsHistory.length > 10) {
                    fpsHistory.shift();
                }

                // Calculate average FPS
                this.averageFPS = fpsHistory.reduce((a, b) => a + b, 0) / fpsHistory.length;

                // Adjust performance if needed
                this.adjustPerformanceBasedOnFPS(this.averageFPS);

                frameCount = 0;
                lastTime = currentTime;
            }

            requestAnimationFrame(monitorPerformance);
        };

        requestAnimationFrame(monitorPerformance);
    }

    adjustPerformanceBasedOnFPS(fps) {
        if (fps < 30 && this.performanceMode !== 'low') {
            console.warn(`Low FPS detected (${fps}). Reducing animation complexity.`);
            this.reduceAnimationComplexity();
        } else if (fps > 55 && this.performanceMode === 'low') {
            console.log(`Good FPS detected (${fps}). Increasing animation quality.`);
            this.increaseAnimationQuality();
        }
    }

    reduceAnimationComplexity() {
        // Reduce animation durations
        ScrollTrigger.getAll().forEach(trigger => {
            if (trigger.animation && trigger.animation.duration() > 1) {
                trigger.animation.duration(trigger.animation.duration() * 0.7);
            }
        });

        // Disable some parallax effects
        const parallaxElements = document.querySelectorAll('[data-parallax]');
        parallaxElements.forEach(element => {
            if (parseFloat(element.dataset.parallax) > 0.5) {
                element.style.transform = 'none';
            }
        });

        // Reduce stagger amounts
        gsap.globalTimeline.timeScale(1.3);

        this.performanceMode = 'low';
    }

    increaseAnimationQuality() {
        // Restore normal animation speeds
        gsap.globalTimeline.timeScale(1);

        // Re-enable parallax effects
        ScrollTrigger.refresh();

        this.performanceMode = 'medium';
    }

    initEfficientScrollHandling() {
        // Use passive event listeners for better performance
        let ticking = false;

        const optimizedScrollHandler = () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    // Batch DOM reads and writes
                    this.batchScrollUpdates();
                    ticking = false;
                });
                ticking = true;
            }
        };

        // Use passive listeners
        window.addEventListener('scroll', optimizedScrollHandler, { passive: true });
        window.addEventListener('resize', this.debounce(() => {
            ScrollTrigger.refresh();
        }, 250), { passive: true });
    }

    batchScrollUpdates() {
        // Batch all scroll-related DOM operations
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        // Update scroll-dependent elements efficiently
        requestAnimationFrame(() => {
            // Batch DOM writes here
            this.updateScrollDependentElements(scrollTop);
        });
    }

    updateScrollDependentElements(scrollTop) {
        // Update navigation active state
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (scrollTop >= sectionTop - 200) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    }

    optimizeGSAPSettings() {
        // Set optimal GSAP configuration based on performance mode
        switch (this.performanceMode) {
            case 'high':
                gsap.config({
                    force3D: true,
                    nullTargetWarn: false,
                    trialWarn: false
                });
                break;

            case 'medium':
                gsap.config({
                    force3D: 'auto',
                    nullTargetWarn: false,
                    trialWarn: false
                });
                break;

            case 'low':
                gsap.config({
                    force3D: false,
                    nullTargetWarn: false,
                    trialWarn: false
                });
                break;
        }

        // Optimize ScrollTrigger settings
        ScrollTrigger.config({
            limitCallbacks: true,
            syncInterval: this.performanceMode === 'high' ? 0 : 16
        });
    }

    // Debounce utility for performance
    debounce(func, wait) {
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

    // Throttle utility for performance
    throttle(func, limit) {
        let inThrottle;
        return function () {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // Intersection Observer for efficient element tracking
    createIntersectionObserver(callback, options = {}) {
        const defaultOptions = {
            root: null,
            rootMargin: '50px',
            threshold: 0.1
        };

        const observerOptions = { ...defaultOptions, ...options };

        return new IntersectionObserver(callback, observerOptions);
    }

    // Lazy load animations
    lazyLoadAnimations() {
        const animationElements = document.querySelectorAll('[data-animate]');

        const observer = this.createIntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const animationType = element.dataset.animate;

                    this.triggerAnimation(element, animationType);
                    observer.unobserve(element);
                }
            });
        });

        animationElements.forEach(element => {
            observer.observe(element);
        });
    }

    triggerAnimation(element, type) {
        switch (type) {
            case 'fadeIn':
                gsap.fromTo(element,
                    { opacity: 0, y: 30 },
                    { opacity: 1, y: 0, duration: 0.8, ease: "power2.out" }
                );
                break;

            case 'slideIn':
                gsap.fromTo(element,
                    { opacity: 0, x: -50 },
                    { opacity: 1, x: 0, duration: 0.8, ease: "power2.out" }
                );
                break;

            case 'scaleIn':
                gsap.fromTo(element,
                    { opacity: 0, scale: 0.8 },
                    { opacity: 1, scale: 1, duration: 0.8, ease: "back.out(1.7)" }
                );
                break;
        }
    }

    // Memory management
    cleanupUnusedAnimations() {
        // Kill completed animations to free memory
        gsap.globalTimeline.getChildren().forEach(tween => {
            if (tween.progress() === 1 && !tween.repeat()) {
                tween.kill();
            }
        });

        // Clean up unused ScrollTriggers
        ScrollTrigger.getAll().forEach(trigger => {
            if (!document.contains(trigger.trigger)) {
                trigger.kill();
            }
        });
    }

    // Get performance metrics
    getPerformanceMetrics() {
        return {
            averageFPS: this.averageFPS,
            performanceMode: this.performanceMode,
            reducedMotion: this.reducedMotion,
            deviceCapabilities: this.deviceCapabilities,
            activeAnimations: gsap.globalTimeline.getChildren().length,
            activeScrollTriggers: ScrollTrigger.getAll().length
        };
    }

    // Destroy and cleanup
    destroy() {
        // Clean up all animations and observers
        gsap.killTweensOf("*");
        ScrollTrigger.killAll();

        // Remove event listeners
        window.removeEventListener('scroll', this.optimizedScrollHandler);
        window.removeEventListener('resize', this.debouncedResize);
    }
}

export default PerformanceOptimizer;