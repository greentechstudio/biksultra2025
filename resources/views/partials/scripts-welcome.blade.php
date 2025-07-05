<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle function
    window.toggleMobileMenu = function() {
        const navMenu = document.querySelector('.nav-menu');
        const body = document.body;
        
        navMenu.classList.toggle('active');
        
        if (navMenu.classList.contains('active')) {
            body.style.overflow = 'hidden';
        } else {
            body.style.overflow = '';
        }
    };

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

    // Buy Ticket and Discover More buttons
    const buyTicketBtn = document.querySelector('.buy-ticket-btn');
    const discoverMoreBtns = document.querySelectorAll('.discover-more-btn, .discover-btn');
    
    if (buyTicketBtn) {
        buyTicketBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Redirect to ticket purchase page or show modal
            alert('Feature coming soon! Please register to get notified.');
            window.location.href = '{{ route("register") }}';
        });
    }
    
    discoverMoreBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            // Redirect to about page or show more info
            alert('More information coming soon! Join our club to stay updated.');
            window.location.href = '{{ route("register") }}';
        });
    });

    // More Team button
    const moreTeamBtn = document.querySelector('.more-team-btn');
    if (moreTeamBtn) {
        moreTeamBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Redirect to team page
            alert('Team page coming soon! Join us to meet the team.');
            window.location.href = '{{ route("register") }}';
        });
    }

    // Mobile menu handling
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', window.toggleMobileMenu);
    }

    // Close mobile menu when clicking on a nav link
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (navMenu.classList.contains('active')) {
                window.toggleMobileMenu();
            }
        });
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

    // Newsletter form handling
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('.newsletter-input').value;
            if (email && email.includes('@')) {
                alert('Thank you for subscribing! We will keep you updated.');
                this.querySelector('.newsletter-input').value = '';
            } else {
                alert('Please enter a valid email address.');
            }
        });
    }

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

    // Video error handling
    const heroVideo = document.querySelector('.hero-video-bg video');
    if (heroVideo) {
        heroVideo.addEventListener('error', function() {
            this.style.display = 'none';
        });
    }

    // Scroll effects for navbar
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
});
</script>
