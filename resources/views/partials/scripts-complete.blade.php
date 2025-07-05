<script>
// Mobile menu toggle function
function toggleMobileMenu() {
    const navMenu = document.querySelector('.nav-menu');
    const body = document.body;
    
    navMenu.classList.toggle('active');
    
    if (navMenu.classList.contains('active')) {
        body.style.overflow = 'hidden';
    } else {
        body.style.overflow = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle (additional event listener for menu toggle button)
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', toggleMobileMenu);
    }

    // Close mobile menu when clicking on a nav link
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (navMenu.classList.contains('active')) {
                toggleMobileMenu();
            }
        });
    });

    // Login and Register button functionality
    const loginBtn = document.querySelector('.login-btn');
    const registerBtn = document.querySelector('.contact-btn');
    
    if (loginBtn) {
        loginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Redirect to login page using Laravel route
            window.location.href = '{{ route("login") }}';
        });
    }
    
    if (registerBtn) {
        registerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Redirect to register page using Laravel route
            window.location.href = '{{ route("register") }}';
        });
    }

    // Join buttons functionality
    const joinButtons = document.querySelectorAll('.hero-btn, .join-btn, .cta-btn');
    joinButtons.forEach(button => {
        if (button.textContent.includes('JOIN')) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                // Redirect to register page
                window.location.href = '{{ route("register") }}';
            });
        }
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        const navMenu = document.querySelector('.nav-menu');
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        const closeBtn = document.querySelector('.close-btn');
        
        if (navMenu && mobileToggle && closeBtn && 
            !navMenu.contains(e.target) && 
            !mobileToggle.contains(e.target) && 
            !closeBtn.contains(e.target)) {
            navMenu.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Close mobile menu on window resize to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            const navMenu = document.querySelector('.nav-menu');
            if (navMenu) {
                navMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });

    // Handle escape key to close menu
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const navMenu = document.querySelector('.nav-menu');
            if (navMenu) {
                navMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });

    // Scroll effects for contact bar and navbar
    const contactBar = document.querySelector('.contact-bar');
    const navbar = document.querySelector('.navbar');
    
    function handleScroll() {
        const scrolled = window.scrollY > 50;
        
        if (contactBar && navbar) {
            if (scrolled) {
                contactBar.classList.add('scrolled');
                navbar.classList.add('scrolled');
            } else {
                contactBar.classList.remove('scrolled');
                navbar.classList.remove('scrolled');
            }
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
                if (target) {
                    animateCounter(counter, target);
                    observer.unobserve(counter);
                }
            }
        });
    }, observerOptions);

    // Observe all counters
    document.querySelectorAll('.counter').forEach(counter => {
        observer.observe(counter);
    });

    // Smooth scrolling for internal links
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

    // Animation on scroll
    const animateOnScroll = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    // Observe elements with animation classes
    document.querySelectorAll('.slideInUp, .slideInLeft, .slideInDown').forEach(el => {
        animateOnScroll.observe(el);
    });

    // Form handling for newsletter
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('.newsletter-input').value;
            if (email) {
                // You can add your newsletter subscription logic here
                alert('Thank you for subscribing!');
                this.querySelector('.newsletter-input').value = '';
            }
        });
    }

    // Buy Ticket and Discover More buttons
    const buyTicketBtn = document.querySelector('.buy-ticket-btn');
    const discoverMoreBtns = document.querySelectorAll('.discover-more-btn, .discover-btn');
    
    if (buyTicketBtn) {
        buyTicketBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Redirect to ticket purchase page or show modal
            alert('Redirecting to ticket purchase page...');
            // window.location.href = '/buy-ticket';
        });
    }
    
    discoverMoreBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            // Redirect to about page or show more info
            alert('Redirecting to more information...');
            // window.location.href = '/about';
        });
    });

    // More Team button
    const moreTeamBtn = document.querySelector('.more-team-btn');
    if (moreTeamBtn) {
        moreTeamBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Redirect to team page
            alert('Redirecting to team page...');
            // window.location.href = '/team';
        });
    }

    // Video error handling
    const heroVideo = document.querySelector('.hero-video-bg video');
    if (heroVideo) {
        heroVideo.addEventListener('error', function() {
            // Hide video if it fails to load
            this.style.display = 'none';
        });
    }

    // CTA video iframe error handling
    const ctaIframe = document.querySelector('.cta-video-bg iframe');
    if (ctaIframe) {
        ctaIframe.addEventListener('error', function() {
            // Hide iframe if it fails to load
            this.style.display = 'none';
        });
    }
});

// Additional utility functions
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// Lazy loading for images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}
</script>
