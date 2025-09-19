@extends('layouts.guest')

@section('title', 'Reset Password')

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
    --warning: #f59e0b;
    --info: #3b82f6;
    
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

/* Spinner Animation */
.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Main Container */
.reset-container {
    min-height: 100vh;
    display: flex;
    background: var(--surface);
}

/* Hero Section (Left Side) */
.hero-section {
    flex: 1;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
    max-width: 600px;
    margin: 0 auto;
    padding: 3rem 2.5rem;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Form Header */
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

/* Reset Form */
.reset-form {
    background: var(--background);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    border: 2px solid var(--border);
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
}

.reset-form:hover {
    box-shadow: var(--shadow-lg);
    border-color: var(--border-focus);
}

/* Alert Messages */
.alert {
    padding: 1rem 1.25rem;
    border-radius: var(--radius-md);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.alert-success {
    background: #ecfdf5;
    border: 1px solid #10b981;
    color: #065f46;
}

.alert-error {
    background: #fef2f2;
    border: 1px solid #ef4444;
    color: #991b1b;
}

.alert-info {
    background: #eff6ff;
    border: 1px solid #3b82f6;
    color: #1e40af;
}

.alert-icon {
    font-size: 1.125rem;
    margin-top: 0.125rem;
    flex-shrink: 0;
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
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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

/* Input Group */
.input-group {
    display: flex;
    gap: 0.75rem;
    align-items: stretch;
}

.input-group .form-input {
    flex: 1;
}

/* Buttons */
.btn-secondary {
    padding: 1rem 1.5rem;
    border: 2px solid var(--border);
    border-radius: var(--radius-md);
    background: var(--background);
    color: var(--text-medium);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-secondary:hover {
    background: var(--surface);
    border-color: var(--border-focus);
    color: var(--text-dark);
}

.btn-secondary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.submit-btn {
    width: 100%;
    padding: 1.25rem 2.5rem;
    background: linear-gradient(135deg, #10b981, #059669);
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
    background: linear-gradient(135deg, #059669, #047857);
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.submit-btn:active {
    transform: translateY(0);
}

.submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Info Card */
.info-card {
    background: #eff6ff;
    border: 1px solid #3b82f6;
    border-radius: var(--radius-md);
    padding: 1rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

.info-card-icon {
    font-size: 1.5rem;
    color: #3b82f6;
    margin-bottom: 0.5rem;
}

.info-card-text {
    color: #1e40af;
    font-size: 0.875rem;
    line-height: 1.5;
}

/* User Info Card */
.user-info-card {
    background: #eff6ff;
    border: 1px solid #3b82f6;
    border-radius: var(--radius-md);
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: none;
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

.secondary-text {
    color: var(--text-medium);
    font-size: 0.95rem;
}

/* Helper Text */
.helper-text {
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: var(--text-medium);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Footer Info */
.footer-info {
    background: var(--surface);
    border-top: 1px solid var(--border);
    padding: 1.5rem;
    margin-top: 2rem;
}

.footer-info-content {
    background: #eff6ff;
    border-radius: var(--radius-md);
    padding: 1rem;
}

.footer-info-title {
    font-weight: 600;
    color: #1e40af;
    margin-bottom: 0.5rem;
}

.footer-info-list {
    list-style: none;
    padding: 0;
    margin: 0;
    color: #1e40af;
    font-size: 0.875rem;
    line-height: 1.5;
}

.footer-info-list li {
    margin-bottom: 0.25rem;
}

/* Responsive Design */
@media (max-width: 767px) {
    .reset-container {
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
    
    .reset-form {
        padding: 2rem;
    }
    
    .input-group {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-secondary {
        width: 100%;
    }
}

/* Medium screens */
@media (min-width: 768px) and (max-width: 1024px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .form-container {
        padding: 2.5rem 2rem;
        max-width: 550px;
    }
}

/* Large screens */
@media (min-width: 1200px) {
    .hero-title {
        font-size: 3.5rem;
    }
    
    .form-container {
        max-width: 700px;
        padding: 4rem 3rem;
    }
    
    .reset-form {
        padding: 3rem;
    }
}
</style>

<div class="reset-container">
    <!-- Hero Section (Left Side) -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-title">
                Reset Password
            </div>
            <div class="hero-subtitle">
                Lupa password? Tidak masalah! Kami akan membantu Anda reset password melalui WhatsApp
            </div>
            
            <div class="hero-features">
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <div class="font-semibold">WhatsApp Instant</div>
                        <div class="text-sm opacity-90">Link reset dikirim langsung ke WA</div>
                    </div>
                </div>
                
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <div class="font-semibold">Secure Reset</div>
                        <div class="text-sm opacity-90">Proses aman dan terpercaya</div>
                    </div>
                </div>
                
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="font-semibold">Quick Access</div>
                        <div class="text-sm opacity-90">Reset dalam hitungan menit</div>
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
                <h1>Reset Password</h1>
                <p>Masukkan nomor WhatsApp untuk menerima link reset password</p>
            </div>

            <!-- Reset Form -->
            <div class="reset-form">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle alert-icon"></i>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle alert-icon"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle alert-icon"></i>
                        <div>
                            <p class="font-semibold">Terdapat kesalahan:</p>
                            <ul class="mt-2 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Info Card -->
                <div class="info-card">
                    <i class="fas fa-info-circle info-card-icon"></i>
                    <p class="info-card-text">
                        Masukkan nomor WhatsApp yang terdaftar untuk menerima link reset password.
                    </p>
                </div>
                
                <!-- Username Info Section -->
                <div class="user-info-card" id="usernameInfo">
                    <div class="flex items-start">
                        <i class="fas fa-user text-blue-500 mr-3 mt-1"></i>
                        <div>
                            <p class="text-blue-800 font-semibold">Username Anda: <span id="foundUsername" class="font-bold"></span></p>
                            <p class="text-blue-600 text-sm">Nama: <span id="foundName"></span></p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('password.reset.send') }}" id="resetForm">
                    @csrf

                    <!-- WhatsApp Number Input -->
                    <div class="form-group">
                        <label for="whatsapp_number" class="form-label">
                            <i class="fab fa-whatsapp mr-2"></i>Nomor WhatsApp <span style="color: var(--error);">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" 
                                   name="whatsapp_number" 
                                   id="whatsapp_number" 
                                   value="{{ old('whatsapp_number') }}" 
                                   class="form-input @error('whatsapp_number') border-red-500 @enderror" 
                                   placeholder="Contoh: 6281234567890 atau 081234567890"
                                   required>
                            <button class="btn-secondary" type="button" id="checkUsernameBtn">
                                <span id="checkUsernameText">
                                    <i class="fas fa-search mr-1"></i>Cek Username
                                </span>
                                <span id="checkUsernameSpinner" style="display: none;">
                                    <i class="fas fa-spinner spinner"></i>
                                </span>
                            </button>
                        </div>
                        <p class="helper-text">
                            <i class="fas fa-info-circle"></i>
                            Masukkan nomor WhatsApp yang sama dengan yang digunakan saat registrasi
                        </p>
                        @error('whatsapp_number')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <span id="submitText">
                            <i class="fab fa-whatsapp mr-2"></i>
                            Kirim Link Reset via WhatsApp
                        </span>
                        <span id="submitSpinner" style="display: none;">
                            <i class="fas fa-spinner spinner mr-2"></i>
                            Mengirim...
                        </span>
                    </button>

                    <!-- Links Section -->
                    <div class="links-section">
                        <div class="link-item">
                            <a href="{{ route('login') }}" class="primary-link">
                                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Login
                            </a>
                        </div>
                        <div class="link-item">
                            <span class="secondary-text">Belum punya akun?</span>
                            <a href="{{ route('register') }}" class="primary-link">Daftar di sini</a>
                        </div>
                    </div>
                </form>
                
                <!-- Footer Info -->
                <div class="footer-info">
                    <div class="footer-info-content">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mr-2 mt-1"></i>
                            <div>
                                <p class="footer-info-title">Catatan:</p>
                                <ul class="footer-info-list">
                                    <li>• Link reset password akan dikirim ke WhatsApp dalam beberapa menit</li>
                                    <li>• Link berlaku selama 1 jam setelah dikirim</li>
                                    <li>• Pastikan nomor WhatsApp yang dimasukkan aktif dan dapat menerima pesan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkUsernameBtn = document.getElementById('checkUsernameBtn');
    const whatsappNumberInput = document.getElementById('whatsapp_number');
    const usernameInfo = document.getElementById('usernameInfo');
    const foundUsername = document.getElementById('foundUsername');
    const foundName = document.getElementById('foundName');
    const checkUsernameText = document.getElementById('checkUsernameText');
    const checkUsernameSpinner = document.getElementById('checkUsernameSpinner');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    const resetForm = document.getElementById('resetForm');

    // Check username button click
    checkUsernameBtn.addEventListener('click', function() {
        const whatsappNumber = whatsappNumberInput.value.trim();
        
        if (!whatsappNumber) {
            alert('Silakan masukkan nomor WhatsApp terlebih dahulu');
            return;
        }

        // Show spinner
        checkUsernameText.style.display = 'none';
        checkUsernameSpinner.style.display = 'inline';
        checkUsernameBtn.disabled = true;

        // Make AJAX request
        fetch('{{ secure_url(route("password.reset.check", [], false)) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                whatsapp_number: whatsappNumber
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show user info
                foundUsername.textContent = data.username || data.user?.email || 'Unknown';
                foundName.textContent = data.name || data.user?.name || 'Unknown';
                usernameInfo.style.display = 'block';
            } else {
                // Hide user info and show error
                usernameInfo.style.display = 'none';
                alert(data.message || 'Nomor WhatsApp tidak ditemukan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengecek nomor WhatsApp');
        })
        .finally(() => {
            // Hide spinner
            checkUsernameText.style.display = 'inline';
            checkUsernameSpinner.style.display = 'none';
            checkUsernameBtn.disabled = false;
        });
    });

    // Form submission
    resetForm.addEventListener('submit', function(e) {
        // Show spinner
        submitText.style.display = 'none';
        submitSpinner.style.display = 'inline';
        submitBtn.disabled = true;
    });
});
</script>
@endsection
