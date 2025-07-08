    <script>
        // Mobile menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenu = document.getElementById('mobileMenu');

            if (mobileMenuToggle && mobileMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileMenuToggle.classList.toggle('active');
                    mobileMenu.classList.toggle('active');
                });

                // Close mobile menu when clicking on a link
                document.querySelectorAll('.mobile-menu-links a').forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenuToggle.classList.remove('active');
                        mobileMenu.classList.remove('active');
                    });
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenuToggle.contains(event.target) && !mobileMenu.contains(event.target)) {
                        mobileMenuToggle.classList.remove('active');
                        mobileMenu.classList.remove('active');
                    }
                });
            }

            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const headerHeight = document.querySelector('.header').offsetHeight;
                        const targetPosition = target.offsetTop - headerHeight;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Header background change on scroll
            window.addEventListener('scroll', function() {
                const header = document.querySelector('.header');
                if (window.scrollY > 100) {
                    header.style.background = 'rgba(39, 63, 11, 0.95)';
                    header.style.backdropFilter = 'blur(25px)';
                    header.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.3)';
                } else {
                    header.style.background = 'rgba(39, 63, 11, 0.9)';
                    header.style.backdropFilter = 'blur(20px)';
                    header.style.boxShadow = 'none';
                }
            });

            // Newsletter form submission
            const newsletterForm = document.querySelector('.newsletter-form');
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const emailInput = this.querySelector('.newsletter-input');
                    const email = emailInput.value.trim();
                    
                    if (email) {
                        // Simple email validation
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (emailRegex.test(email)) {
                            alert('Terima kasih! Anda telah berlangganan newsletter kami.');
                            emailInput.value = '';
                        } else {
                            alert('Mohon masukkan email yang valid.');
                        }
                    } else {
                        alert('Mohon masukkan email Anda.');
                    }
                });
            }

            // Intersection Observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                    }
                });
            }, observerOptions);

            // Observe elements for animation
            document.querySelectorAll('.category-card, .prize-item, .early-bird, .registration-form').forEach(el => {
                observer.observe(el);
            });

            // Add loading animation
            window.addEventListener('load', function() {
                document.body.classList.add('loaded');
            });
        });
    </script>
