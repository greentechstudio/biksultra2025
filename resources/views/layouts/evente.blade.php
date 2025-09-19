<!doctype html>
<html class="no-js" lang="id">

@include('partials.evente.head')

<body>


</body>
    <style>
        .coming-soon-tab {
            background-color: #888 !important;
            color: #ddd !important;
            cursor: not-allowed !important;
            opacity: 0.6 !important;
            border: 1px solid #666 !important;
            pointer-events: none !important;
        }
        
        .coming-soon-tab:hover {
            background-color: #888 !important;
            color: #ddd !important;
            transform: none !important;
        }
        
        .coming-soon-tab .td-counts {
            color: #bbb !important;
        }
        
        .coming-soon-tab .d-block {
            color: #999 !important;
            font-size: 12px !important;
        }
        
        /* Glass Overlay Coming Soon Effect */
        .glass-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .glass-overlay:hover {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        .coming-soon-content {
            text-align: center;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .coming-soon-content h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #feca57);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease-in-out infinite;
        }
        
        .coming-soon-content p {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 20px;
            color: #fff;
        }
        
        .coming-soon-badge {
            display: inline-block;
            padding: 10px 25px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            animation: pulse 2s infinite;
        }
        
        .overlay-container {
            position: relative;
            overflow: hidden;
        }
        
        @keyframes gradientShift {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 30px rgba(255, 255, 255, 0.5);
            }
        }
        
        /* Floating particles effect */
        .glass-overlay::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.3) 2px, transparent 2px),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.2) 1px, transparent 1px),
                radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px, 30px 30px, 70px 70px;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        /* Responsive glass overlay */
        @media (max-width: 768px) {
            .coming-soon-content h3 {
                font-size: 2rem;
            }
            
            .coming-soon-content p {
                font-size: 1rem;
            }
            
            .coming-soon-badge {
                font-size: 0.8rem;
                padding: 8px 20px;
            }
        }
    </style>

    <!-- Custom CSS -->
    <style>
        .coming-soon-tab {
            background-color: #888 !important;
            color: #ddd !important;
            cursor: not-allowed !important;
            opacity: 0.6 !important;
            border: 1px solid #666 !important;
            pointer-events: none !important;
        }
        
        .coming-soon-tab:hover {
            background-color: #888 !important;
            color: #ddd !important;
            transform: none !important;
        }
        
        .coming-soon-tab .td-counts {
            color: #bbb !important;
        }
        
        .coming-soon-tab .d-block {
            color: #999 !important;
            font-size: 12px !important;
        }
        
        /* Glass Overlay Coming Soon Effect */
        .glass-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .glass-overlay:hover {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        .coming-soon-content {
            text-align: center;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .coming-soon-content h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #feca57);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease-in-out infinite;
        }
        
        .coming-soon-content p {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 20px;
            color: #fff;
        }
        
        .coming-soon-badge {
            display: inline-block;
            padding: 10px 25px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            animation: pulse 2s infinite;
        }
        
        .overlay-container {
            position: relative;
            overflow: hidden;
        }
        
        @keyframes gradientShift {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 30px rgba(255, 255, 255, 0.5);
            }
        }
        
        /* Floating particles effect */
        .glass-overlay::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.3) 2px, transparent 2px),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.2) 1px, transparent 1px),
                radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px, 30px 30px, 70px 70px;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        /* Responsive glass overlay */
        @media (max-width: 768px) {
            .coming-soon-content h3 {
                font-size: 2rem;
            }
            
            .coming-soon-content p {
                font-size: 1rem;
            }
            
            .coming-soon-badge {
                font-size: 0.8rem;
                padding: 8px 20px;
            }
        }
    </style>

</head>

