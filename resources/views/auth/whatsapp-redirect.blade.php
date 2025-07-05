@extends('layouts.guest')

@section('title', 'Konfirmasi WhatsApp')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h3><i class="fab fa-whatsapp me-2"></i>Registrasi Berhasil!</h3>
        <p class="mb-0">Silakan konfirmasi melalui WhatsApp</p>
    </div>
    
    <div class="auth-body text-center">
        <div class="mb-4">
            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
        </div>
        
        <h5 class="mb-3">Halo {{ $user->name }}!</h5>
        <p class="mb-4">
            Registrasi Anda berhasil! Untuk melanjutkan, silakan konfirmasi akun Anda melalui WhatsApp 
            dengan mengklik tombol di bawah ini.
        </p>
        
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Langkah selanjutnya:</strong><br>
            1. Klik tombol "Konfirmasi via WhatsApp"<br>
            2. Kirim pesan konfirmasi yang sudah disiapkan<br>
            3. Tunggu verifikasi dari admin<br>
            4. Login kembali setelah akun diverifikasi
        </div>
        
        <div class="d-grid gap-2 mb-3">
            <a href="{{ $whatsappUrl }}" target="_blank" class="whatsapp-btn">
                <i class="fab fa-whatsapp me-2"></i>Konfirmasi via WhatsApp
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                <i class="fas fa-sign-in-alt me-2"></i>Kembali ke Login
            </a>
        </div>
        
        <div class="text-muted small">
            <p class="mb-1"><strong>User ID:</strong> {{ $user->id }}</p>
            <p class="mb-0"><strong>Status:</strong> <span class="badge bg-warning">Menunggu Verifikasi</span></p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto refresh page every 30 seconds to check verification status
    setTimeout(function(){
        fetch('/api/check-verification/' + {{ $user->id }})
            .then(response => response.json())
            .then(data => {
                if(data.verified) {
                    window.location.href = '{{ route("login") }}';
                }
            });
    }, 30000);
</script>
@endpush
@endsection
