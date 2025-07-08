    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-green: #273F0B;
            --secondary-green: #3d5f1a;
            --accent-orange: #ED3D26;
            --dark-orange: #c83017;
            --dark-bg: #161616;
            --light-gray: #f8f9fa;
            --text-dark: #333;
            --text-light: #666;
            --border-radius-sm: 8px;
            --border-radius-md: 16px;
            --border-radius-lg: 24px;
            --border-radius-xl: 32px;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.1);
            --shadow-md: 0 8px 32px rgba(0,0,0,0.15);
            --shadow-lg: 0 16px 64px rgba(0,0,0,0.2);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            
            /* Enhanced Typography Scale */
            --font-weight-light: 300;
            --font-weight-regular: 400;
            --font-weight-medium: 500;
            --font-weight-semibold: 600;
            --font-weight-bold: 700;
            --font-weight-extrabold: 800;
            --font-weight-black: 900;
            
            /* Fluid Typography Scale */
            --text-xs: clamp(0.75rem, 0.5vw + 0.7rem, 0.875rem);
            --text-sm: clamp(0.875rem, 0.6vw + 0.8rem, 1rem);
            --text-base: clamp(1rem, 0.8vw + 0.9rem, 1.125rem);
            --text-lg: clamp(1.125rem, 1vw + 1rem, 1.25rem);
            --text-xl: clamp(1.25rem, 1.2vw + 1.1rem, 1.5rem);
            --text-2xl: clamp(1.5rem, 1.5vw + 1.3rem, 1.875rem);
            --text-3xl: clamp(1.875rem, 2vw + 1.5rem, 2.25rem);
            --text-4xl: clamp(2.25rem, 2.5vw + 1.8rem, 3rem);
            --text-5xl: clamp(3rem, 4vw + 2.5rem, 4rem);
            --text-6xl: clamp(4rem, 6vw + 3rem, 6rem);
            
            /* Line Heights */
            --leading-tight: 1.1;
            --leading-snug: 1.25;
            --leading-normal: 1.5;
            --leading-relaxed: 1.625;
            --leading-loose: 2;
            
            /* Letter Spacing */
            --tracking-tighter: -0.05em;
            --tracking-tight: -0.025em;
            --tracking-normal: 0em;
            --tracking-wide: 0.025em;
            --tracking-wider: 0.05em;
            --tracking-widest: 0.1em;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            font-size: var(--text-base);
            line-height: var(--leading-relaxed);
            color: var(--text-dark);
            font-weight: var(--font-weight-regular);
            scroll-behavior: smooth;
            overflow-x: hidden;
            position: relative;
            font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Animated Gradient Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #ED3D26, #273F0B, #161616, #ED3D26);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            z-index: -2;
        }

        /* Animated overlay pattern */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(237, 61, 38, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(39, 63, 11, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 60% 40%, rgba(22, 22, 22, 0.2) 0%, transparent 50%);
            animation: floatingPattern 20s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }
            25% {
                background-position: 100% 50%;
            }
            50% {
                background-position: 100% 100%;
            }
            75% {
                background-position: 0% 100%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes floatingPattern {
            0%, 100% {
                transform: scale(1) rotate(0deg);
                opacity: 0.3;
            }
            33% {
                transform: scale(1.1) rotate(120deg);
                opacity: 0.5;
            }
            66% {
                transform: scale(0.9) rotate(240deg);
                opacity: 0.4;
            }
        }

        /* Floating particles */
        .floating-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(1) {
            left: 10%;
            top: 20%;
            animation-delay: 0s;
            animation-duration: 8s;
        }

        .particle:nth-child(2) {
            left: 80%;
            top: 30%;
            animation-delay: 2s;
            animation-duration: 6s;
        }

        .particle:nth-child(3) {
            left: 60%;
            top: 70%;
            animation-delay: 4s;
            animation-duration: 10s;
        }

        .particle:nth-child(4) {
            left: 30%;
            top: 80%;
            animation-delay: 1s;
            animation-duration: 7s;
        }

        .particle:nth-child(5) {
            left: 90%;
            top: 10%;
            animation-delay: 3s;
            animation-duration: 9s;
        }

        .particle:nth-child(6) {
            left: 45%;
            top: 15%;
            animation-delay: 5s;
            animation-duration: 12s;
        }

        .particle:nth-child(7) {
            left: 75%;
            top: 85%;
            animation-delay: 6s;
            animation-duration: 7s;
        }

        .particle:nth-child(8) {
            left: 15%;
            top: 65%;
            animation-delay: 2.5s;
            animation-duration: 9s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) translateX(0px) scale(1);
                opacity: 0.3;
            }
            25% {
                transform: translateY(-20px) translateX(10px) scale(1.2);
                opacity: 0.8;
            }
            50% {
                transform: translateY(-10px) translateX(-15px) scale(0.8);
                opacity: 0.5;
            }
            75% {
                transform: translateY(-25px) translateX(5px) scale(1.1);
                opacity: 0.7;
            }
        }

        /* Pulsing effect for specific sections */
        .section-glow {
            position: relative;
        }

        .section-glow::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(237, 61, 38, 0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 0.3;
                transform: scale(1);
            }
            50% {
                opacity: 0.6;
                transform: scale(1.05);
            }
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Header */
        .header {
            background: rgba(39, 63, 11, 0.9);
            backdrop-filter: blur(20px);
            color: white;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition);
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 72px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, white, #f8f9fa);
            border-radius: var(--border-radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-green);
            font-weight: var(--font-weight-bold);
            font-size: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .logo-header {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-green);
            font-weight: var(--font-weight-bold);
            font-size: 1.5rem;
            transition: var(--transition);
        }

        .logo-icon:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .logo-text h1 {
            font-size: var(--text-lg);
            margin-bottom: 2px;
            font-weight: var(--font-weight-bold);
            letter-spacing: var(--tracking-tight);
            line-height: var(--leading-tight);
        }

        .logo-text p {
            font-size: var(--text-sm);
            opacity: 0.85;
            font-weight: var(--font-weight-medium);
            letter-spacing: var(--tracking-normal);
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 32px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: var(--font-weight-medium);
            font-size: var(--text-base);
            transition: var(--transition);
            padding: 8px 16px;
            border-radius: var(--border-radius-sm);
            position: relative;
            letter-spacing: var(--tracking-normal);
        }

        .nav-links a:hover {
            color: var(--accent-orange);
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--accent-orange);
            transition: var(--transition);
            transform: translateX(-50%);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 8px;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }

        .mobile-menu-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .mobile-menu-toggle span {
            width: 24px;
            height: 3px;
            background: white;
            margin: 3px 0;
            transition: var(--transition);
            border-radius: 2px;
        }

        .mobile-menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        .mobile-menu {
            position: fixed;
            top: 88px;
            left: 0;
            right: 0;
            background: var(--primary-green);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 24px;
            transform: translateY(-100%);
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 999;
        }

        .mobile-menu.active {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }

        .mobile-menu-links {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .mobile-menu-links a {
            color: white;
            text-decoration: none;
            font-weight: var(--font-weight-medium);
            padding: 16px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition);
            font-size: var(--text-lg);
            letter-spacing: var(--tracking-normal);
        }

        .mobile-menu-links a:hover {
            color: var(--accent-orange);
            padding-left: 16px;
        }

        .mobile-menu-links a:last-child {
            border-bottom: none;
        }

        /* Hero Section */
        .hero {
            background: rgba(39, 63, 11, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            padding: 140px 0 100px;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 70%, rgba(255, 107, 53, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 70% 30%, rgba(74, 124, 89, 0.3) 0%, transparent 50%);
            opacity: 0.6;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-text h1 {
            font-size: var(--text-6xl);
            font-weight: var(--font-weight-black);
            margin-bottom: 32px;
            text-transform: uppercase;
            letter-spacing: var(--tracking-tighter);
            line-height: var(--leading-tight);
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-text .highlight {
            color: var(--accent-orange);
            text-shadow: 0 4px 16px rgba(255, 107, 53, 0.4);
            display: inline-block;
            transform: skew(-2deg);
        }

        .hero-text .date {
            font-size: var(--text-2xl);
            margin-bottom: 40px;
            opacity: 0.95;
            font-weight: var(--font-weight-semibold);
            letter-spacing: var(--tracking-wide);
            line-height: var(--leading-snug);
        }

        .hashtag {
            background: linear-gradient(135deg, var(--accent-orange), var(--dark-orange));
            color: white;
            padding: 18px 36px;
            border-radius: var(--border-radius-xl);
            font-size: var(--text-lg);
            font-weight: var(--font-weight-bold);
            display: inline-block;
            margin-bottom: 48px;
            box-shadow: var(--shadow-md);
            letter-spacing: var(--tracking-wide);
            transition: var(--transition);
            transform: rotate(-1deg);
        }

        .hashtag:hover {
            transform: translateY(-4px) rotate(0deg);
            box-shadow: var(--shadow-lg);
        }

        .cta-button {
            background: linear-gradient(135deg, var(--accent-orange), var(--dark-orange));
            color: white;
            padding: 20px 50px;
            border: none;
            border-radius: var(--border-radius-xl);
            font-size: var(--text-lg);
            font-weight: var(--font-weight-bold);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 16px;
            box-shadow: var(--shadow-md);
            letter-spacing: var(--tracking-wide);
            text-transform: uppercase;
        }

        .cta-button::after {
            content: '→';
            font-size: var(--text-xl);
            font-weight: var(--font-weight-extrabold);
            transition: var(--transition);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .cta-button:hover::after {
            transform: translateX(4px);
        }

        .hero-image {
            text-align: center;
            position: relative;
        }

        .runner-image {
            width: 100%;
            max-width: 450px;
            height: auto;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
        }

        .runner-image:hover {
            transform: scale(1.02);
        }

        /* Registration Section */
        .registration {
            background: rgba(237, 61, 38, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .registration::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        }

        .registration-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .registration-info h2 {
            font-size: var(--text-5xl);
            font-weight: var(--font-weight-black);
            margin-bottom: 48px;
            text-transform: uppercase;
            letter-spacing: var(--tracking-tighter);
            line-height: var(--leading-tight);
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .registration-details {
            font-size: var(--text-2xl);
            margin-bottom: 40px;
            font-weight: var(--font-weight-bold);
            letter-spacing: var(--tracking-wider);
            opacity: 0.95;
        }

        .early-bird {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(16px);
            padding: 32px;
            border-radius: var(--border-radius-lg);
            margin: 32px 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition);
        }

        .early-bird:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .early-bird h3 {
            font-size: var(--text-2xl);
            font-weight: var(--font-weight-extrabold);
            margin-bottom: 12px;
            letter-spacing: var(--tracking-widest);
            text-transform: uppercase;
        }

        .early-bird p {
            font-size: var(--text-base);
            font-weight: var(--font-weight-medium);
            letter-spacing: var(--tracking-wide);
            opacity: 0.9;
        }

        .registration-form {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(16px);
            padding: 48px;
            border-radius: var(--border-radius-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        .registration-form h3 {
            font-size: var(--text-3xl);
            font-weight: var(--font-weight-bold);
            margin-bottom: 28px;
            letter-spacing: var(--tracking-tight);
        }

        .registration-form p {
            font-size: var(--text-lg);
            margin-bottom: 40px;
            opacity: 0.9;
            line-height: var(--leading-relaxed);
            font-weight: var(--font-weight-regular);
        }

        /* Categories Section */
        .categories {
            background: rgba(39, 63, 11, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            padding: 120px 0;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .categories::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 80%, rgba(74, 124, 89, 0.3) 0%, transparent 50%);
        }

        .categories h2 {
            font-size: var(--text-4xl);
            text-align: center;
            margin-bottom: 96px;
            color: var(--accent-orange);
            font-weight: var(--font-weight-black);
            letter-spacing: var(--tracking-tighter);
            position: relative;
            z-index: 2;
            text-transform: uppercase;
            text-shadow: 0 4px 16px rgba(237, 61, 38, 0.3);
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            position: relative;
            z-index: 2;
        }

        .category-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(16px);
            padding: 48px 32px;
            border-radius: var(--border-radius-lg);
            text-align: center;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-orange), var(--dark-orange));
            transform: scaleX(0);
            transition: var(--transition);
        }

        .category-card:hover::before {
            transform: scaleX(1);
        }

        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            background: rgba(255,255,255,0.15);
        }

        .category-card h3 {
            font-size: var(--text-3xl);
            color: var(--accent-orange);
            margin-bottom: 32px;
            font-weight: var(--font-weight-extrabold);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            letter-spacing: var(--tracking-wide);
            text-transform: uppercase;
        }

        .category-card .price {
            font-size: var(--text-2xl);
            font-weight: var(--font-weight-bold);
            margin-bottom: 20px;
            color: white;
            letter-spacing: var(--tracking-normal);
        }

        .category-card .note {
            font-size: var(--text-sm);
            opacity: 0.8;
            font-style: italic;
            margin-bottom: 20px;
            color: var(--accent-orange);
            font-weight: var(--font-weight-medium);
            letter-spacing: var(--tracking-normal);
        }

        .category-card p:last-child {
            font-size: var(--text-base);
            line-height: var(--leading-relaxed);
            opacity: 0.9;
            font-weight: var(--font-weight-regular);
        }

        /* Prize Section */
        .prizes {
            background: rgba(237, 61, 38, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            padding: 120px 0;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .prizes::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 60% 40%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        }

        .prizes-content {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .prizes h2 {
            font-size: var(--text-4xl);
            margin-bottom: 48px;
            font-weight: var(--font-weight-black);
            letter-spacing: var(--tracking-tighter);
            text-transform: uppercase;
            text-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        .total-prize {
            font-size: var(--text-6xl);
            font-weight: var(--font-weight-black);
            margin: 64px 0;
            text-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            letter-spacing: var(--tracking-tighter);
            line-height: var(--leading-tight);
        }

        .total-prize span {
            font-size: 0.4em;
            opacity: 0.8;
            font-weight: var(--font-weight-bold);
        }

        .prizes p {
            font-size: var(--text-xl);
            margin-bottom: 56px;
            opacity: 0.9;
            font-weight: var(--font-weight-medium);
            letter-spacing: var(--tracking-normal);
        }

        .prizes h3 {
            font-size: var(--text-3xl);
            margin: 80px 0 56px;
            font-weight: var(--font-weight-bold);
            letter-spacing: var(--tracking-tight);
            text-transform: uppercase;
        }

        .prize-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 64px;
        }

        .prize-item {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(16px);
            padding: 40px 32px;
            border-radius: var(--border-radius-lg);
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .prize-item:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            background: rgba(255,255,255,0.2);
        }

        .prize-item i {
            font-size: 3rem;
            margin-bottom: 24px;
            color: white;
        }

        .prize-item h4 {
            font-size: var(--text-xl);
            margin-bottom: 20px;
            font-weight: var(--font-weight-bold);
            letter-spacing: var(--tracking-normal);
        }

        .prize-item p {
            font-size: var(--text-base);
            line-height: var(--leading-relaxed);
            opacity: 0.9;
            margin: 0;
            font-weight: var(--font-weight-regular);
        }

        /* Footer */
        .footer {
            background: rgba(22, 22, 22, 0.9);
            backdrop-filter: blur(20px);
            color: white;
            padding: 80px 0 40px;
            position: relative;
            overflow: hidden;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 20%, rgba(237, 61, 38, 0.1) 0%, transparent 50%);
        }

        .footer-top {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 80px;
            align-items: center;
            margin-bottom: 80px;
            padding-bottom: 80px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 2;
        }

        .footer-cta h2 {
            font-size: var(--text-4xl);
            font-weight: var(--font-weight-black);
            margin-bottom: 32px;
            color: var(--accent-orange);
            letter-spacing: var(--tracking-tight);
            line-height: var(--leading-tight);
        }

        .footer-cta p {
            font-size: var(--text-lg);
            line-height: var(--leading-relaxed);
            opacity: 0.85;
            font-weight: var(--font-weight-regular);
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 20px;
            justify-self: end;
        }

        .footer-logo-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--accent-orange), var(--dark-orange));
            border-radius: var(--border-radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .footer-logo {
            border-radius: var(--border-radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            transition: var(--transition);
        }

        .footer-logo-icon:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .footer-logo-text h3 {
            font-size: var(--text-xl);
            font-weight: var(--font-weight-bold);
            margin-bottom: 6px;
            color: white;
            letter-spacing: var(--tracking-tight);
        }

        .footer-logo-text p {
            font-size: var(--text-sm);
            opacity: 0.75;
            font-weight: var(--font-weight-medium);
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 60px;
            position: relative;
            z-index: 2;
        }

        .newsletter-section h3 {
            color: var(--accent-orange);
            margin-bottom: 28px;
            font-size: var(--text-xl);
            font-weight: var(--font-weight-bold);
            letter-spacing: var(--tracking-normal);
        }

        .newsletter-form {
            display: flex;
            gap: 0;
            margin-bottom: 20px;
            border-radius: var(--border-radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .newsletter-input {
            flex: 1;
            padding: 18px 24px;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: var(--text-base);
            font-weight: var(--font-weight-medium);
            backdrop-filter: blur(16px);
        }

        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
            font-size: var(--text-base);
        }

        .newsletter-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
        }

        .newsletter-btn {
            padding: 18px 28px;
            background: linear-gradient(135deg, var(--accent-orange), var(--dark-orange));
            color: white;
            border: none;
            font-weight: var(--font-weight-bold);
            font-size: var(--text-base);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: var(--tracking-normal);
        }

        .newsletter-btn:hover {
            background: linear-gradient(135deg, var(--dark-orange), var(--accent-orange));
            transform: translateY(-2px);
        }

        .newsletter-description {
            font-size: var(--text-sm);
            opacity: 0.75;
            line-height: var(--leading-relaxed);
            font-weight: var(--font-weight-regular);
        }

        .footer-column h3 {
            color: var(--accent-orange);
            margin-bottom: 28px;
            font-size: var(--text-xl);
            font-weight: var(--font-weight-bold);
            letter-spacing: var(--tracking-normal);
        }

        .footer-column p {
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 16px;
            line-height: var(--leading-relaxed);
            font-weight: var(--font-weight-regular);
            font-size: var(--text-base);
        }

        .phone-number, .email {
            color: rgba(255, 255, 255, 0.95);
            font-weight: var(--font-weight-medium);
            font-size: var(--text-base);
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 2;
        }

        .footer-nav {
            display: flex;
            gap: 32px;
        }

        .footer-nav a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: var(--font-weight-medium);
            transition: var(--transition);
            padding: 8px 0;
            position: relative;
        }

        .footer-nav a:hover {
            color: var(--accent-orange);
        }

        .footer-nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-orange);
            transition: var(--transition);
        }

        .footer-nav a:hover::after {
            width: 100%;
        }

        .footer-copyright {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.875rem;
            font-weight: var(--font-weight-medium);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .container {
                padding: 0 20px;
            }
            
            .hero-content {
                gap: 60px;
            }
            
            .footer-content {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }

            .footer-top {
                gap: 60px;
            }

            .newsletter-section {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .hero {
                padding: 120px 0 80px;
                min-height: 90vh;
            }

            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 48px;
            }

            .hero-text h1 {
                font-size: clamp(2rem, 12vw, 3.5rem);
            }

            .registration-content {
                grid-template-columns: 1fr;
                gap: 48px;
                text-align: center;
            }

            .registration-form {
                padding: 32px 24px;
            }

            .category-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .prize-details {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 32px;
                text-align: center;
            }

            .footer-top {
                grid-template-columns: 1fr;
                gap: 48px;
                text-align: center;
            }

            .footer-logo {
                justify-self: center;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 24px;
                text-align: center;
            }

            .footer-nav {
                order: 2;
            }

            .footer-copyright {
                order: 1;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 16px;
            }

            .logo-text h1 {
                font-size: 1rem;
            }

            .logo-text p {
                font-size: 0.75rem;
            }

            .logo-icon {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
            }

            .cta-button {
                padding: 16px 32px;
                font-size: 1rem;
            }

            .registration-form {
                padding: 24px 16px;
            }

            .category-card {
                padding: 32px 24px;
            }

            .newsletter-form {
                flex-direction: column;
                gap: 12px;
            }

            .newsletter-btn {
                justify-content: center;
            }

            .footer-nav {
                gap: 16px;
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        /* Scroll Animations */
        @media (prefers-reduced-motion: no-preference) {
            .category-card,
            .prize-item,
            .early-bird,
            .registration-form {
                opacity: 0;
                transform: translateY(32px);
                animation: fadeInUp 0.8s ease-out forwards;
            }

            .category-card:nth-child(1) { animation-delay: 0.1s; }
            .category-card:nth-child(2) { animation-delay: 0.2s; }
            .category-card:nth-child(3) { animation-delay: 0.3s; }

            .prize-item:nth-child(1) { animation-delay: 0.1s; }
            .prize-item:nth-child(2) { animation-delay: 0.2s; }
            .prize-item:nth-child(3) { animation-delay: 0.3s; }

            @keyframes fadeInUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }
        

        .registration-supported {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .supported-text {
            font-size: 14px;
            color: #ffffff;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .registration-supported-logo {
            height: 80px;
            width: auto;
            opacity: 0.9;
            transition: opacity 0.3s ease;
        }

        .registration-supported-logo:hover {
            opacity: 1;
        }

        .early-bird-note {
            text-align: center;
            margin-top: 30px;
        }

        .early-bird-note p {
            font-size: 14px;
            color: #fff;
            font-style: italic;
            margin: 0;
        }
    </style>
