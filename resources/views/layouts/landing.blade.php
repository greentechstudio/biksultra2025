<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>Amazing Sultra Run 2025 - Event Lari Terbesar di Sulawesi Tenggara | Kendari</title>
    <meta name="description" content="Amazing Sultra Run 2025 - Event lari jalan raya yang menawarkan pengalaman berbeda dari event lari yang pernah digelar di Sulawesi Tenggara. Daftar sekarang di Kendari!">
    <meta name="keywords" content="Amazing Sultra Run, event lari Sulawesi Tenggara, lari Kendari, marathon Sultra, fun run Kendari, olahraga Sulawesi Tenggara, event lari 2025">
    <meta name="author" content="Dinas Pariwisata Provinsi Sulawesi Tenggara">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Amazing Sultra Run 2025 - Event Lari Terbesar di Sulawesi Tenggara">
    <meta property="og:description" content="Event lari jalan raya yang menawarkan pengalaman berbeda dari event lari yang pernah digelar di Sulawesi Tenggara. Bergabunglah dengan ribuan pelari!">
    <meta property="og:image" content="{{ asset('images/amazing-sultra-run-banner.jpg') }}">
    <meta property="og:site_name" content="Amazing Sultra Run 2025">
    <meta property="og:locale" content="id_ID">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Amazing Sultra Run 2025 - Event Lari Terbesar di Sulawesi Tenggara">
    <meta name="twitter:description" content="Event lari jalan raya yang menawarkan pengalaman berbeda dari event lari yang pernah digelar di Sulawesi Tenggara.">
    <meta name="twitter:image" content="{{ asset('images/amazing-sultra-run-banner.jpg') }}">
    
    <!-- Additional SEO -->
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="geo.region" content="ID-ST">
    <meta name="geo.placename" content="Kendari, Sulawesi Tenggara">
    <meta name="geo.position" content="-3.9778;122.5149">
    <meta name="ICBM" content="-3.9778, 122.5149">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Event",
        "name": "Amazing Sultra Run 2025",
        "description": "Event lari jalan raya yang menawarkan pengalaman berbeda dari event lari yang pernah digelar di Sulawesi Tenggara",
        "startDate": "2025-12-15",
        "endDate": "2025-12-15",
        "location": {
            "@type": "Place",
            "name": "Kendari",
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "Kendari",
                "addressRegion": "Sulawesi Tenggara",
                "addressCountry": "ID"
            }
        },
        "organizer": {
            "@type": "Organization",
            "name": "Dinas Pariwisata Provinsi Sulawesi Tenggara"
        },
        "offers": {
            "@type": "Offer",
            "url": "{{ url()->current() }}",
            "price": "0",
            "priceCurrency": "IDR",
            "availability": "https://schema.org/InStock"
        }
    }
    </script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    @include('partials.landing-styles')
</head>
<body>
    <!-- Background Elements -->
    @include('partials.landing-background')
    
    <!-- Header -->
    @include('partials.landing-header')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('partials.landing-footer')
    
    <!-- Scripts -->
    @include('partials.landing-scripts')
</body>
</html>
