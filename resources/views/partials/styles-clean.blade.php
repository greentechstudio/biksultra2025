<style>
    /* =================================
       RESET & BASE STYLES
       ================================= */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        line-height: 1.6;
        background-color: white;
        overflow-x: hidden;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* =================================
       CONTACT BAR (FIXED TOP)
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
    }

    /* =================================
       NAVBAR (FIXED BELOW CONTACT BAR)
       ================================= */
    .navbar {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 16px 0;
        position: fixed;
        top: 42px;
        left: 0;
        right: 0;
        z-index: 999;
        transition: all 0.3s ease;
    }

    .navbar .container {
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
        margin: 0;
    }

    .logo-text p {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.8);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 0;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        gap: 40px;
        align-items: center;
        margin: 0;
        padding: 0;
    }

    .nav-menu .close-btn {
        display: none;
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

    .contact-btn,
    .login-btn {
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
        text-decoration: none;
        font-size: 14px;
    }

    .contact-btn:hover,
    .login-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
    }

    .mobile-menu-toggle {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
        color: white;
        border-radius: 4px;
    }

    .mobile-menu-toggle svg {
        width: 24px;
        height: 24px;
    }

    /* =================================
       HERO SECTION (FULL VIEWPORT)
       ================================= */
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
        color: #111;
    }

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
       EVENT CARD IN HERO
       ================================= */
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
        margin-bottom: 20px;
    }

    .event-date {
        background: rgba(255,255,255,0.1);
        padding: 20px;
        border-radius: 8px;
        grid-column: 1 / -1;
    }

    .event-time,
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
        font-size: 11px;
        color: rgba(255,255,255,0.7);
        margin-top: 15px;
    }

    /* =================================
       SCROLLED STATE FOR HEADERS
       ================================= */
    .contact-bar.scrolled {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .contact-bar.scrolled .contact-info,
    .contact-bar.scrolled .social-icons svg {
        color: rgba(0, 0, 0, 0.7);
    }

    .navbar.scrolled {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
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

    .navbar.scrolled .login-btn {
        background: transparent;
        color: rgba(0, 0, 0, 0.8);
        border-color: rgba(0, 0, 0, 0.3);
    }

    /* =================================
       BASIC SECTIONS
       ================================= */
    .about-section {
        background: white;
        padding: 120px 0;
    }

    .about-section h2 {
        font-size: 3rem;
        color: #111;
        margin-bottom: 30px;
        text-transform: uppercase;
    }

    .about-section p {
        font-size: 16px;
        color: #666;
        line-height: 1.8;
        max-width: 600px;
    }

    /* =================================
       ADMIN ACCESS
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
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .admin-access:hover {
        background: #dc2626;
        color: white;
    }

    /* =================================
       RESPONSIVE DESIGN
       ================================= */
    @media (max-width: 768px) {
        .contact-info {
            display: none;
        }
        
        .nav-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 300px;
            height: 100vh;
            background: rgba(0, 0, 0, 0.95);
            flex-direction: column;
            justify-content: flex-start;
            padding: 80px 30px 30px;
            transition: right 0.3s ease;
            z-index: 1001;
        }
        
        .nav-menu.active {
            right: 0;
        }
        
        .nav-menu .close-btn {
            display: block;
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }
        
        .nav-menu a {
            color: white;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 18px;
        }
        
        .mobile-menu-toggle {
            display: block;
        }
        
        .hero .container {
            grid-template-columns: 1fr;
            gap: 30px;
            text-align: center;
        }
        
        .hero-content h1 {
            font-size: 3rem;
        }
        
        .event-card {
            margin: 30px auto 0;
            max-width: 100%;
        }
        
        .vertical-text {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .hero-content h1 {
            font-size: 2.5rem;
        }
        
        .hero-content p {
            font-size: 16px;
        }
        
        .hero-btn {
            padding: 14px 28px;
            font-size: 16px;
        }
    }
</style>
