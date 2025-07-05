<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
} 

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
}

/* Top Contact Bar */
.contact-bar {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding: 12px 0;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    transition: all 0.3s ease;
}

.contact-bar .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
}

.social-icons {
    display: flex;
    gap: 15px;
}

.social-icons svg {
    width: 16px;
    height: 16px;
    color: rgba(255, 255, 255, 0.8);
    transition: color 0.3s ease;
}

.social-icons svg:hover {
    color: white;
}

.contact-info {
    display: flex;
    gap: 30px;
    color: rgba(255, 255, 255, 0.8);
    transition: color 0.3s ease;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.contact-item svg {
    width: 16px;
    height: 16px;
    color: #ef4444;
}

.contact-item span {
    color: rgba(255, 255, 255, 0.9);
    transition: color 0.3s ease;
}

/* Scrolled state for contact bar */
.contact-bar.scrolled {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.contact-bar.scrolled .contact-info {
    color: rgba(0, 0, 0, 0.7);
}

.contact-bar.scrolled .contact-item span {
    color: rgba(0, 0, 0, 0.8);
}

.contact-bar.scrolled .social-icons svg {
    color: rgba(0, 0, 0, 0.6);
}

.contact-bar.scrolled .social-icons svg:hover {
    color: #ef4444;
}

.contact-bar.scrolled .contact-item svg {
    color: #ef4444;
}

/* Navigation */
.navbar {
    background: transparent;
    position: fixed;
    top: 44px; /* Height of contact bar */
    left: 0;
    right: 0;
    z-index: 999;
    padding: 15px 0;
    transition: all 0.3s ease;
}

.navbar .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    display: flex;
    align-items: center;
    gap: 15px;
}

.logo-icon {
    background: #ef4444;
    color: white;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 24px;
    border-radius: 4px;
}

.logo-text h1 {
    font-size: 32px;
    font-weight: bold;
    color: white;
    margin-bottom: 5px;
    transition: color 0.3s ease;
}

.logo-text p {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.7);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin: 0;
    transition: color 0.3s ease;
}

.nav-menu {
    display: flex;
    list-style: none;
    gap: 40px;
    align-items: center;
}

.nav-menu .close-btn {
    display: none;
    visibility: hidden;
    opacity: 0;
}

.nav-menu a {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: color 0.3s;
}

.nav-menu a:hover,
.nav-menu a.active {
    color: #ef4444;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.contact-btn {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 12px 24px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 25px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    backdrop-filter: blur(10px);
}

.contact-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
}

.login-btn {
    background: transparent;
    color: rgba(255, 255, 255, 0.9);
    padding: 12px 24px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 25px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    backdrop-filter: blur(10px);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 14px;
}

.login-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
}

/* Scrolled state for navbar */
.navbar.scrolled {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    transition: all 0.3s ease;
}

.navbar.scrolled .logo-text h1 {
    color: #333;
}

.navbar.scrolled .logo-text p {
    color: rgba(0, 0, 0, 0.6);
}

.navbar.scrolled .nav-menu a {
    color: rgba(0, 0, 0, 0.8);
}

.navbar.scrolled .nav-menu a:hover,
.navbar.scrolled .nav-menu a.active {
    color: #ef4444;
}

.navbar.scrolled .contact-btn {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
}

.navbar.scrolled .contact-btn:hover {
    background: #dc2626;
    border-color: #dc2626;
}

.navbar.scrolled .login-btn {
    background: transparent;
    color: rgba(0, 0, 0, 0.8);
    border-color: rgba(0, 0, 0, 0.3);
}

.navbar.scrolled .login-btn:hover {
    background: rgba(0, 0, 0, 0.05);
    border-color: #ef4444;
    color: #ef4444;
}

.navbar.scrolled .mobile-menu-toggle {
    color: #333;
}

.navbar.scrolled .mobile-menu-toggle:hover {
    color: #ef4444;
}

.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    color: white;
    transition: color 0.3s;
    min-width: 40px;
    min-height: 40px;
    border-radius: 4px;
}

.mobile-menu-toggle:hover {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
}

.mobile-menu-toggle svg {
    stroke-width: 2;
    stroke: currentColor;
    fill: none;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    width: 100%;
}

/* Hero Section */
.hero {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, #475569 0%, #64748b 50%, #0f766e 100%);
    display: flex;
    align-items: center;
    overflow: hidden;
}

.hero-video-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-video-bg video {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    transform: translate(-50%, -50%);
    object-fit: cover;
    opacity: 0.7;
}

.hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.8;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(71,85,105,0.6) 0%, rgba(100,116,139,0.6) 50%, rgba(15,118,110,0.6) 100%);
    z-index: 2;
}

.hero .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 3;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    width: 100%;
}

.hero-content h1 {
    font-size: 5rem;
    font-weight: bold;
    color: white;
    line-height: 0.9;
    margin-bottom: 30px;
}

