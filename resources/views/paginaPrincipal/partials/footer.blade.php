<style>
    /* Footer Styles - Diseño según imagen de referencia */
    footer {
        background: #1e293b;
        position: relative;
        border: none;
    }

    /* Subtítulos de secciones - estilo minimalista */
    footer .footer-section h4 {
        color: #ffffff;
        font-weight: 600;
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        text-align: left;
    }

    /* Textos del footer - estilo limpio */
    footer .footer-section p,
    footer .footer-section li a {
        color: #94a3b8 !important;
        font-weight: 400;
        font-size: 0.875rem;
        transition: color 0.3s ease;
        line-height: 1.6;
        text-align: left;
    }

    footer .footer-section li a:hover {
        color: #ffffff !important;
    }

    /* Newsletter form styling */
    footer .newsletter-form input {
        background: #334155;
        border: 1px solid #475569;
        color: #ffffff;
        font-size: 0.875rem;
    }

    footer .newsletter-form input:focus {
        border-color: #3b82f6;
        outline: none;
    }

    footer .newsletter-form button {
        background: #ffffff;
        color: #1e293b;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        transition: background-color 0.3s ease;
    }

    footer .newsletter-form button:hover {
        background: #f1f5f9;
    }

    /* Redes sociales - estilo minimalista */
    footer .social-links {
        display: flex;
        gap: 1rem;
        justify-content: flex-start;
        margin-top: 1.5rem;
    }

    footer .social-links a {
        color: #94a3b8;
        transition: color 0.3s ease;
        width: auto;
        height: auto;
        background: none;
        border: none;
        border-radius: 0;
        padding: 0;
    }

    footer .social-links a:hover {
        color: #ffffff;
        transform: none;
        box-shadow: none;
    }

    footer .social-links a svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    /* Grid layout adjustments */
    footer .grid {
        text-align: center;
    }

    /* Remove animations for cleaner look */
    footer .footer-section {
        animation: none;
    }

    /* Responsive del footer */
    @media (max-width: 768px) {
        footer .grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .footer-section {
            text-align: left;
        }

        .newsletter-form .flex {
            flex-direction: column;
            gap: 0.5rem;
        }

        .newsletter-form input,
        .newsletter-form button {
            border-radius: 0.5rem;
        }
    }

    /* Newsletter section specific styling */
    footer .footer-section:last-child p {
        color: #94a3b8 !important;
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }
</style>

<!-- Footer Reorganizado según diseño de imagen -->
<footer class="bg-black py-16">
    <div class="container mx-auto px-6">
        <!-- Grid Principal del Footer -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <!-- Columna 1: Info -->
            <div class="footer-section">
                <h4 class="text-lg font-semibold text-white mb-6">Info</h4>
                <ul class="space-y-3">
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Formatos</a></li>
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Compresión</a></li>
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Precios</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">FAQ</a>
                    </li>
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Estado</a></li>
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Política</a></li>
                </ul>
            </div>

            <!-- Columna 2: Getting Started -->
            <div class="footer-section">
                <h4 class="text-lg font-semibold text-white mb-6">Getting Started</h4>
                <ul class="space-y-3">
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Introducción</a></li>
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Temas</a></li>
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Documentación</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">Usos</a>
                    </li>
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Elementos</a></li>
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Global</a></li>
                </ul>
            </div>

            <!-- Columna 3: Resources -->
            <div class="footer-section">
                <h4 class="text-lg font-semibold text-white mb-6">Resources</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">API</a>
                    </li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">Form
                            Validation</a></li>
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Accessibility</a></li>
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Marketplace</a></li>
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Visibility</a></li>
                    <li><a href="#"
                            class="text-gray-300 hover:text-white transition-colors duration-300">Community</a></li>
                </ul>
            </div>

            <!-- Columna 4: Newsletter -->
            <div class="footer-section">
                <h4 class="text-lg font-semibold text-white mb-6">Newsletter</h4>
                <p class="text-gray-300 mb-6 text-sm">
                    Subscribe to our newsletter for a weekly dose of news, updates, helpful tips, and exclusive offers.
                </p>

                <!-- Newsletter Form -->
                <div class="newsletter-form mb-6">
                    <div class="flex">
                        <input type="email" placeholder="Your email"
                            class="flex-1 px-4 py-3 bg-gray-800 text-white border border-gray-600 rounded-l-lg focus:outline-none focus:border-blue-500 transition-colors">
                        <button
                            class="px-6 py-3 bg-white text-black font-semibold rounded-r-lg hover:bg-gray-200 transition-colors">
                            SUBSCRIBE
                        </button>
                    </div>
                </div>

                <!-- Social Icons -->
                <div class="social-links flex space-x-4">
                    <!-- Facebook -->
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>

                    <!-- GitHub -->
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                        </svg>
                    </a>

                    <!-- LinkedIn -->
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                    </a>

                    <!-- Twitter -->
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
