@extends('layouts.base')

@section('title', 'Runer Running Club')

@section('content')

{{-- Hero Section --}}
<section class="hero">
    <div class="hero-video-bg">
        <video autoplay muted loop playsinline preload="metadata">
            <source src="{{ asset('promo.mp4') }}" type="video/mp4">
            <source src="https://sample-videos.com/zip/10/webm/SampleVideo_1280x720_2mb.webm" type="video/webm">
            <!-- Fallback image if video fails to load -->
            <img src="https://sjc.microlink.io/YmSFpR1kWNgKg_2ztmTS74tu-b79eEfmG4TriTQ1ycXEq8MB4u2ol-3JHxgBDez_fXUXoudgYKSRkOLo1nQjqg.jpeg" alt="Runners in action" style="width: 100%; height: 100%; object-fit: cover;">
        </video>
    </div>
    <div class="hero-overlay"></div>
    <div class="vertical-text">SUNDAY</div>
    
    <div class="container">
        <div class="hero-content">
            <h1>LIMITLESS<br>RUNNING<br>SPIRIT.</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="hero-btn">
                    GO TO DASHBOARD
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 12h14m-7-7l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <a href="{{ route('register') }}" class="hero-btn">
                    JOIN RUNER CLUB
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 12h14m-7-7l7 7-7 7"/>
                    </svg>
                </a>
            @endauth
        </div>

        <div class="event-card">
            <div class="event-header">
                <div class="event-label">Upcoming Event:</div>
                <h3 class="event-title">TWILIGHT TRAIL RUN</h3>
            </div>
            
            <div class="event-details">
                <div class="event-date">
                    <div class="detail-label">Date</div>
                    <div class="detail-value">JAN 25, 2025</div>
                </div>
                <div class="event-time">
                    <div class="detail-label">Start</div>
                    <div class="detail-value small">08:00 PM</div>
                </div>
                <div class="event-duration">
                    <div class="detail-label">Until</div>
                    <div class="detail-value small">FINISH</div>
                </div>
            </div>
            
            <div class="event-note">
                *Vestibulum efficitur gravida lobortis. Donec posuere enim nec, posuere urna.
            </div>
        </div>
    </div>
</section>

{{-- About Section --}}
<section class="about-section">
    <div class="container">
        <!-- Main Content Grid -->
        <div class="about-main-grid">
            <!-- Left Image Column -->
            <div class="about-column about-image-column">
                <div class="section-label-container">
                    <span class="label-bracket">[</span>
                    <span class="label-text">WHO WE ARE</span>
                    <span class="label-bracket">]</span>
                </div>
                <div class="image-container slideInUp">
                    <img src="https://askit.dextheme.net/runer/wp-content/uploads/sites/29/2025/01/USX5VEC.jpg" alt="Runners training together" class="about-image">
                </div>
            </div>

            <!-- Center Content Column -->
            <div class="about-column about-content-column">
                <div class="content-wrapper">
                    <h2 class="about-title slideInLeft">POWERED BY RUNNING SPIRIT.</h2>
                    <p class="about-description">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ullamcorper tempus est. Donec sit amet lorem at ex eleifend convallis eu vitae nisl. Nullam ornare aliquam faucibus. Etiam elementum elit a sollicitudin mollis. Aenean et risus et sapien elementum sodales.
                    </p>
                    <a href="#" class="discover-btn">
                        Discover More
                        <svg class="btn-icon" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                            <path d="M256 8c137 0 248 111 248 248S393 504 256 504 8 393 8 256 119 8 256 8zm-28.9 143.6l75.5 72.4H120c-13.3 0-24 10.7-24 24v16c0 13.3 10.7 24 24 24h182.6l-75.5 72.4c-9.7 9.3-9.9 24.8-.4 34.3l11 10.9c9.4 9.4 24.6 9.4 33.9 0L404.3 273c9.4-9.4 9.4-24.6 0-33.9L271.6 106.3c-9.4-9.4-24.6-9.4-33.9 0l-11 10.9c-9.5 9.6-9.3 25.1.4 34.4z"/>
                        </svg>
                    </a>
                </div>
                <div class="image-container slideInDown">
                    <img src="https://askit.dextheme.net/runer/wp-content/uploads/sites/29/2025/01/RDMRMJH.jpg" alt="Runners in action" class="about-image">
                </div>
            </div>

            <!-- Right Stats Column -->
            <div class="about-column about-stats-column">
                <div class="stats-container">
                    <div class="stat-item slideInUp" data-delay="0">
                        <div class="counter-wrapper">
                            <span class="counter" data-target="253">253</span>
                            <span class="counter-suffix">+</span>
                        </div>
                        <h6 class="stat-label">VICTORY AWARDS</h6>
                    </div>
                    
                    <div class="stat-item slideInUp" data-delay="200">
                        <div class="counter-wrapper">
                            <span class="counter" data-target="2687">2,687</span>
                        </div>
                        <h6 class="stat-label">ACTIVE & ENERGETIC MEMBERS</h6>
                    </div>
                    
                    <div class="stat-item slideInUp" data-delay="400">
                        <div class="counter-wrapper">
                            <span class="counter" data-target="149">149</span>
                            <span class="counter-suffix">+</span>
                        </div>
                        <h6 class="stat-label">EVENTS & CHALLENGES</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Why Stride With Us Section --}}
