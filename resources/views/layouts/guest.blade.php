<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-1V4ZXC9BP5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-1V4ZXC9BP5');
</script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                    },
                    colors: {
                        'custom-red': '#ED3D26',
                        'custom-green': '#273F0B',
                        'custom-dark': '#161616',
                    }
                }
            }
        }
    </script>

    @stack('styles')
    
    <style>
        @keyframes gradient-x {
            0%, 100% {
                background-size: 200% 200%;
                background-position: left center;
            }
            50% {
                background-size: 200% 200%;
                background-position: right center;
            }
        }
        
        .animate-gradient-x {
            animation: gradient-x 15s ease infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Glass effect for cards */
        .glass-effect {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Gradient header with custom colors */
        .custom-gradient-header {
            background: linear-gradient(135deg, #ED3D26 0%, #273F0B 50%, #161616 100%);
        }
        
        /* Keep original alert colors */
        .alert-success {
            background-color: #d4edda !important;
            border-color: #c3e6cb !important;
            color: #155724 !important;
        }
        
        .alert-danger {
            background-color: #f8d7da !important;
            border-color: #f5c6cb !important;
            color: #721c24 !important;
        }
        
        .alert-warning {
            background-color: #fff3cd !important;
            border-color: #ffeaa7 !important;
            color: #856404 !important;
        }
        
        .alert-info {
            background-color: #d1ecf1 !important;
            border-color: #bee5eb !important;
            color: #0c5460 !important;
        }
        
        /* Custom button styles with theme colors */
        .btn-custom-primary {
            background: linear-gradient(135deg, #ED3D26 0%, #273F0B 100%);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-custom-primary:hover {
            background: linear-gradient(135deg, #c73321 0%, #1f2f08 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(237, 61, 38, 0.3);
        }
        
        .btn-custom-secondary {
            background: linear-gradient(135deg, #273F0B 0%, #161616 100%);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-custom-secondary:hover {
            background: linear-gradient(135deg, #1f2f08 0%, #0a0a0a 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(39, 63, 11, 0.3);
        }
        
        /* Form stability - prevent movement */
        .form-container {
            position: relative;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Disable animations on form elements that cause movement */
        .form-card {
            animation: none !important;
            transform: none !important;
        }
        
        /* Override any bouncing/floating animations on forms */
        .form-card * {
            animation-duration: 0s !important;
        }
    </style>
</head>
<body class="min-h-screen font-sans antialiased relative">
    <!-- Animated Gradient Background -->
    <div class="fixed inset-0 bg-gradient-to-br from-custom-red via-custom-dark to-custom-green animate-gradient-x -z-10"></div>
    
    <!-- Overlay Pattern for depth -->
    <div class="fixed inset-0 opacity-10 -z-5">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent transform -skew-y-12"></div>
        <div class="absolute inset-0 bg-gradient-to-l from-transparent via-white to-transparent transform skew-y-12"></div>
    </div>
    
    <!-- Floating Particles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-5">
        <div class="absolute -top-4 -right-4 w-72 h-72 bg-custom-red rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-bounce"></div>
        <div class="absolute -bottom-8 -left-4 w-72 h-72 bg-custom-green rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-bounce" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-white rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
    </div>
    
    <div class="relative min-h-screen flex items-center justify-center">
        <div class="w-full mx-auto relative z-10">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
