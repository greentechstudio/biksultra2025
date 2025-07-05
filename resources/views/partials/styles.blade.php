<style>
    /* =================================
       BASE STYLES - EXACT FROM HOME.HTML
       ================================= */
    html {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        scroll-behavior: smooth;
    }

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
        background-color: white;
        overflow-x: hidden;
    }

    /* =================================
       ADMIN ACCESS LINK
       ================================= */
    .admin-access {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        background: #ef4444;
        color: white;
        padding: 12px 20px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .admin-access:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        color: white;
    }

    /* =================================
       TOP CONTACT BAR
       ================================= */
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

    /* =================================
       NAVIGATION
       ================================= */
    .navbar {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        box-shadow: none;
        padding: 16px 0;
        position: fixed;
        top: 42px;
        left: 0;
        right: 0;
        z-index: 999;
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
        gap: 10px;
    }

    .logo-icon {
        background: #ef4444;
        color: white;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 20px;
        border-radius: 4px;
    }

    .logo-text h1 {
        font-size: 24px;
        font-weight: bold;
        color: white;
        transition: color 0.3s ease;
    }

    .logo-text p {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.8);
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: color 0.3s ease;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        gap: 40px;
        align-items: center;
    }

    .nav-menu .close-btn {
        display: none !important;
        visibility: hidden;
        opacity: 0;
        position: absolute;
        top: -9999px;
    }

    .nav-menu a {
        text-decoration: none;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
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
        text-decoration: none;
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
        text-decoration: none;
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

    /* =================================
       HERO SECTION
       ================================= */
    .hero {
        position: relative;
        min-height: 100vh;
        background: linear-gradient(135deg, #475569 0%, #64748b 50%, #0f766e 100%);
        display: flex;
        align-items: center;
        overflow: hidden;
        margin: 0;
        padding: 0;
        top: 0;
        left: 0;
        right: 0;
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
        text-decoration: none;
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

    /* =================================
       ABOUT SECTION (FROM HOME.HTML)
       ================================= */
    .about-section {
        background: white;
        padding: 120px 0;
    }

    .about-section .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .about-main-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 60px;
        align-items: start;
    }

    .about-column {
        position: relative;
    }

    .section-label-container {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 40px;
        justify-content: flex-start;
    }

    .label-bracket {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }

    .label-text {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #666;
    }

    .image-container {
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .about-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .about-image:hover {
        transform: scale(1.05);
    }

    .about-content-column {
        display: flex;
        flex-direction: column;
        gap: 40px;
    }

    .content-wrapper {
        padding: 40px 0;
    }

    .about-title {
        font-size: 3.5rem;
        font-weight: bold;
        color: #111;
        line-height: 1.1;
        margin-bottom: 30px;
        text-transform: uppercase;
    }

    .about-description {
        font-size: 16px;
        color: #666;
        line-height: 1.8;
        margin-bottom: 40px;
    }

    .discover-btn {
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
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        width: fit-content;
    }

    .discover-btn:hover {
        background: #333;
    }

    .btn-icon {
        width: 16px;
        height: 16px;
        fill: currentColor;
    }

    .about-content-column .image-container {
        height: 300px;
    }

    .about-content-column .about-image {
        height: 300px;
    }

    .about-stats-column {
        padding-top: 60px;
    }

    .stats-container {
        display: flex;
        flex-direction: column;
        gap: 40px;
    }

    .stat-item {
        background: #f8f9fa;
        padding: 30px 20px;
        border-radius: 12px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-5px);
    }

    .counter-wrapper {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 5px;
        margin-bottom: 10px;
    }

    .counter {
        font-size: 3.5rem;
        font-weight: bold;
        color: #111;
    }

    .counter-suffix {
        font-size: 2.5rem;
        font-weight: bold;
        color: #ef4444;
    }

    .stat-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #666;
        font-weight: 600;
    }

    /* =================================
       WHY STRIDE SECTION (FROM HOME.HTML)
       ================================= */
    .why-stride-section {
        background: #f8f9fa;
        padding: 120px 0;
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
        align-items: start;
        margin-bottom: 80px;
    }

    .stride-title-section {
        position: relative;
    }

    .section-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 30px;
    }

    .stride-title {
        font-size: 4rem;
        font-weight: bold;
        color: #111;
        line-height: 1.1;
        text-transform: uppercase;
    }

    .stride-description {
        padding-top: 20px;
    }

    .stride-description p {
        font-size: 16px;
        color: #666;
        line-height: 1.8;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
    }

    /* =================================
       BASIC RESPONSIVE STYLES 
       ================================= */
    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 3rem;
        }
        
        .hero .container {
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        .about-main-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }
    }
</style>