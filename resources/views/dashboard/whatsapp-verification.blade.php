@extends('layouts.app')

@section('title', 'WhatsApp Verification')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fab fa-whatsapp me-2"></i>
        WhatsApp Verification
    </h1>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fab fa-whatsapp me-2"></i>Verifikasi WhatsApp
                </h5>
            </div>
            <div class="card-body text-center">
                @if($user->whatsapp_verified)
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-success mb-3">WhatsApp Sudah Terverifikasi!</h4>
                    <p class="mb-3">
                        Akun WhatsApp Anda sudah berhasil diverifikasi{{ $user->whatsapp_verified_at ? ' pada ' . $user->whatsapp_verified_at->format('d M Y H:i') : '' }}
                    </p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                    </a>
                @else
                    <div class="mb-4">
                        <i class="fab fa-whatsapp text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h4 class="mb-3">Verifikasi Akun WhatsApp Anda</h4>
                    <p class="mb-4">
                        Untuk melanjutkan, silakan verifikasi akun WhatsApp Anda dengan mengklik tombol di bawah ini.
                        Anda akan diarahkan ke WhatsApp dengan pesan verifikasi yang sudah disiapkan.
                    </p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Langkah-langkah verifikasi:</strong><br>
                        1. Klik tombol "Verifikasi via WhatsApp"<br>
                        2. Anda akan diarahkan ke aplikasi WhatsApp<br>
                        3. Kirim pesan verifikasi yang sudah disiapkan<br>
                        4. Tunggu konfirmasi dari admin<br>
                        5. Refresh halaman ini untuk melihat status terbaru
                    </div>
                    
                    <div class="mb-4">
                        <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-success btn-lg">
                            <i class="fab fa-whatsapp me-2"></i>Verifikasi via WhatsApp
                        </a>
                    </div>
                    
                    <div class="text-muted">
                        <p class="mb-1"><strong>User ID:</strong> {{ $user->id }}</p>
                        <p class="mb-1"><strong>WhatsApp:</strong> {{ $user->phone }}</p>
                        <p class="mb-0"><strong>Status:</strong> <span class="badge bg-warning">Menunggu Verifikasi</span></p>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-outline-primary" onclick="checkVerificationStatus()">
                                <i class="fas fa-sync-alt me-2"></i>Cek Status
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(!$user->whatsapp_verified)
@push('scripts')
<script>
function checkVerificationStatus() {
    fetch('/api/check-verification/{{ $user->id }}')
        .then(response => response.json())
        .then(data => {
            if(data.verified) {
                location.reload();
            } else {
                alert('Verifikasi belum selesai. Silakan tunggu beberapa saat lagi.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengecek status verifikasi.');
        });
}

// Auto check every 30 seconds
setInterval(function() {
    fetch('/api/check-verification/{{ $user->id }}')
        .then(response => response.json())
        .then(data => {
            if(data.verified) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
}, 30000);
</script>
@endpush
@endif
@endsection
