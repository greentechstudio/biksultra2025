@extends('layouts.guest')

@section('title', 'Informasi Akun - Amazing Sultra Run 2025')

@section('content')
<style>
/* Additional styles for form */
.form-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.glass-effect {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
}

.custom-gradient-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}
</style>

<div class="form-container">
    <div class="w-full max-w-md mx-auto">
        <div class="glass-effect rounded-2xl overflow-hidden">
            <!-- Header -->
            <div class="custom-gradient-header text-white p-6 text-center">
                <div class="flex justify-center items-center space-x-4 mb-4">
                    <img src="{{ asset('images/logoprov.png') }}" alt="Logo Provinsi" class="h-12 w-auto object-contain">
                    <img src="{{ asset('images/pesonaindonesia.png') }}" alt="Pesona Indonesia" class="h-12 w-auto object-contain">
                </div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Akun
                </h1>
                <p class="text-gray-200">Dapatkan informasi akun Anda via WhatsApp</p>
            </div>
            
            <!-- Form Content -->
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Terjadi kesalahan:
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form id="accountInfoForm" method="POST" action="{{ route('account.info.send') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Info Card -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Cara Mendapatkan Informasi Akun
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Masukkan nomor WhatsApp yang Anda gunakan saat mendaftar. Informasi lengkap akun Anda akan dikirim melalui WhatsApp.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- WhatsApp Number Input -->
                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-whatsapp text-green-500"></i>
                            </div>
                            <input type="tel" 
                                   class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('whatsapp_number') border-red-500 @enderror" 
                                   id="whatsapp_number" 
                                   name="whatsapp_number" 
                                   value="{{ old('whatsapp_number') }}" 
                                   placeholder="Contoh: 081234567890"
                                   required>
                        </div>
                        @error('whatsapp_number')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Masukkan nomor yang sama dengan saat pendaftaran
                        </p>
                    </div>

                    <!-- reCAPTCHA v3 (invisible) -->
                    @error('recaptcha')
                        <div class="mb-4">
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        </div>
                    @enderror

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Informasi Akun
                        </button>
                        <p class="mt-4 text-sm text-gray-600">
                            Informasi akan dikirim ke WhatsApp Anda dalam beberapa menit
                        </p>
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Dilindungi oleh reCAPTCHA v3 - Verifikasi otomatis saat submit
                        </p>
                    </div>

                    <!-- Back to Login -->
                    <div class="text-center border-t border-gray-200 pt-6">
                        <p class="text-sm text-gray-600">
                            Sudah ingat informasi login Anda? 
                            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                Login di sini
                            </a>
                        </p>
                        <p class="text-sm text-gray-600 mt-2">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include reCAPTCHA v3 -->
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission with reCAPTCHA v3
    const form = document.getElementById('accountInfoForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Disable submit button
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        }

        // Execute reCAPTCHA v3
        if (typeof grecaptcha !== 'undefined') {
            console.log('Executing reCAPTCHA v3 for account info request...');
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config("services.recaptcha.site_key") }}', {action: 'account_info'}).then(function(token) {
                    console.log('reCAPTCHA v3 token received:', token.substring(0, 20) + '...');
                    
                    // Add reCAPTCHA token to form
                    let recaptchaInput = document.querySelector('input[name="g-recaptcha-response"]');
                    if (!recaptchaInput) {
                        recaptchaInput = document.createElement('input');
                        recaptchaInput.type = 'hidden';
                        recaptchaInput.name = 'g-recaptcha-response';
                        form.appendChild(recaptchaInput);
                    }
                    recaptchaInput.value = token;

                    // Submit the form
                    HTMLFormElement.prototype.submit.call(form);
                }).catch(function(error) {
                    console.error('reCAPTCHA error:', error);
                    alert('Gagal memverifikasi reCAPTCHA. Silakan coba lagi.');
                    
                    // Re-enable submit button
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Kirim Informasi Akun';
                    }
                });
            });
        } else {
            console.warn('reCAPTCHA not loaded, submitting form without verification');
            // If reCAPTCHA not loaded, submit anyway with warning
            HTMLFormElement.prototype.submit.call(form);
        }
    });

    // Auto-format phone number
    const phoneInput = document.getElementById('whatsapp_number');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, ''); // Remove non-digits
            
            // Auto-format Indonesian phone numbers
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            } else if (!value.startsWith('62')) {
                if (value.length > 0) {
                    value = '62' + value;
                }
            }
            
            this.value = value;
        });
    }
});
</script>
@endsection