.hero-content p {
    font-size: 18px;
    color: rgba(255,255,255,0.8);
    margin-bottom: 40px;
    max-width: 400px;
}

.hero-btn {
    background: white;
    color: #111;
    padding: 16px 32px;
    border: none;
    border-radius: 25px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: background 0.3s;
}

.hero-btn:hover {
    background: #f1f1f1;
}

/* Event Card */
.event-card {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 12px;
    padding: 30px;
    color: white;
    max-width: 350px;
    margin-left: auto;
}

.event-header {
    margin-bottom: 25px;
}

.event-label {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255,255,255,0.7);
    margin-bottom: 10px;
}

.event-title {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 20px;
}

.event-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.event-date {
    background: rgba(255,255,255,0.1);
    padding: 20px;
    border-radius: 8px;
    grid-column: 1 / -1;
}

.event-time {
    background: rgba(255,255,255,0.1);
    padding: 15px;
    border-radius: 8px;
}

.event-duration {
    background: rgba(255,255,255,0.1);
    padding: 15px;
    border-radius: 8px;
}

.detail-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255,255,255,0.7);
    margin-bottom: 5px;
}

.detail-value {
    font-size: 24px;
    font-weight: bold;
}

.detail-value.small {
    font-size: 18px;
}

.event-note {
    margin-top: 20px;
    font-size: 11px;
    color: rgba(255,255,255,0.7);
}

/* Vertical Text */
.vertical-text {
    position: absolute;
    left: 30px;
    top: 50%;
    transform: translateY(-50%) rotate(-90deg);
    color: white;
    font-weight: bold;
    font-size: 18px;
    letter-spacing: 3px;
    z-index: 3;
}

/* About Section */
.about-section {
    background: white;
    padding: 100px 0;
    overflow: hidden;
}

.about-section .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Main Grid Layout */
.about-main-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr 1fr;
    gap: 60px;
    align-items: start;
}

.about-column {
    position: relative;
}

