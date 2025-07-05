<script>
    function toggleMobileMenu() {
        const navMenu = document.querySelector('.nav-menu');
        navMenu.classList.toggle('active');
        
        // Prevent body scroll when menu is open
        if (navMenu.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    // Close mobile menu when clicking on a link
    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.remove('active');
            document.body.style.overflow = '';
        });
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        const navMenu = document.querySelector('.nav-menu');
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        const closeBtn = document.querySelector('.close-btn');
        
        if (!navMenu.contains(e.target) && !mobileToggle.contains(e.target) && !closeBtn.contains(e.target)) {
            navMenu.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Close mobile menu on window resize to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Handle escape key to close menu
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Counter Animation
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                start = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(start).toLocaleString();
        }, 16);
    }

    // Intersection Observer for counter animation
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'));
                animateCounter(counter, target);
                observer.unobserve(counter);
            }
        });
    }, observerOptions);

    // Observe all counters
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.counter').forEach(counter => {
            observer.observe(counter);
        });
    });

    // Scroll Effect Script
    document.addEventListener('DOMContentLoaded', function() {
        const contactBar = document.querySelector('.contact-bar');
        const navbar = document.querySelector('.navbar');
        
        function handleScroll() {
            const scrolled = window.scrollY > 50;
            
            if (scrolled) {
                contactBar.classList.add('scrolled');
                navbar.classList.add('scrolled');
            } else {
                contactBar.classList.remove('scrolled');
                navbar.classList.remove('scrolled');
            }
        }
        
        // Initial check
        handleScroll();
        
        // Listen for scroll events with throttling for better performance
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });
    });

    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Video controls
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.querySelector('.hero-video-bg video');
        if (video) {
            video.muted = true;
            video.playsInline = true;
            
            // Pause video when tab is not visible to save resources
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    video.pause();
                } else {
                    video.play();
                }
            });
        }
    });
</script>
