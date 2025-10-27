// Scroll Controller for efficient scroll event handling
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

class ScrollController {
    constructor() {
        this.scrollPosition = 0;
        this.isScrolling = false;
        this.scrollDirection = 'down';
        this.lastScrollTop = 0;
        this.scrollCallbacks = [];
        this.throttleDelay = 16; // ~60fps
        
        this.init();
    }

    init() {
        // Use GSAP's ScrollTrigger for optimized scroll handling
        ScrollTrigger.addEventListener("scroll", this.handleScroll.bind(this));
        
        // Add debounced scroll end detection
        this.debouncedScrollEnd = this.debounce(() => {
            this.isScrolling = false;
            this.onScrollEnd();
        }, 150);
    }

    handleScroll() {
        this.scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
        this.scrollDirection = this.scrollPosition > this.lastScrollTop ? 'down' : 'up';
        this.lastScrollTop = this.scrollPosition;
        this.isScrolling = true;

        // Execute registered callbacks
        this.scrollCallbacks.forEach(callback => {
            if (typeof callback === 'function') {
                callback({
                    position: this.scrollPosition,
                    direction: this.scrollDirection,
                    isScrolling: this.isScrolling
                });
            }
        });

        // Reset scroll end timer
        this.debouncedScrollEnd();
    }

    onScrollEnd() {
        // Trigger scroll end callbacks
        this.scrollCallbacks.forEach(callback => {
            if (typeof callback.onScrollEnd === 'function') {
                callback.onScrollEnd({
                    position: this.scrollPosition,
                    direction: this.scrollDirection,
                    isScrolling: this.isScrolling
                });
            }
        });
    }

    // Register scroll callback
    addScrollCallback(callback) {
        this.scrollCallbacks.push(callback);
    }

    // Remove scroll callback
    removeScrollCallback(callback) {
        const index = this.scrollCallbacks.indexOf(callback);
        if (index > -1) {
            this.scrollCallbacks.splice(index, 1);
        }
    }

    // Smooth scroll to element
    scrollToElement(element, options = {}) {
        const defaults = {
            duration: 1,
            ease: "power2.inOut",
            offsetY: 0
        };
        
        const settings = { ...defaults, ...options };
        
        gsap.to(window, {
            duration: settings.duration,
            scrollTo: {
                y: element,
                offsetY: settings.offsetY
            },
            ease: settings.ease
        });
    }

    // Smooth scroll to position
    scrollToPosition(position, options = {}) {
        const defaults = {
            duration: 1,
            ease: "power2.inOut"
        };
        
        const settings = { ...defaults, ...options };
        
        gsap.to(window, {
            duration: settings.duration,
            scrollTo: position,
            ease: settings.ease
        });
    }

    // Utility: Debounce function
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

    // Utility: Throttle function
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // Get current scroll information
    getScrollInfo() {
        return {
            position: this.scrollPosition,
            direction: this.scrollDirection,
            isScrolling: this.isScrolling,
            documentHeight: document.documentElement.scrollHeight,
            windowHeight: window.innerHeight,
            scrollPercentage: (this.scrollPosition / (document.documentElement.scrollHeight - window.innerHeight)) * 100
        };
    }

    // Check if element is in viewport
    isInViewport(element, threshold = 0) {
        const rect = element.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        const windowWidth = window.innerWidth || document.documentElement.clientWidth;

        return (
            rect.top >= -threshold &&
            rect.left >= -threshold &&
            rect.bottom <= windowHeight + threshold &&
            rect.right <= windowWidth + threshold
        );
    }

    // Destroy scroll controller
    destroy() {
        ScrollTrigger.removeEventListener("scroll", this.handleScroll.bind(this));
        this.scrollCallbacks = [];
    }
}

export default ScrollController;