<section class="why-stride-section">
    <div class="container">
        <div class="stride-header">
            <div class="stride-title-section">
                <div class="section-label">
                    <span class="label-bracket">[</span>
                    <span class="label-text">WHY STRIDE WITH US?</span>
                    <span class="label-bracket">]</span>
                </div>
                <h2 class="stride-title">RUN BETTER,<br>DREAM BIGGER.</h2>
            </div>
            <div class="stride-description">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ullamcorper tempus est. Donec sit amet lorem at ex eleifend convallis eu vitae nisl. Nullam ornare aliquam faucibus. Etiam elementum elit.</p>
            </div>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12,6 12,12 16,14"/>
                    </svg>
                </div>
                <h3 class="feature-title">Expert-Led Training</h3>
                <p class="feature-description">Guided by professional coaches and seasoned runners to help you achieve your best.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    </svg>
                </div>
                <h3 class="feature-title">Inclusive Community</h3>
                <p class="feature-description">A welcoming environment for runners of all levels, from beginners to marathoners.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <circle cx="12" cy="8" r="7"/>
                        <polyline points="8.21,13.89 7,23 12,20 17,23 15.79,13.88"/>
                    </svg>
                </div>
                <h3 class="feature-title">Tailored Events</h3>
                <p class="feature-description">Running experiences, from fun runs to endurance challenges, designed to inspire and motivate.</p>
            </div>

            <div class="feature-card cta-card">
                <div class="cta-content">
                    <h3 class="cta-title">STRIDE<br>FORWARD<br>WITH US.</h3>
                    @auth
                        <a href="{{ route('dashboard') }}" class="join-btn">
                            ACCESS DASHBOARD
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 12h14m-7-7l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="join-btn">
                            JOIN RUNNER CLUB
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 12h14m-7-7l7 7-7 7"/>
                            </svg>
                        </a>
                    @endauth
                </div>
                <div class="cta-runner-image">
                    <img src="https://images.unsplash.com/photo-1594736797933-d0401ba2fe65?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Female runner" />
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Exclusive Features Section --}}
<section class="exclusive-features-section">
    <div class="container">
        <div class="features-header">
            <div>
                <div class="features-label">EXCLUSIVE FEATURES</div>
            </div>
            <div class="features-content">
                <h2>Fueling Runners<br>With Excellence.</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ullamcorper tempus est. Donec sit amet lorem at ex eleifend convallis eu vitae nisl. Nullam ornare aliquam faucibus. Etiam elementum elit.</p>
            </div>
        </div>

        <div class="features-showcase">
            <div class="feature-showcase-item">
                <img src="/placeholder.svg?height=400&width=350" alt="Group of runners on urban steps">
                <div class="feature-overlay">
                    <h3>Urban Training</h3>
                    <p>Experience dynamic city running with our urban training programs designed for all fitness levels.</p>
                </div>
            </div>

            <div class="feature-showcase-item">
                <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-fTgEOPUvXTpewEDvzGEPLZJnLhoFUO.png" alt="Coach and runner training session">
                <div class="feature-overlay">
                    <h3>Expert Coaching</h3>
                    <p>Guidance from experienced coaches to improve technique, endurance, and speed through personalized sessions.</p>
                </div>
            </div>

            <div class="feature-showcase-item">
                <img src="/placeholder.svg?height=400&width=350" alt="Marathon runners with race numbers">
                <div class="feature-overlay">
                    <h3>Race Events</h3>
                    <p>Participate in organized races and marathons that challenge your limits and celebrate achievements.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Challenges Section --}}
