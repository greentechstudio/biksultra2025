@extends('layouts.guest')

@section('title', 'Set Password Baru')

@section('content')
<div class="max-w-md mx-auto">
    <div class="glass-effect rounded-2xl overflow-hidden animate-float">
        <!-- Header -->
        <div class="custom-gradient-header p-6 text-center">
            <div class="mb-4">
                <i class="fas fa-key text-4xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Set Password Baru</h2>
            <p class="text-gray-200">Atur password baru untuk akun Anda</p>
        </div>
        
        <!-- Form -->
        <div class="p-6">
            @if ($errors->any())
                <div class="alert-danger rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
                        <div>
                            <strong>Terdapat kesalahan:</strong>
                            <ul class="list-disc list-inside mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-center mb-6">
                <i class="fas fa-shield-alt text-4xl text-green-600 mb-3"></i>
                <p class="text-gray-600">
                    Masukkan password baru untuk akun WhatsApp: <strong>{{ $whatsappNumber }}</strong>
                </p>
            </div>

            <form method="POST" action="{{ route('password.reset.update') }}" id="passwordForm" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="whatsapp_number" value="{{ $whatsappNumber }}">

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" 
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('password') border-red-500 @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Minimal 8 karakter"
                               required>
                        <button type="button" id="togglePassword" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                    <p class="mt-2 text-sm text-gray-500" id="passwordStrength">
                        <i class="fas fa-info-circle mr-1"></i>
                        Password harus minimal 8 karakter
                    </p>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" 
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('password_confirmation') border-red-500 @enderror" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Ulangi password baru"
                               required>
                        <button type="button" id="togglePasswordConfirm" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="passwordConfirmIcon"></i>
                        </button>
                    </div>
                    <p class="mt-2 text-sm text-gray-500" id="passwordMatch">
                        <i class="fas fa-info-circle mr-1"></i>
                        Ulangi password yang sama
                    </p>
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" 
                        id="submitBtn">
                    <i class="fas fa-save mr-2"></i>
                    <span id="submitText">Simpan Password Baru</span>
                    <i class="fas fa-spinner fa-spin ml-2 hidden" id="submitSpinner"></i>
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordIcon = document.getElementById('passwordIcon');
    const passwordConfirmIcon = document.getElementById('passwordConfirmIcon');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordMatch = document.getElementById('passwordMatch');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        passwordIcon.classList.toggle('fa-eye');
        passwordIcon.classList.toggle('fa-eye-slash');
    });
    
    togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmInput.setAttribute('type', type);
        passwordConfirmIcon.classList.toggle('fa-eye');
        passwordConfirmIcon.classList.toggle('fa-eye-slash');
    });
    
    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = checkPasswordStrength(password);
        
        passwordStrength.innerHTML = `<i class="fas fa-info-circle mr-1"></i>${strength.message}`;
        passwordStrength.className = `mt-2 text-sm ${strength.class}`;
    });
    
    // Password match checker
    passwordConfirmInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;
        
        if (confirmPassword === '') {
            passwordMatch.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Ulangi password yang sama';
            passwordMatch.className = 'mt-2 text-sm text-gray-500';
        } else if (password === confirmPassword) {
            passwordMatch.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Password cocok';
            passwordMatch.className = 'mt-2 text-sm text-green-600';
        } else {
            passwordMatch.innerHTML = '<i class="fas fa-times-circle mr-1"></i>Password tidak cocok';
            passwordMatch.className = 'mt-2 text-sm text-red-600';
        }
    });
    
    // Form submission
    document.getElementById('passwordForm').addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitText.textContent = 'Menyimpan...';
        submitSpinner.classList.remove('hidden');
    });
    
    function checkPasswordStrength(password) {
        if (password.length < 8) {
            return {
                message: 'Password harus minimal 8 karakter',
                class: 'text-red-600'
            };
        }
        
        let score = 0;
        if (password.length >= 8) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        
        if (score < 3) {
            return {
                message: 'Password lemah - tambahkan huruf besar, angka, atau simbol',
                class: 'text-yellow-600'
            };
        } else if (score < 4) {
            return {
                message: 'Password sedang',
                class: 'text-blue-600'
            };
        } else {
            return {
                message: 'Password kuat',
                class: 'text-green-600'
            };
        }
    }
});
</script>
@endpush
@endsection