<body>

    <!-- Preloader Start -->
    <div class="preloader">
        <div class="loader"></div>
    </div>
    <!-- Preloader End -->

    <!-- Scroll-top -->
    <button class="scroll__top scroll-to-target" data-target="html">
        <i class="fa-sharp fa-regular fa-arrow-up"></i>
    </button>
    <!-- Scroll-top-end-->

    <!-- header-search -->
    <div class="search__popup">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="search__wrapper">
                        <div class="search__close">
                            <button type="button" class="search-close-btn">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 1L1 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path d="M1 1L17 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="search__form">
                            <form action="#">
                                <div class="search__input">
                                    <input class="search-input-field" type="text" placeholder="Type keywords here">
                                    <span class="search-focus-border"></span>
                                    <button type="submit">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.55 18.1C14.272 18.1 18.1 14.272 18.1 9.55C18.1 4.82797 14.272 1 9.55 1C4.82797 1 1 4.82797 1 9.55C1 14.272 4.82797 18.1 9.55 18.1Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M19.0002 19.0002L17.2002 17.2002" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="search-popup-overlay"></div>
    <!-- header-search-end -->

    <!-- cart mini area start -->
    <div class="cartmini__area">
        <div class="cartmini__wrapper d-flex justify-content-between flex-column">
            <div class="cartmini__top-wrapper">
                <div class="cartmini__top p-relative">
                    <div class="cartmini__title">
                        <h4>Your cart</h4>
                    </div>
                    <div class="cartmini__close">
                        <button type="button" class="cartmini__close-btn cartmini-close-btn"><i class="fal fa-times"></i></button>
                    </div>
                </div>
                <div class="cartmini__widget">
                    <div class="cartmini__widget-item">
                        <div class="cartmini__thumb">
                            <a href="#">
                                <img src="{{ asset('evente-assets/img/shop/sm-product-1.jpg') }}" alt="">
                            </a>
                        </div>
                        <div class="cartmini__content">
                            <h5><a href="#">Event Item</a></h5>
                            <div class="cartmini__price-wrapper">
                                <span class="cartmini__price">Rp 0</span>
                            </div>
                        </div>
                        <button class="cartmini__del"><i class="fal fa-times"></i></button>
                    </div>
                </div>
            </div>
            <div class="cartmini__checkout">
                <div class="cartmini__checkout-btn">
                    <a href="#" class="td-btn cartmini-checkout">Proceed to checkout</a>
                </div>
            </div>
        </div>
    </div>
    <div class="cartmini__overlay"></div>
    <!-- cart mini area end -->

    <!-- header-area -->
    @include('partials.evente.header')
    <!-- header-area-end -->

    <!-- main-area -->
    <main>
        @yield('content')
    </main>
    <!-- main-area-end -->

    <!-- footer-area -->
    @include('partials.evente.footer')
    <!-- footer-area-end -->

    <!-- JS here -->
    <script src="{{ asset('evente-assets/js/vendor/jquery.js') }}"></script>
    <script src="{{ asset('evente-assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('evente-assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('evente-assets/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('evente-assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('evente-assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('evente-assets/js/jquery.odometer.min.js') }}"></script>
    <script src="{{ asset('evente-assets/js/jquery-appear.js') }}"></script>
    <script src="{{ asset('evente-assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('evente-assets/js/Jarallax.js') }}"></script>
    <script src="{{ asset('evente-assets/js/nice-select.js') }}"></script>
    <script src="{{ asset('evente-assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('evente-assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('evente-assets/js/main.js') }}"></script>

    @stack('scripts')

    <!-- Custom Navigation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling untuk section navigation
            document.querySelectorAll('.section-scroll').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);
                    
                    if (targetSection) {
                        // Offset untuk header yang fixed
                        const headerHeight = window.innerWidth <= 991 ? 60 : 80;
                        const targetPosition = targetSection.offsetTop - headerHeight;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Active menu berdasarkan scroll position
            function updateActiveMenu() {
                const sections = document.querySelectorAll('section[id], div[id]');
                const scrollPosition = window.scrollY + 150;
                let activeFound = false;
                
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;
                    const sectionId = section.getAttribute('id');
                    
                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight && !activeFound) {
                        // Remove active class dari semua links (desktop dan mobile)
                        document.querySelectorAll('.navigation li').forEach(li => {
                            li.classList.remove('active');
                        });
                        document.querySelectorAll('.mobile-section-link').forEach(link => {
                            link.classList.remove('active');
                        });
                        
                        // Add active class ke link yang sesuai (desktop)
                        const activeDesktopLink = document.querySelector(`.navigation a[href="#${sectionId}"]`);
                        if (activeDesktopLink) {
                            activeDesktopLink.parentElement.classList.add('active');
                        }
                        
                        // Add active class ke link yang sesuai (mobile)
                        const activeMobileLink = document.querySelector(`.mobile-section-link[href="#${sectionId}"]`);
                        if (activeMobileLink) {
                            activeMobileLink.classList.add('active');
                        }
                        
                        activeFound = true;
                    }
                });
            }
            
            // Listen untuk scroll events
            window.addEventListener('scroll', updateActiveMenu);
            
            // Set initial active menu
            updateActiveMenu();
            
            // Handle resize untuk responsivitas
            window.addEventListener('resize', function() {
                updateActiveMenu();
            });
        });
    </script>
</body>

</html>