<section class="challenges-section">
    <div class="container">
        <div class="challenges-header">
            <div class="challenges-label">CHALLENGES</div>
            <h6 class="challenges-title">Run Together, Celebrate Victory !</h6>
            <p class="challenges-description">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ullamcorper tempus est. Donec sit amet lorem 
                at ex eleifend convallis eu vitae nisl. Nullam ornare aliquam faucibus. Etiam elementum elit.
            </p>
        </div>

        <div class="challenge-card">
            <div class="challenge-date">
                <div class="challenge-date-main">February, 22<br>2025</div>
                <div class="challenge-date-day">Saturday</div>
            </div>

            <div class="challenge-poster">
                <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-EWJwgC8UieSuJwzGUTCO7mj8Adz4Nc.png" alt="Tokyo Marathon Poster">
            </div>

            <div class="challenge-info">
                <h3>Tokyo Marathon</h3>
                <div class="challenge-details">
                    <div class="challenge-detail">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <span><strong>Shinjuku, Japan</strong></span>
                    </div>
                    <div class="challenge-detail">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                        <span><strong>26.2 Miles</strong></span>
                    </div>
                    <div class="challenge-detail">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12,6 12,12 16,14"/>
                        </svg>
                        <span><strong>06:00 - Finish</strong></span>
                    </div>
                </div>
                <p class="challenge-description">
                    A modern urban course that winds through the vibrant streets of Tokyo, showcasing its 
                    culture and skyline.
                </p>
            </div>

            <div class="challenge-actions">
                <button class="buy-ticket-btn">
                    BUY TICKET
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 12h14m-7-7l7 7-7 7"/>
                    </svg>
                </button>
                <button class="discover-more-btn">
                    DISCOVER MORE
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 12h14m-7-7l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>

{{-- Sponsors Section --}}
<section class="sponsors-section">
    <div class="container">
        <div class="sponsors-title">
            Our Optimistic<br>Partner & Sponsor:
        </div>
        <div class="sponsors-logos">
            <div class="sponsor-logo focus">FOCUS</div>
            <div class="sponsor-logo knife">KNIFE</div>
            <div class="sponsor-logo strana">strana</div>
            <div class="sponsor-logo attitude">
                <div class="main">Attitude</div>
                <div class="sub">Slogan Company</div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="cta-section">
    <div class="cta-video-bg">
        <iframe 
            src="https://www.youtube.com/embed/K4TOrB7at0Y?autoplay=1&mute=1&loop=1&playlist=K4TOrB7at0Y&controls=0&showinfo=0&rel=0&modestbranding=1"
            title="Background Video"
            allow="autoplay; encrypted-media"
            allowfullscreen>
        </iframe>
    </div>
    <div class="cta-overlay"></div>
    <div class="cta-content">
        <h2 class="cta-title">Lace Up, Your Victory Awaits.</h2>
        <p class="cta-description">
            Donec a est nisl. Quisque hendrerit ex tellus, pharetra tincidunt ante venenatis nec. Donec malesuada mi eu 
            elit tristique tincidunt. Vestibulum id massa orci.
        </p>
        @auth
            <a href="{{ route('dashboard') }}" class="cta-btn">
                GO TO DASHBOARD
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 12h14m-7-7l7 7-7 7"/>
                </svg>
            </a>
        @else
            <a href="{{ route('register') }}" class="cta-btn">
                JOIN NOW
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 12h14m-7-7l7 7-7 7"/>
                </svg>
            </a>
        @endauth
    </div>
</section>

{{-- Team Section --}}
<section class="team-section">
    <div class="container">
        <div class="team-header">
            <div class="team-label">PAGE SETTERS</div>
            <h2 class="team-title">Together We Conquer Every Mile.</h2>
            <p class="team-description">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ullamcorper tempus est. Donec sit amet lorem 
                at ex eleifend convallis eu vitae nisl. Nullam ornare aliquam faucibus. Etiam elementum elit.
            </p>
        </div>

        <div class="team-grid">
            <div class="team-member">
                <img src="/placeholder.svg?height=400&width=350" alt="Team Member 1" class="team-member-image">
            </div>
            <div class="team-member">
                <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-luLe9wmrXqFZfS84EG9l2GbP0ohXME.png" alt="Team Member 2" class="team-member-image">
            </div>
            <div class="team-member">
                <img src="/placeholder.svg?height=400&width=350" alt="Team Member 3" class="team-member-image">
            </div>
        </div>

        <div class="team-actions">
            <button class="more-team-btn">
                MORE TEAM
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 12h14m-7-7l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>
</section>

@endsection

