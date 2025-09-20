@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<style>
:root {
    /* Color Variables */
    --primary-color: #2563eb;
    --primary-hover: #1d4ed8;
    --background: #ffffff;
    --surface: #f8fafc;
    --border: #e2e8f0;
    --border-focus: #cbd5e1;
    --text-dark: #1e293b;
    --text-medium: #64748b;
    --text-light: #94a3b8;
    --error: #ef4444;
    --success: #10b981;
    
    /* Spacing */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}

/* Main Container */
.login-container {
    min-height: 100vh;
    display: flex;
    background: var(--surface);
}

/* Hero Section (Left Side) */
.hero-section {
    flex: 1;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    color: white;
    overflow: hidden;
}

.hero-content {
    text-align: center;
    max-width: 500px;
    z-index: 2;
    position: relative;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
    line-height: 1.1;
}

.hero-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    font-weight: 400;
    line-height: 1.6;
}

.hero-features {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 2rem;
}

.hero-feature {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-sm);
    backdrop-filter: blur(10px);
}

.hero-feature-icon {
    width: 2rem;
    height: 2rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Background Pattern */
.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    z-index: 1;
}

/* Form Section (Right Side) */
.form-section-container {
    flex: 1;
    background: var(--background);
    overflow-y: auto;
    max-height: 100vh;
    display: flex;
    align-items: stretch;
}

/* Form Container */
.form-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 3rem 2.5rem;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Form Header - Top Section */
.form-header {
    text-align: center;
    margin-bottom: 3rem;
    padding-bottom: 2.5rem;
    border-bottom: 2px solid var(--border);
    flex-shrink: 0;
}

.form-header h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    color: var(--text-dark);
    letter-spacing: -0.025em;
    line-height: 1.2;
}

.form-header p {
    font-size: 1.125rem;
    color: var(--text-medium);
    font-weight: 400;
    line-height: 1.5;
}

/* Form Content */
.login-form {
    background: var(--background);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    border: 2px solid var(--border);
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
}

.login-form:hover {
    box-shadow: var(--shadow-lg);
    border-color: var(--border-focus);
}

/* Form Groups */
.form-group {
    margin-bottom: 2rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.75rem;
    letter-spacing: 0.025em;
}

.form-input {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid var(--border);
    border-radius: var(--radius-md);
    font-size: 1rem;
    transition: all 0.2s ease;
    background: var(--background);
    color: var(--text-dark);
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    transform: translateY(-1px);
}

.form-input::placeholder {
    color: var(--text-light);
}

.form-error {
    color: var(--error);
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Checkbox */
.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 2rem;
}

.checkbox-input {
    width: 1.125rem;
    height: 1.125rem;
    border: 2px solid var(--border);
    border-radius: var(--radius-sm);
    accent-color: var(--primary-color);
}

.checkbox-label {
    font-size: 0.9rem;
    color: var(--text-medium);
}

/* Submit Button */
.submit-btn {
    width: 100%;
    padding: 1.25rem 2.5rem;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: 1.125rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    margin-bottom: 2rem;
}

.submit-btn:hover {
    background: linear-gradient(135deg, var(--primary-hover), var(--primary-color));
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.submit-btn:active {
    transform: translateY(0);
}

/* Links Section */
.links-section {
    text-align: center;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}

.link-item {
    margin-bottom: 1rem;
}

.link-item:last-child {
    margin-bottom: 0;
}

.primary-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
}

.primary-link:hover {
    color: var(--primary-hover);
    text-decoration: underline;
}

.secondary-text {
    color: var(--text-medium);
    font-size: 0.95rem;
}

/* WhatsApp Link */
.whatsapp-link {
    color: #10b981;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
}

.whatsapp-link:hover {
    color: #059669;
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 767px) {
    .login-container {
        flex-direction: column;
        min-height: auto;
    }
    
    .hero-section {
        min-height: 40vh;
        padding: 2rem 1.5rem;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .hero-features {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .hero-feature {
        min-width: auto;
        max-width: none;
    }
    
    .form-section-container {
        max-height: none;
        overflow-y: visible;
    }
    
    .form-container {
        padding: 2rem 1.5rem;
        max-width: 100%;
        min-height: auto;
        justify-content: flex-start;
    }
    
    .form-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
    }
    
    .form-header h1 {
        font-size: 1.875rem;
    }
    
    .login-form {
        padding: 2rem;
    }
}

/* Medium screens */
@media (min-width: 768px) and (max-width: 1024px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .form-container {
        padding: 2.5rem 2rem;
        max-width: 450px;
    }
}

/* Large screens */
@media (min-width: 1200px) {
    .hero-title {
        font-size: 3.5rem;
    }
    
    .form-container {
        max-width: 600px;
        padding: 4rem 3rem;
    }
    
    .login-form {
        padding: 3rem;
    }
}
</style>

<div class="login-container">
    <!-- Hero Section (Left Side) -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-title">
                Amazing Sultra Run
            </div>
            <div class="hero-subtitle">
                Selamat datang kembali! Masuk ke akun Anda untuk melihat informasi detail tentang akun anda.
            </div>
            
            <div class="hero-features">
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <div class="font-semibold">Quick Access</div>
                        <div class="text-sm opacity-90">Login cepat dan aman</div>
                    </div>
                </div>
                
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div>
                        <div class="font-semibold">Mobile Friendly</div>
                        <div class="text-sm opacity-90">Akses dari mana saja</div>
                    </div>
                </div>
                
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <div class="font-semibold">WhatsApp Support</div>
                        <div class="text-sm opacity-90">Reset password mudah</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form Section (Right Side) -->
    <div class="form-section-container">
        <div class="form-container">
            <!-- Form Header -->
            <div class="form-header">
                <h1>Masuk Akun</h1>
                <p>Masuk untuk mengakses dashboard dan fitur event lari</p>
            </div>

            <!-- Login Form -->
            <div class="login-form">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope mr-2"></i>Email *
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}" 
                               class="form-input @error('email') border-red-500 @enderror" 
                               placeholder="Masukkan email Anda"
                               required>
                        @error('email')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock mr-2"></i>Password *
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-input @error('password') border-red-500 @enderror" 
                               placeholder="Masukkan password Anda"
                               required>
                        @error('password')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="checkbox-group">
                        <input type="checkbox" 
                               name="remember" 
                               id="remember" 
                               class="checkbox-input">
                        <label for="remember" class="checkbox-label">
                            Ingat saya
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Masuk
                    </button>
                    
                    <!-- Links Section -->
                    <div class="links-section">
                        <div class="link-item">
                            <a href="{{ route('password.reset') }}" class="whatsapp-link">
                                <i class="fab fa-whatsapp mr-1"></i>
                                Lupa Password? Reset via WhatsApp
                            </a>
                        </div>
                        <div class="link-item">
                            <span class="secondary-text">Belum punya akun?</span>
                            <a href="{{ route('register') }}" class="primary-link">Daftar di sini</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
