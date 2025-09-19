<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>{{ config('event.titles.main') }}</title>
<meta name="description" content="{{ config('event.seo.meta_description') }}">
<meta name="keywords" content="{{ config('event.seo.keywords') }}">
<meta name="author" content="{{ config('event.organizer.region') }}">
<meta name="robots" content="index, follow">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="event">
<meta property="og:title" content="{{ config('event.name') }} {{ config('event.year') }} | {{ config('event.full_name') }}">
<meta property="og:description" content="Event resmi {{ config('event.organizer.region') }} dengan lomba, talkshow, dan workshop literasi keuangan. Bergabunglah dalam acara edukatif terbesar di {{ config('event.location.province') }}!">
<meta property="og:url" content="{{ config('app.url') }}">
<meta property="og:site_name" content="{{ config('event.name') }} {{ config('event.year') }}">
<meta property="og:locale" content="id_ID">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ config('event.name') }} {{ config('event.year') }} | {{ config('event.full_name') }}">
<meta name="twitter:description" content="Event resmi {{ config('event.organizer.region') }} dengan lomba, talkshow, dan workshop literasi keuangan. Daftarkan diri Anda sekarang!">

<!-- Additional SEO Meta Tags -->
<meta name="geo.region" content="ID-ST">
<meta name="geo.placename" content="{{ config('event.location.city') }}, {{ config('event.location.province') }}">
<meta name="geo.position" content="-3.9778;122.5148">
<meta name="ICBM" content="-3.9778, 122.5148">

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="shortcut icon" type="image/x-icon" href="{{ asset('evente-assets/img/logo/favicon.png') }}">
<!-- Place favicon.ico in the root directory -->

<!-- CSS here -->
<link rel="stylesheet" href="{{ asset('evente-assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('evente-assets/css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('evente-assets/css/magnific-popup.css') }}">
<link rel="stylesheet" href="{{ asset('evente-assets/css/fontawesome-all.min.css') }}">
<link rel="stylesheet" href="{{ asset('evente-assets/css/flaticon_mycollection.css') }}">
<link rel="stylesheet" href="{{ asset('evente-assets/css/swiper-bundle.min.css') }}">
<link rel="stylesheet" href="{{ asset('evente-assets/css/odometer.css') }}">
<link rel="stylesheet" href="{{ asset('evente-assets/css/default.css') }}">
<link rel="stylesheet" href="{{ asset('evente-assets/css/main.css') }}">

<!-- Custom Coming Soon Tab CSS -->
<style>
    .coming-soon-tab {
        background-color: #888 !important;
        color: #ddd !important;
        cursor: not-allowed !important;
        opacity: 0.6 !important;
        border: 1px solid #666 !important;
        pointer-events: none !important;
    }
    
    .coming-soon-tab:hover {
        background-color: #888 !important;
        color: #ddd !important;
        transform: none !important;
    }
    
    .coming-soon-tab .td-counts {
        color: #bbb !important;
    }
    
    .coming-soon-tab .d-block {
        color: #999 !important;
        font-size: 12px !important;
    }
    
    /* Glass Overlay Coming Soon Effect */
    .glass-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .glass-overlay:hover {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
    
    .coming-soon-content {
        text-align: center;
        color: #fff;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
    
    .coming-soon-content h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
        background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #feca57);
        background-size: 400% 400%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientShift 3s ease-in-out infinite;
    }
    
    .coming-soon-content p {
        font-size: 1.2rem;
        font-weight: 500;
        margin-bottom: 20px;
        color: #fff;
    }
    
    .coming-soon-badge {
        display: inline-block;
        padding: 10px 25px;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        animation: pulse 2s infinite;
    }
    
    .overlay-container {
        position: relative;
        overflow: hidden;
    }
    
    @keyframes gradientShift {
        0%, 100% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.5);
        }
    }
    
    /* Floating particles effect */
    .glass-overlay::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.3) 2px, transparent 2px),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.2) 1px, transparent 1px),
            radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
        background-size: 50px 50px, 30px 30px, 70px 70px;
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    /* Responsive glass overlay */
    @media (max-width: 768px) {
        .coming-soon-content h3 {
            font-size: 2rem;
        }
        
        .coming-soon-content p {
            font-size: 1rem;
        }
        
        .coming-soon-badge {
            font-size: 0.8rem;
            padding: 8px 20px;
        }
    }
</style>