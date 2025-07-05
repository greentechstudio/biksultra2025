<script>
    // Simple mobile menu toggle
    function toggleMobileMenu() {
        const navMenu = document.querySelector('.nav-menu');
        if (navMenu) {
            navMenu.classList.toggle('active');
        }
    }

    // Scroll effect for navbar
    document.addEventListener('DOMContentLoaded', function() {
        const contactBar = document.querySelector('.contact-bar');
        const navbar = document.querySelector('.navbar');
        
        function handleScroll() {
            const scrolled = window.scrollY > 50;
            
            if (contactBar) {
                if (scrolled) {
                    contactBar.classList.add('scrolled');
                } else {
                    contactBar.classList.remove('scrolled');
                }
            }
            
            if (navbar) {
                if (scrolled) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }
        }
        
        // Initial check
        handleScroll();
        
        // Listen for scroll events
        window.addEventListener('scroll', handleScroll);
    });

    // Simple counter animation
    function animateCounter(element, target) {
        const duration = 2000;
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current);
            }
        }, 16);
    }

    // Initialize counters when in viewport
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.counter');
        if (counters.length > 0) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const target = parseInt(counter.getAttribute('data-target'));
                        if (target) {
                            animateCounter(counter, target);
                            observer.unobserve(counter);
                        }
                    }
                });
            }, { threshold: 0.5 });

            counters.forEach(counter => {
                observer.observe(counter);
            });
        }
    });
</script>
