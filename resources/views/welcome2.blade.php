<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                font-family: 'Figtree', sans-serif;
            }
            .hero-section {
                min-height: 100vh;
                display: flex;
                align-items: center;
                color: white;
            }
            .hero-content {
                text-align: center;
            }
            .hero-title {
                font-size: 3.5rem;
                font-weight: 600;
                margin-bottom: 1.5rem;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            }
            .hero-subtitle {
                font-size: 1.25rem;
                margin-bottom: 2rem;
                opacity: 0.9;
            }
            .feature-card {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                padding: 2rem;
                margin-bottom: 2rem;
                text-align: center;
                color: white;
                transition: transform 0.3s ease;
            }
            .feature-card:hover {
                transform: translateY(-10px);
            }
            .feature-icon {
                font-size: 3rem;
                margin-bottom: 1rem;
                color: #ffd700;
            }
            .btn-custom {
                background: rgba(255, 255, 255, 0.2);
                border: 2px solid rgba(255, 255, 255, 0.3);
                color: white;
                padding: 12px 30px;
                border-radius: 50px;
                font-weight: 600;
                text-decoration: none;
                margin: 0 10px;
                transition: all 0.3s ease;
            }
            .btn-custom:hover {
                background: white;
                color: #667eea;
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <div class="hero-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="hero-content">
                            <h1 class="hero-title">
                                <i class="fas fa-tachometer-alt me-3"></i>
                                {{ config('app.name') }}
                            </h1>
                            <p class="hero-subtitle">
                                Sistem Dashboard Terintegrasi dengan WhatsApp untuk Registrasi dan Konfirmasi Pembayaran
                            </p>
                            
                            <div class="mb-5">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn-custom">
                                        <i class="fas fa-home me-2"></i>
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn-custom">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Login
                                    </a>
                                    <a href="{{ route('register') }}" class="btn-custom">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Register
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h4>Registrasi Mudah</h4>
                            <p>Daftar dengan mudah dan dapatkan konfirmasi langsung melalui WhatsApp</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <h4>Verifikasi WhatsApp</h4>
                            <p>Sistem terintegrasi dengan WhatsApp untuk verifikasi akun yang aman</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h4>Konfirmasi Pembayaran</h4>
                            <p>Konfirmasi pembayaran langsung melalui WhatsApp dengan mudah dan cepat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
