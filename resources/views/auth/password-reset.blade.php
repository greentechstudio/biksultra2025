@extends('layouts.guest')

@section('title', 'Reset Password')

@push('styles')
<style>
.spinner {
    animation: spin 1s linear infinite;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="glass-effect rounded-2xl overflow-hidden max-w-md mx-auto form-card">
        <!-- Header -->
        <div class="custom-gradient-header p-6 text-center">
            <div class="mb-4">
                <i class="fab fa-whatsapp text-4xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Reset Password</h2>
            <p class="text-gray-200">Reset password via WhatsApp</p>
        </div>
        
        <!-- Form -->
        <div class="p-6">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        <p class="text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                        <p class="text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-400 mr-3 mt-1"></i>
                    <div>
                        <p class="text-red-800 font-medium">Terdapat kesalahan:</p>
                        <ul class="mt-2 text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Info -->
        <div class="text-center mb-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <i class="fas fa-info-circle text-blue-500 text-2xl mb-2"></i>
                <p class="text-blue-700 text-sm">
                    Masukkan nomor WhatsApp yang terdaftar untuk menerima link reset password.
                </p>
            </div>
        </div>
        
        <!-- Username Info Section -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6" id="usernameInfo" style="display: none;">
            <div class="flex items-start">
                <i class="fas fa-user text-blue-500 mr-3 mt-1"></i>
                <div>
                    <p class="text-blue-800 font-medium">Username Anda: <span id="foundUsername" class="font-bold"></span></p>
                    <p class="text-blue-600 text-sm">Nama: <span id="foundName"></span></p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('password.reset.send') }}" id="resetForm" class="space-y-6">
            @csrf

            <!-- WhatsApp Number Input -->
            <div>
                <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fab fa-whatsapp mr-2 text-green-500"></i>Nomor WhatsApp <span class="text-red-500">*</span>
                </label>
                <div class="flex space-x-2">
                    <input type="text" 
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('whatsapp_number') border-red-500 ring-red-500 @enderror" 
                           id="whatsapp_number" 
                           name="whatsapp_number" 
                           value="{{ old('whatsapp_number') }}" 
                           placeholder="Contoh: 6281234567890 atau 081234567890"
                           required>
                    <button class="px-4 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors" type="button" id="checkUsernameBtn">
                        <span id="checkUsernameText">
                            <i class="fas fa-search mr-1"></i>Cek Username
                        </span>
                        <span id="checkUsernameSpinner" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>
                <p class="mt-2 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Masukkan nomor WhatsApp yang sama dengan yang digunakan saat registrasi
                </p>
                @error('whatsapp_number')
                    <p class="text-red-500 text-sm mt-2">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full btn-custom-secondary text-white font-semibold py-3 px-4 rounded-lg" 
                    id="submitBtn">
                <span id="submitText">
                    <i class="fab fa-whatsapp mr-2"></i>
                    Kirim Link Reset via WhatsApp
                </span>
                <span id="submitSpinner" style="display: none;">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Mengirim...
                </span>
            </button>

            <!-- Back to Login -->
            <div class="text-center pt-4 border-t border-gray-200">
                <p>
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali ke Login
                    </a>
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
                        Daftar di sini
                    </a>
                </p>
            </div>
        </form>
    </div>
    
    <!-- Footer Info -->
    <div class="bg-gray-50 p-4 border-t border-gray-200">
        <div class="bg-blue-50 rounded-lg p-3">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mr-2 mt-1"></i>
                <div class="text-sm text-blue-700">
                    <p class="font-medium mb-1">Catatan:</p>
                    <ul class="space-y-1">
                        <li>• Link reset password akan dikirim ke WhatsApp dalam beberapa menit</li>
                        <li>• Link berlaku selama 1 jam setelah dikirim</li>
                        <li>• Pastikan nomor WhatsApp yang dimasukkan aktif dan dapat menerima pesan</li>
                    </ul>
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
    console.log('DOM loaded'); // Debug
    
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
        console.log('Check username button clicked'); // Debug
        
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
            console.log('Response:', data); // Debug
            
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
        console.log('Form submitted'); // Debug
        
        // Show spinner
        submitText.style.display = 'none';
        submitSpinner.style.display = 'inline';
        submitBtn.disabled = true;
        
        // Allow form to submit naturally
        // The spinner will be hidden when page redirects or reloads
    });
});
</script>
    </div>
</div>
@endsection
