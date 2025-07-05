<!-- Hero Section -->
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
            <button class="hero-btn">
                JOIN RUNER CLUB
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 12h14m-7-7l7 7-7 7"/>
                </svg>
            </button>
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
