import './bootstrap';
import AnimationController from './animations';
import ScrollController from './scroll-controller';

// Initialize animations when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize GSAP Animation Controller
    window.animationController = new AnimationController();
    
    // Initialize Scroll Controller
    window.scrollController = new ScrollController();
});
