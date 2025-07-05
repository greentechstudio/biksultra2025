@extends('layouts.guest')

@section('title', 'Set Password Baru')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="auth-form-container">
                <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-key me-2"></i>Set Password Baru
                    </h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Terdapat kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                        <p class="text-muted">
                            Masukkan password baru untuk akun WhatsApp: <strong>{{ $whatsappNumber }}</strong>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.reset.update') }}" id="passwordForm">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="whatsapp_number" value="{{ $whatsappNumber }}">

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Minimal 8 karakter"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <small id="passwordStrength" class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Password harus minimal 8 karakter
                                </small>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-1"></i>Konfirmasi Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Ulangi password baru"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="fas fa-eye" id="passwordConfirmIcon"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <small id="passwordMatch" class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Ulangi password yang sama
                                </small>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                <span id="submitText">Simpan Password Baru</span>
                                <span id="submitSpinner" class="spinner-border spinner-border-sm ms-2 d-none"></span>
                            </button>
                        </div>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-0">
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Login
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light">
                    <div class="alert alert-warning mb-0" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Gunakan password yang kuat dan mudah diingat</li>
                            <li>Jangan gunakan password yang sama dengan akun lain</li>
                            <li>Simpan password di tempat yang aman</li>
                        </ul>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('passwordForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordMatch = document.getElementById('passwordMatch');
    
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordIcon = document.getElementById('passwordIcon');
    const passwordConfirmIcon = document.getElementById('passwordConfirmIcon');

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        passwordIcon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    });

    togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmInput.setAttribute('type', type);
        passwordConfirmIcon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    });

    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = checkPasswordStrength(password);
        
        passwordStrength.innerHTML = `<i class="fas ${strength.icon} me-1"></i>${strength.text}`;
        passwordStrength.className = `text-${strength.color}`;
        
        checkPasswordMatch();
    });

    // Password match checker
    passwordConfirmInput.addEventListener('input', function() {
        checkPasswordMatch();
    });

    function checkPasswordStrength(password) {
        if (password.length === 0) {
            return {
                text: 'Password harus minimal 8 karakter',
                color: 'muted',
                icon: 'fas fa-info-circle'
            };
        } else if (password.length < 8) {
            return {
                text: 'Password terlalu pendek (minimal 8 karakter)',
                color: 'danger',
                icon: 'fas fa-times-circle'
            };
        } else if (password.length >= 8 && password.length < 12) {
            return {
                text: 'Password cukup kuat',
                color: 'warning',
                icon: 'fas fa-check-circle'
            };
        } else {
            const hasLower = /[a-z]/.test(password);
            const hasUpper = /[A-Z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            const score = hasLower + hasUpper + hasNumber + hasSpecial;
            
            if (score >= 3) {
                return {
                    text: 'Password sangat kuat',
                    color: 'success',
                    icon: 'fas fa-shield-alt'
                };
            } else {
                return {
                    text: 'Password kuat',
                    color: 'success',
                    icon: 'fas fa-check-circle'
                };
            }
        }
    }

    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = passwordConfirmInput.value;
        
        if (confirmPassword.length === 0) {
            passwordMatch.innerHTML = '<i class="fas fa-info-circle me-1"></i>Ulangi password yang sama';
            passwordMatch.className = 'text-muted';
        } else if (password === confirmPassword) {
            passwordMatch.innerHTML = '<i class="fas fa-check-circle me-1"></i>Password cocok';
            passwordMatch.className = 'text-success';
        } else {
            passwordMatch.innerHTML = '<i class="fas fa-times-circle me-1"></i>Password tidak cocok';
            passwordMatch.className = 'text-danger';
        }
    }

    // Handle form submission
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirmPassword = passwordConfirmInput.value;
        
        if (password.length < 8) {
            e.preventDefault();
            alert('Password harus minimal 8 karakter');
            return;
        }
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok');
            return;
        }
        
        submitBtn.disabled = true;
        submitText.textContent = 'Menyimpan...';
        submitSpinner.classList.remove('d-none');
    });
});
</script>
@endsection
