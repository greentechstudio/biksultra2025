@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="auth-form-container">
                <div class="card shadow">
                <div class="card-header bg-warning text-dark text-center">
                    <h4 class="card-title mb-0">
                        <i class="fab fa-whatsapp me-2"></i>Reset Password via WhatsApp
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

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
                        <i class="fab fa-whatsapp fa-3x text-success mb-3"></i>
                        <p class="text-muted">
                            Masukkan nomor WhatsApp yang terdaftar untuk menerima link reset password.
                        </p>
                    </div>

                    <!-- Username Info Section -->
                    <div class="alert alert-info d-none" id="usernameInfo" role="alert">
                        <i class="fas fa-user me-2"></i>
                        <strong>Username Anda:</strong> <span id="foundUsername" class="fw-bold"></span><br>
                        <small class="text-muted">Nama: <span id="foundName"></span></small>
                    </div>

                    <form method="POST" action="{{ route('password.reset.send') }}" id="resetForm">
                        @csrf

                        <div class="mb-3">
                            <label for="whatsapp_number" class="form-label">
                                <i class="fab fa-whatsapp me-1"></i>Nomor WhatsApp <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control @error('whatsapp_number') is-invalid @enderror" 
                                       id="whatsapp_number" 
                                       name="whatsapp_number" 
                                       value="{{ old('whatsapp_number') }}" 
                                       placeholder="Contoh: 6281234567890 atau 081234567890"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="checkUsernameBtn">
                                    <span id="checkUsernameText">
                                        <i class="fas fa-search me-1"></i>Cek Username
                                    </span>
                                    <span id="checkUsernameSpinner" class="spinner-border spinner-border-sm d-none"></span>
                                </button>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan nomor WhatsApp yang sama dengan yang digunakan saat registrasi
                            </small>
                            @error('whatsapp_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning" id="submitBtn">
                                <i class="fab fa-whatsapp me-2"></i>
                                <span id="submitText">Kirim Link Reset via WhatsApp</span>
                                <span id="submitSpinner" class="spinner-border spinner-border-sm ms-2 d-none"></span>
                            </button>
                        </div>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-2">
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Login
                                </a>
                            </p>
                            <p class="mb-0">
                                <small class="text-muted">
                                    Belum punya akun? 
                                    <a href="{{ route('register') }}" class="text-decoration-none">Daftar di sini</a>
                                </small>
                            </p>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light">
                    <div class="alert alert-info mb-0" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Link reset password akan dikirim ke WhatsApp dalam beberapa menit</li>
                            <li>Link berlaku selama 1 jam setelah dikirim</li>
                            <li>Pastikan nomor WhatsApp yang dimasukkan aktif dan dapat menerima pesan</li>
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
console.log('Script tag loaded!'); // Basic test

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing reset password form...'); // Debug log
    
    // Get elements
    const checkUsernameBtn = document.getElementById('checkUsernameBtn');
    const whatsappInput = document.getElementById('whatsapp_number');
    const usernameInfo = document.getElementById('usernameInfo');
    
    console.log('Elements check:', {
        checkUsernameBtn: !!checkUsernameBtn,
        whatsappInput: !!whatsappInput,
        usernameInfo: !!usernameInfo
    });

    if (!checkUsernameBtn) {
        console.error('Check username button not found!');
        return;
    }

    console.log('Adding click event listener...');
    
    // Simple click test first
    checkUsernameBtn.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent any default behavior
        console.log('🎯 BUTTON CLICKED!'); 
        
        const number = whatsappInput.value.trim();
        console.log('WhatsApp number:', number);
        
        if (!number || number.length < 10) {
            alert('Mohon masukkan nomor WhatsApp yang valid (minimal 10 digit)');
            return;
        }

        // Show loading state
        checkUsernameBtn.disabled = true;
        document.getElementById('checkUsernameText').innerHTML = '<i class="fas fa-search me-1"></i>Mencari...';
        document.getElementById('checkUsernameSpinner').classList.remove('d-none');
        usernameInfo.classList.add('d-none');

        console.log('Calling API to get real username data...');

        // Call the API endpoint (no CSRF token needed for API routes)
        fetch('/api/check-username-simple', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                whatsapp_number: number
            })
        })
        .then(response => {
            console.log('API Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('API Response data:', data);
            
            if (data.success) {
                // Show real data from API
                document.getElementById('foundUsername').textContent = data.username;
                document.getElementById('foundName').textContent = data.name;
                usernameInfo.innerHTML = '<i class="fas fa-user me-2"></i><strong>Username Anda:</strong> <span class="fw-bold">' + data.username + '</span><br><small class="text-muted">Nama: ' + data.name + '</small>';
                usernameInfo.classList.remove('d-none');
                usernameInfo.className = 'alert alert-success';
                console.log('✅ Username found:', data.username);
            } else {
                // Show error message
                usernameInfo.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i><strong>Tidak ditemukan:</strong> ' + data.message;
                usernameInfo.className = 'alert alert-warning';
                usernameInfo.classList.remove('d-none');
                console.log('❌ Username not found:', data.message);
            }
        })
        .catch(error => {
            console.error('API Error:', error);
            usernameInfo.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i><strong>Error:</strong> Terjadi kesalahan saat mencari username.';
            usernameInfo.className = 'alert alert-danger';
            usernameInfo.classList.remove('d-none');
        })
        .finally(() => {
            // Reset button state
            checkUsernameBtn.disabled = false;
            document.getElementById('checkUsernameText').innerHTML = '<i class="fas fa-search me-1"></i>Cek Username';
            document.getElementById('checkUsernameSpinner').classList.add('d-none');
            console.log('Button state reset');
        });
    });

    console.log('Event listener added successfully!');

    // Auto-format WhatsApp number
    whatsappInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        
        if (value.length > 0 && !value.startsWith('62')) {
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            } else if (value.startsWith('8')) {
                value = '62' + value;
            }
        }
        
        this.value = value;
        usernameInfo.classList.add('d-none');
    });

    // Form submission handler
    const form = document.getElementById('resetForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitText.textContent = 'Mengirim...';
            submitSpinner.classList.remove('d-none');
            
            setTimeout(function() {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitText.textContent = 'Kirim Link Reset via WhatsApp';
                    submitSpinner.classList.add('d-none');
                }
            }, 10000);
        });
    }

    console.log('✅ All event listeners added successfully!');
});

console.log('Script fully loaded!');
</script>
@endsection