/* Left Image Column */
.about-image-column {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.section-label-container {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.label-bracket,
.label-text {
    font-size: 14px;
    font-weight: 600;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.label-bracket {
    font-size: 18px;
    font-weight: 700;
}

/* Center Content Column */
.about-content-column {
    display: flex;
    flex-direction: column;
    gap: 50px;
}

.content-wrapper {
    padding: 40px 0;
}

.about-title {
    font-size: 4rem;
    font-weight: 900;
    color: #111;
    line-height: 1.1;
    margin-bottom: 30px;
    text-transform: uppercase;
    letter-spacing: -1px;
}

.about-description {
    font-size: 16px;
    color: #666;
    line-height: 1.7;
    margin-bottom: 40px;
}

/* Image Containers */
.image-container {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.image-container:hover {
    transform: translateY(-10px);
}

.about-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    display: block;
}

.about-content-column .image-container {
    height: 300px;
}

.about-content-column .about-image {
    height: 300px;
}

/* Button Styling */
.discover-btn {
    display: inline-flex;
    align-items: center;
    gap: 15px;
    background: #111;
    color: white;
    text-decoration: none;
    padding: 18px 35px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.discover-btn:hover {
    background: #333;
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.btn-icon {
    width: 20px;
    height: 20px;
    fill: currentColor;
    transition: transform 0.3s ease;
}

.discover-btn:hover .btn-icon {
    transform: translateX(5px);
}

/* Right Stats Column */
.about-stats-column {
    padding-top: 80px;
}

.stats-container {
    display: flex;
    flex-direction: column;
    gap: 60px;
}

.stat-item {
    text-align: center;
    padding: 30px 20px;
    background: rgba(0,0,0,0.02);
    border-radius: 15px;
    transition: all 0.3s ease;
}

.stat-item:hover {
    background: rgba(0,0,0,0.05);
    transform: translateY(-5px);
}

.counter-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    margin-bottom: 15px;
}

.counter {
    font-size: 3.5rem;
    font-weight: 900;
    color: #111;
    line-height: 1;
}

.counter-suffix {
    font-size: 2.5rem;
    font-weight: 900;
    color: #ef4444;
}

.stat-label {
    font-size: 12px;
    font-weight: 700;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 2px;
    line-height: 1.4;
    margin: 0;
}

/* Animation Classes */
.slideInUp {
    animation: slideInUp 0.8s ease-out forwards;
}

.slideInLeft {
    animation: slideInLeft 0.8s ease-out forwards;
}

.slideInDown {
    animation: slideInDown 0.8s ease-out forwards;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Why Stride Section */
.why-stride-section {
    background: white;
    padding: 100px 0;
} 

.why-stride-section .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
} 

.stride-header {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    margin-bottom: 80px;
    align-items: flex-start;
}

.stride-title-section {
    padding-right: 40px;
}

.stride-title-section .section-label {
    display: inline-block;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #111;
    margin-bottom: 20px;
}

.stride-title-section .label-bracket {
    color: #111;
    font-weight: normal;
    margin: 0 8px;
}

.stride-title {
    font-size: 4rem;
    font-weight: 900;
    color: #111;
    line-height: 1.1;
    margin-bottom: 0;
    text-transform: uppercase;
    letter-spacing: -1px;
}

.stride-description {
    font-size: 16px;
    color: #666;
    line-height: 1.8;
    padding-top: 20px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
}

.feature-card {
    border: 2px solid #e5e5e5;
    padding: 40px 30px;
    text-align: center;
    transition: all 0.3s ease;
    background: white;
    min-height: 320px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

.feature-card:hover {
    border-color: #ef4444;
    transform: translateY(-5px);
}

.feature-card.cta-card {
    background: #ef4444;
    border: none;
    color: white;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 320px;
    text-align: left;
    padding: 40px 30px 30px 40px;
}

.feature-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    border: 2px solid #ef4444;
}

.feature-icon svg {
    width: 32px;
    height: 32px;
    color: #ef4444;
    stroke-width: 1.5;
}

.feature-title {
    font-size: 18px;
    font-weight: 700;
    color: #111;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    line-height: 1.3;
}

.feature-description {
    font-size: 14px;
    color: #666;
    line-height: 1.6;
}

.cta-content {
    text-align: left;
    z-index: 2;
    position: relative;
}

.cta-title {
    font-size: 1.8rem;
    font-weight: 900;
    color: white;
    line-height: 1.1;
    margin-bottom: 30px;
    text-transform: uppercase;
    letter-spacing: -0.5px;
}

.cta-runner-image {
    position: absolute;
    right: -10px;
    bottom: 0;
    width: 120px;
    height: 160px;
    z-index: 1;
}

.cta-runner-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    opacity: 0.9;
}

.join-btn {
    background: white;
    color: #111;
    padding: 14px 28px;
    border: none;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    z-index: 3;
}

.join-btn:hover {
    background: #f1f1f1;
    transform: translateY(-2px);
}

.join-btn svg {
    transition: transform 0.3s;
}

.join-btn:hover svg {
    transform: translateX(3px);
}

/* Exclusive Features Section */
.exclusive-features-section {
    background: linear-gradient(135deg, #334155 0%, #475569 50%, #0f766e 100%);
    padding: 100px 0;
    color: white;
}

.exclusive-features-section .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.features-header {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 60px;
    margin-bottom: 80px;
    align-items: flex-start;
}

.features-label {
    display: inline-block;
    background: rgba(255,255,255,0.1);
    color: white;
    padding: 8px 20px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: 1px solid rgba(255,255,255,0.2);
}

.features-content h2 {
    font-size: 4rem;
    font-weight: bold;
    color: white;
    line-height: 1.1;
    margin-bottom: 30px;
    text-transform: uppercase;
}

.features-content p {
    font-size: 16px;
    color: rgba(255,255,255,0.8);
    line-height: 1.8;
}

.features-showcase {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.feature-showcase-item {
    position: relative;
    height: 400px;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.feature-showcase-item:hover {
    transform: translateY(-10px);
}

.feature-showcase-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.feature-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 30px;
}

.feature-overlay h3 {
    font-size: 24px;
    font-weight: bold;
    color: white;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.feature-overlay p {
    font-size: 14px;
    color: rgba(255,255,255,0.9);
    line-height: 1.6;
}

/* Challenges Section */
.challenges-section {
    background: white;
    padding: 100px 0;
}

.challenges-section .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.challenges-header {
    text-align: center;
    margin-bottom: 80px;
}

.challenges-label {
    display: inline-block;
    background: #111;
    color: white;
    padding: 8px 20px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 30px;
}

.challenges-title {
    font-size: 4rem;
    font-weight: bold;
    color: #111;
    line-height: 1.1;
    margin-bottom: 30px;
    text-transform: uppercase;
}

.challenges-description {
    font-size: 16px;
    color: #666;
    line-height: 1.8;
    max-width: 600px;
    margin: 0 auto;
}

.challenge-card {
    border: 2px solid #e5e5e5;
    border-radius: 12px;
    overflow: hidden;
    display: grid;
    grid-template-columns: 200px 200px 1fr 200px;
    align-items: center;
    transition: all 0.3s ease;
}

.challenge-card:hover {
    border-color: #ef4444;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.challenge-date {
    padding: 40px 30px;
    text-align: center;
    border-right: 1px solid #e5e5e5;
}

.challenge-date-main {
    font-size: 2rem;
    font-weight: bold;
    color: #111;
    margin-bottom: 5px;
    text-transform: uppercase;
}

.challenge-date-day {
    font-size: 14px;
    font-weight: 600;
    color: #ef4444;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.challenge-poster {
    padding: 20px;
    border-right: 1px solid #e5e5e5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.challenge-poster img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    border-radius: 8px;
}

.challenge-info {
    padding: 40px 30px;
    border-right: 1px solid #e5e5e5;
}

.challenge-info h3 {
    font-size: 2rem;
    font-weight: bold;
    color: #111;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.challenge-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.challenge-detail {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #666;
}

.challenge-detail svg {
    width: 16px;
    height: 16px;
    color: #ef4444;
}

.challenge-detail strong {
    color: #111;
}

.challenge-description {
    font-size: 14px;
    color: #666;
    line-height: 1.6;
}

.challenge-actions {
    padding: 40px 30px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    align-items: center;
}

.buy-ticket-btn {
    background: #ef4444;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.3s;
    text-transform: uppercase;
    letter-spacing: 1px;
    width: 100%;
    justify-content: center;
}

.buy-ticket-btn:hover {
    background: #dc2626;
}

.discover-more-btn {
    background: #111;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.3s;
    text-transform: uppercase;
    letter-spacing: 1px;
    width: 100%;
    justify-content: center;
}

.discover-more-btn:hover {
    background: #333;
}

/* Sponsors Section */
.sponsors-section {
    background: white;
    padding: 60px 0;
    border-bottom: 1px solid #e5e5e5;
}

.sponsors-section .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sponsors-title {
    font-size: 18px;
    font-weight: bold;
    color: #111;
    text-transform: uppercase;
    letter-spacing: 1px;
    max-width: 200px;
}

.sponsors-logos {
    display: flex;
    align-items: center;
    gap: 60px;
}

.sponsor-logo {
    font-size: 24px;
    font-weight: bold;
    color: #111;
    text-transform: uppercase;
    letter-spacing: 2px;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.sponsor-logo:hover {
    opacity: 1;
}

.sponsor-logo.focus {
    font-weight: 300;
    letter-spacing: 4px;
}

.sponsor-logo.knife {
    font-weight: 900;
}

.sponsor-logo.strana {
    border: 2px solid #111;
    padding: 8px 16px;
    font-size: 18px;
    font-weight: 500;
}

.sponsor-logo.attitude {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.sponsor-logo.attitude .main {
    font-size: 24px;
    font-weight: bold;
}

.sponsor-logo.attitude .sub {
    font-size: 10px;
    font-weight: 400;
    letter-spacing: 1px;
    margin-top: 2px;
}

/* CTA Section */
.cta-section {
    position: relative;
    min-height: 70vh;
    background: linear-gradient(135deg, #334155 0%, #475569 50%, #0f766e 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    overflow: hidden;
}

.cta-video-bg {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100vw;
    height: 56.25vw; /* 16:9 aspect ratio */
    min-height: 100vh;
    min-width: 177.77vh; /* 16:9 aspect ratio */
    transform: translate(-50%, -50%);
    pointer-events: none;
    z-index: 1;
}

.cta-video-bg iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.cta-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(51,65,85,0.8) 0%, rgba(71,85,105,0.8) 50%, rgba(15,118,110,0.8) 100%);
    z-index: 2;
}

.cta-content {
    position: relative;
    z-index: 3;
    max-width: 600px;
    padding: 40px 20px;
    text-align: center;
    margin: 0 auto;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: white;
    line-height: 1.2;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.cta-description {
    font-size: 16px;
    color: rgba(255,255,255,0.9);
    line-height: 1.6;
    margin-bottom: 30px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.cta-btn {
    background: white;
    color: #111;
    padding: 14px 28px;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.3s;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.cta-btn:hover {
    background: #f1f1f1;
}

/* Team Section */
.team-section {
    background: white;
    padding: 100px 0;
}

.team-section .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.team-header {
    text-align: center;
    margin-bottom: 80px;
}

.team-label {
    display: inline-block;
    background: #111;
    color: white;
    padding: 8px 20px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 30px;
}

.team-title {
    font-size: 3rem;
    font-weight: bold;
    color: #111;
    line-height: 1.1;
    margin-bottom: 30px;
    text-transform: uppercase;
}

.team-description {
    font-size: 16px;
    color: #666;
    line-height: 1.8;
    max-width: 600px;
    margin: 0 auto;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
    margin-bottom: 60px;
}

.team-member {
    text-align: center;
}

.team-member-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.team-member:hover .team-member-image {
    transform: translateY(-10px);
}

.team-actions {
    text-align: center;
}

.more-team-btn {
    background: #111;
    color: white;
    padding: 16px 32px;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: background 0.3s;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.more-team-btn:hover {
    background: #333;
}

/* Footer Section */
.footer {
    background: #1e3a4a;
    color: white;
    padding: 80px 0 0;
}

.footer .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-top {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 60px;
    align-items: center;
    margin-bottom: 80px;
    padding-bottom: 80px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.footer-cta h2 {
    font-size: 4rem;
    font-weight: bold;
    color: white;
    line-height: 1.1;
    margin-bottom: 30px;
    text-transform: uppercase;
}

.footer-cta p {
    font-size: 16px;
    color: rgba(255,255,255,0.8);
    line-height: 1.8;
    max-width: 500px;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 15px;
}

.footer-logo-icon {
    background: #ef4444;
    color: white;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 30px;
    border-radius: 4px;
}

.footer-logo-text h3 {
    font-size: 36px;
    font-weight: bold;
    color: white;
    margin-bottom: 5px;
}

.footer-logo-text p {
    font-size: 12px;
    color: rgba(255,255,255,0.7);
    text-transform: uppercase;
    letter-spacing: 2px;
}

.footer-content {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 60px;
    margin-bottom: 60px;
}

.newsletter-section h3 {
    font-size: 18px;
    font-weight: bold;
    color: white;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 30px;
}

.newsletter-form {
    display: flex;
    margin-bottom: 20px;
}

.newsletter-input {
    flex: 1;
    padding: 15px 20px;
    border: 1px solid rgba(255,255,255,0.2);
    background: rgba(255,255,255,0.1);
    color: white;
    border-radius: 0;
    font-size: 16px;
}

.newsletter-input::placeholder {
    color: rgba(255,255,255,0.6);
}

.newsletter-btn {
    background: white;
    color: #111;
    padding: 15px 30px;
    border: none;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.3s;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.newsletter-btn:hover {
    background: #f1f1f1;
}

.newsletter-description {
    font-size: 14px;
    color: rgba(255,255,255,0.7);
    line-height: 1.6;
}

.footer-column h3 {
    font-size: 18px;
    font-weight: bold;
    color: white;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 30px;
}

.footer-column p {
    font-size: 16px;
    color: rgba(255,255,255,0.8);
    line-height: 1.8;
    margin-bottom: 10px;
}

.footer-column .phone-number {
    font-size: 18px;
    font-weight: 600;
    color: white;
    margin-bottom: 5px;
}

.footer-column .email {
    font-size: 18px;
    font-weight: 600;
    color: white;
}

.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 30px 0;
    border-top: 1px solid rgba(255,255,255,0.1);
}

.footer-nav {
    display: flex;
    gap: 40px;
}

.footer-nav a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: color 0.3s;
}

.footer-nav a:hover {
    color: white;
}

.footer-copyright {
    font-size: 14px;
    color: rgba(255,255,255,0.6);
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Header */
.header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    z-index: 1000;
    padding: 15px 0;
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.header.scrolled {
    background: rgba(255, 255, 255, 0.98);
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    display: flex;
    align-items: center;
    gap: 15px;
    text-decoration: none;
    color: #333;
}

.logo-icon {
    width: 40px;
    height: 40px;
    background: #ff6b35;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 900;
}

.logo-text h1 {
    font-size: 24px;
    font-weight: 900;
    margin: 0;
}

.logo-text p {
    font-size: 12px;
    color: #666;
    margin: 0;
}

.nav-menu {
    display: flex;
    list-style: none;
    gap: 40px;
    margin: 0;
}

.nav-menu a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    transition: color 0.3s ease;
}

.nav-menu a:hover {
    color: #ff6b35;
}

.menu-toggle {
    display: none;
    flex-direction: column;
    cursor: pointer;
    gap: 4px;
}

.menu-toggle span {
    width: 25px;
    height: 3px;
    background: #333;
    transition: all 0.3s ease;
}

/* Section Spacing and Transitions */
section {
    position: relative;
    z-index: 1;
}

section:not(.hero) {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.6s ease-out;
}

/* Additional Improvements for Clean Layout */

/* Ensure all sections have consistent vertical rhythm */
.hero,
.about-section,
.why-stride-section,
.exclusive-features-section,
.challenges-section,
.sponsors-section,
.cta-section,
.team-section {
    position: relative;
    z-index: 1;
}

/* Better spacing for text elements */
h1, h2, h3, h4, h5, h6 {
    margin-bottom: 1rem;
    font-weight: 900;
    line-height: 1.2;
}

p {
    margin-bottom: 1rem;
    line-height: 1.6;
}

/* Consistent button sizing */
.hero-btn,
.join-btn,
.cta-btn,
.more-team-btn,
.discover-btn {
    min-height: 48px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Better image loading */
img {
    max-width: 100%;
    height: auto;
    display: block;
}

/* Improved focus states for accessibility */
*:focus {
    outline: 2px solid #ff6b35;
    outline-offset: 2px;
}

button:focus,
a:focus {
    outline-color: #ff6b35;
}

/* Better text readability */
.challenges-section,
.team-section,
.why-stride-section {
    background: #fff;
}

.about-section,
.exclusive-features-section,
.sponsors-section {
    background: #f8f9fa;
}

/* Ensure proper stacking context */
.hero {
    z-index: 1;
}

.header {
    z-index: 1000;
}

/* Improved Button Styles */
button,
.hero-btn,
.join-btn,
.cta-btn,
.more-team-btn,
.buy-ticket-btn,
.discover-more-btn,
.newsletter-btn {
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    outline: none;
    position: relative;
    overflow: hidden;
}

button:focus,
.hero-btn:focus,
.join-btn:focus,
.cta-btn:focus,
.more-team-btn:focus,
.buy-ticket-btn:focus,
.discover-more-btn:focus,
.newsletter-btn:focus {
    outline: 2px solid #ff6b35;
    outline-offset: 2px;
}

/* Improved Link Styles */
a {
    transition: all 0.3s ease;
}

a:focus {
    outline: 2px solid #ff6b35;
    outline-offset: 2px;
}

/* Loading State */
.loading {
    opacity: 0.7;
    pointer-events: none;
}

/* Scroll Behavior */
html {
    scroll-behavior: smooth;
    scroll-padding-top: 80px;
}

/* Contact Bar */
.contact-bar {
    background: #333;
    color: #fff;
    padding: 12px 0;
    font-size: 14px;
    position: relative;
    z-index: 999;
}

.contact-bar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.social-icons {
    display: flex;
    gap: 15px;
    align-items: center;
}

.social-icons svg {
    width: 20px;
    height: 20px;
    color: #fff;
    transition: color 0.3s ease;
    cursor: pointer;
}

.social-icons svg:hover {
    color: #ff6b35;
}

.contact-info {
    display: flex;
    gap: 30px;
    align-items: center;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #fff;
}

.contact-item svg {
    width: 16px;
    height: 16px;
    color: #ff6b35;
    flex-shrink: 0;
}

.contact-item span {
    font-size: 13px;
    opacity: 0.9;
}

/* Navigation */
.navbar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 15px 0;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.navbar.scrolled {
    background: rgba(255, 255, 255, 0.98);
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
}

.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar .logo {
    display: flex;
    align-items: center;
    gap: 15px;
    text-decoration: none;
    color: #333;
}

.navbar .logo-icon {
    width: 40px;
    height: 40px;
    background: #ff6b35;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 900;
}

.navbar .logo-text h1 {
    font-size: 24px;
    font-weight: 900;
    margin: 0;
    color: #333;
}

.navbar .logo-text p {
    font-size: 12px;
    color: #666;
    margin: 0;
}

.nav-menu {
    display: flex;
    list-style: none;
    gap: 40px;
    margin: 0;
    padding: 0;
    align-items: center;
}

.nav-menu li {
    position: relative;
}

.nav-menu a {
    text-decoration: none;
    color: #333;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: color 0.3s ease;
    padding: 10px 0;
}

.nav-menu a:hover,
.nav-menu a.active {
    color: #ff6b35;
}

.nav-menu a.active::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    right: 0;
    height: 2px;
    background: #ff6b35;
}

.close-btn {
    display: none;
    background: none;
    border: none;
    font-size: 24px;
    color: #333;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 20px;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.login-btn {
    background: transparent;
    border: 2px solid #333;
    color: #333;
    padding: 10px 20px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.login-btn:hover {
    background: #333;
    color: #fff;
}

.contact-btn {
    background: #ff6b35;
    border: none;
    color: #fff;
    padding: 12px 24px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.contact-btn:hover {
    background: #e55a2b;
    transform: translateY(-2px);
}

.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    color: #333;
}

.mobile-menu-toggle svg {
    width: 24px;
    height: 24px;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .features-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .stride-header {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .stride-title {
        font-size: 3rem;
    }
}

@media (max-width: 768px) {
    .contact-bar .container {
        justify-content: space-between;
        gap: 10px;
        padding: 0 15px;
    }

    .social-icons {
        gap: 15px;
    }

    .social-icons svg {
        width: 16px;
        height: 16px;
    }

    .contact-info {
        gap: 15px;
        font-size: 12px;
    }

    .contact-item {
        gap: 5px;
    }

    .contact-item svg {
        width: 14px;
        height: 14px;
    }

    .contact-item span {
        display: none;
    }

    .contact-item:first-child span {
        display: inline;
    }

    .navbar .container {
        position: relative;
    }

    .nav-menu {
        position: fixed;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100vh;
        background: white;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 40px;
        transition: left 0.3s ease;
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }

    .nav-menu.active {
        left: 0;
    }

    .nav-menu::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(239,68,68,0.05) 0%, rgba(17,17,17,0.05) 100%);
        z-index: -1;
    }

    .nav-menu .close-btn {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: absolute;
        top: 30px;
        right: 30px;
        background: none;
        border: none;
        font-size: 30px;
        color: #111;
        cursor: pointer;
        padding: 10px;
        transition: color 0.3s;
        z-index: 1001;
    }

    .nav-menu .close-btn:hover {
        color: #ef4444;
    }

    .nav-menu li {
        font-size: 18px;
    }

    .nav-menu a {
        font-size: 18px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .mobile-menu-toggle {
        display: block;
    }

    .contact-btn {
        padding: 10px 20px;
        font-size: 14px;
    }

    .login-btn {
        padding: 8px 16px;
        font-size: 12px;
        margin-right: 8px;
    }

    .hero .container {
        grid-template-columns: 1fr;
        gap: 40px;
        text-align: center;
    }

    .hero-video-bg video {
        /* Ensure video covers mobile screen properly */
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        opacity: 0.5;
    }

    .hero-content h1 {
        font-size: 3rem;
    }

    .event-card {
        margin: 0 auto;
    }

    .vertical-text {
        display: none;
    }

    .about-main-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .about-column {
        order: 2;
    }

    .about-image-column {
        order: 1;
        gap: 30px;
    }

    .about-content-column {
        order: 2;
        gap: 30px;
    }

    .about-stats-column {
        order: 3;
        padding-top: 0;
    }

    .section-label-container {
        justify-content: center;
        margin-bottom: 30px;
    }

    .content-wrapper {
        padding: 20px 0;
        text-align: center;
    }

    .about-title {
        font-size: 2.5rem;
        line-height: 1.1;
        margin-bottom: 25px;
    }

    .about-description {
        margin-bottom: 35px;
    }

    .image-container {
        border-radius: 15px;
    }

    .about-image {
        height: 300px;
    }

    .about-content-column .image-container {
        height: 250px;
    }

    .about-content-column .about-image {
        height: 250px;
    }

    .stats-container {
        gap: 30px;
    }

    .stat-item {
        padding: 25px 15px;
    }

    .counter {
        font-size: 2.8rem;
    }

    .counter-suffix {
        font-size: 2rem;
    }

    .stat-label {
        font-size: 11px;
        letter-spacing: 1px;
    }

    .features-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .stride-title {
        font-size: 2.5rem;
    }
    
    .feature-card {
        min-height: 280px;
    }
    
    .feature-card.cta-card {
        min-height: 280px;
        padding: 30px 25px;
    }
    
    .cta-title {
        font-size: 1.5rem;
    }

    .features-header {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .features-content h2 {
        font-size: 2.5rem;
    }

    .features-showcase {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .feature-showcase-item {
        height: 300px;
    }

    .challenges-title {
        font-size: 2.5rem;
    }

    .challenge-card {
        grid-template-columns: 1fr;
        gap: 0;
    }

    .challenge-date,
    .challenge-poster,
    .challenge-info,
    .challenge-actions {
        border-right: none;
        border-bottom: 1px solid #e5e5e5;
    }

    .challenge-actions {
        border-bottom: none;
    }

    .sponsors-section .container {
        flex-direction: column;
        gap: 40px;
        text-align: center;
    }

    .sponsors-logos {
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
    }

    .team-title {
        font-size: 2.5rem;
    }
    
    .team-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .team-member-image {
        height: 300px;
    }

    .footer-top {
        grid-template-columns: 1fr;
        gap: 30px;
        text-align: center;
        margin-bottom: 60px;
        padding-bottom: 60px;
    }

    .footer-cta h2 {
        font-size: 2.2rem;
        line-height: 1.3;
        margin-bottom: 20px;
    }

    .footer-cta p {
        font-size: 15px;
        line-height: 1.7;
        margin: 0 auto;
        max-width: 400px;
    }

    .footer-logo {
        order: -1;
        margin-bottom: 20px;
    }

    .footer-logo-icon {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }

    .footer-logo-text h3 {
        font-size: 28px;
        margin-bottom: 3px;
    }

    .footer-logo-text p {
        font-size: 11px;
        letter-spacing: 1.5px;
    }

    .footer-content {
        grid-template-columns: 1fr;
        gap: 40px;
        margin-bottom: 50px;
    }

    .newsletter-section {
        text-align: center;
        order: -1;
        margin-bottom: 20px;
    }

    .newsletter-section h3 {
        font-size: 16px;
        margin-bottom: 20px;
    }

    .newsletter-form {
        flex-direction: column;
        gap: 12px;
        max-width: 350px;
        margin: 0 auto;
    }

    .newsletter-input {
        padding: 12px 18px;
        font-size: 15px;
        border-radius: 4px;
        text-align: center;
    }

    .newsletter-btn {
        padding: 12px 25px;
        font-size: 13px;
        border-radius: 25px;
        justify-content: center;
    }

    .newsletter-description {
        font-size: 13px;
        line-height: 1.5;
        margin-top: 15px;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
    }

    .footer-column {
        text-align: center;
        padding: 20px 15px;
        background: rgba(255,255,255,0.05);
        border-radius: 8px;
    }

    .footer-column h3 {
        font-size: 16px;
        margin-bottom: 20px;
        color: #fff;
    }

    .footer-column p {
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 8px;
    }

    .footer-column .phone-number,
    .footer-column .email {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .footer-bottom {
        flex-direction: column;
        gap: 25px;
        text-align: center;
        padding: 25px 0;
    }

    .footer-nav {
        flex-direction: column;
        gap: 15px;
        order: -1;
    }

    .footer-nav a {
        font-size: 15px;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        margin: 0 20px;
    }

    .footer-nav a:last-child {
        border-bottom: none;
    }

    .footer-copyright {
        font-size: 12px;
        line-height: 1.4;
        padding-top: 15px;
        border-top: 1px solid rgba(255,255,255,0.1);
        margin-top: 15px;
    }

    /* CTA Section Mobile */
    .cta-section {
        min-height: 60vh;
    }

    .cta-content {
        max-width: 90%;
        padding: 30px 15px;
    }

    .cta-title {
        font-size: 2rem;
        margin-bottom: 15px;
    }

    .cta-description {
        font-size: 14px;
        margin-bottom: 25px;
    }

    .cta-btn {
        padding: 12px 24px;
        font-size: 14px;
    }
}

/* Extra small mobile devices */
@media (max-width: 480px) {
    .contact-bar {
        padding: 8px 0;
    }

    .contact-bar .container {
        gap: 8px;
        padding: 0 10px;
    }

    .social-icons {
        gap: 10px;
    }

    .social-icons svg {
        width: 14px;
        height: 14px;
    }

    .contact-info {
        gap: 10px;
        font-size: 11px;
    }

    .contact-item span {
        display: none;
    }

    /* Show only location on very small screens */
    .contact-item:first-child span {
        display: inline;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* CTA Section Extra Small Mobile */
    .cta-section {
        min-height: 50vh;
    }

    .cta-content {
        padding: 20px 10px;
    }

    .cta-title {
        font-size: 1.8rem;
    }

    .cta-description {
        font-size: 13px;
        line-height: 1.5;
    }

    .cta-btn {
        padding: 10px 20px;
        font-size: 13px;
    }

    /* Footer Extra Small Mobile */
    .footer {
        padding: 60px 0 0;
    }

    .footer-top {
        gap: 25px;
        margin-bottom: 40px;
        padding-bottom: 40px;
    }

    .footer-cta h2 {
        font-size: 1.8rem;
        line-height: 1.2;
        margin-bottom: 15px;
    }

    .footer-cta p {
        font-size: 14px;
        line-height: 1.6;
    }

    .footer-logo-icon {
        width: 45px;
        height: 45px;
        font-size: 22px;
    }

    .footer-logo-text h3 {
        font-size: 24px;
    }

    .footer-logo-text p {
        font-size: 10px;
    }

    .footer-content {
        gap: 30px;
        margin-bottom: 40px;
    }

    .newsletter-section h3 {
        font-size: 15px;
        margin-bottom: 18px;
    }

    .newsletter-form {
        max-width: 300px;
        gap: 10px;
    }

    .newsletter-input {
        padding: 10px 15px;
        font-size: 14px;
    }

    .newsletter-btn {
        padding: 10px 20px;
        font-size: 12px;
    }

    .newsletter-description {
        font-size: 12px;
        margin-top: 12px;
        max-width: 250px;
    }

    .footer-column {
        padding: 15px 10px;
        margin: 0 5px;
    }

    .footer-column h3 {
        font-size: 15px;
        margin-bottom: 15px;
    }

    .footer-column p {
        font-size: 13px;
        margin-bottom: 6px;
    }

    .footer-column .phone-number,
    .footer-column .email {
        font-size: 15px;
    }

    .footer-bottom {
        gap: 20px;
        padding: 20px 0;
    }

    .footer-nav a {
        font-size: 14px;
        margin: 0 10px;
        padding: 6px 0;
    }

    .footer-copyright {
        font-size: 11px;
        padding-top: 12px;
        margin-top: 12px;
    }
}

/* Tablet Navigation */
@media (max-width: 1024px) and (min-width: 769px) {
    .navbar .container {
        padding: 0 15px;
    }

    .nav-menu {
        gap: 25px;
    }

    .nav-menu a {
        font-size: 15px;
    }

    .contact-btn {
        padding: 10px 20px;
        font-size: 14px;
    }

    .login-btn {
        padding: 8px 16px;
        font-size: 13px;
    }
}

/* Admin Access Link */
.admin-access {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #ef4444;
    color: white;
    padding: 12px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    transition: all 0.3s ease;
    z-index: 1000;
}

.admin-access:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}
</style>
