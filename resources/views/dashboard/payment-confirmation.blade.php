@extends('layouts.app')

@section('title', 'Payment Confirmation')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-credit-card me-2"></i>
        Payment Confirmation
    </h1>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-credit-card me-2"></i>Konfirmasi Pembayaran
                </h5>
            </div>
            <div class="card-body text-center">
                @if($user->payment_confirmed)
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-success mb-3">Pembayaran Sudah Terkonfirmasi!</h4>
                    <p class="mb-3">
                        Pembayaran Anda sudah berhasil dikonfirmasi{{ $user->payment_confirmed_at ? ' pada ' . $user->payment_confirmed_at->format('d M Y H:i') : '' }}
                    </p>
                    <div class="alert alert-success">
                        <strong>Detail Pembayaran:</strong><br>
                        Jumlah: <strong>Rp {{ number_format($user->payment_amount, 0, ',', '.') }}</strong><br>
                        Metode: <strong>{{ ucfirst($user->payment_method) }}</strong><br>
                        @if($user->payment_notes)
                            Catatan: <strong>{{ $user->payment_notes }}</strong>
                        @endif
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                    </a>
                @else
                    @if(!$user->whatsapp_verified)
                        <div class="mb-4">
                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-warning mb-3">WhatsApp Belum Terverifikasi</h4>
                        <p class="mb-4">
                            Anda harus memverifikasi WhatsApp terlebih dahulu sebelum melakukan konfirmasi pembayaran.
                        </p>
                        <a href="{{ route('dashboard.whatsapp') }}" class="btn btn-success btn-lg">
                            <i class="fab fa-whatsapp me-2"></i>Verifikasi WhatsApp Dulu
                        </a>
                    @else
                        <div class="mb-4">
                            <i class="fas fa-money-bill-wave text-primary" style="font-size: 4rem;"></i>
                        </div>
                        
                        <h4 class="mb-3">Konfirmasi Pembayaran Anda</h4>
                        <p class="mb-4">
                            Silakan konfirmasi pembayaran Anda dengan mengklik tombol di bawah ini.
                            Anda akan diarahkan ke WhatsApp untuk mengirim bukti pembayaran.
                        </p>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi Pembayaran:</strong><br>
                            • Jumlah yang harus dibayar: <strong>Rp 100.000</strong><br>
                            • Metode: Transfer Bank / E-Wallet<br>
                            • Kirim bukti transfer via WhatsApp<br>
                            • Tunggu konfirmasi dari admin
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Langkah-langkah konfirmasi:</strong><br>
                            1. Lakukan pembayaran terlebih dahulu<br>
                            2. Klik tombol "Konfirmasi via WhatsApp"<br>
                            3. Kirim bukti pembayaran via WhatsApp<br>
                            4. Tunggu verifikasi dari admin<br>
                            5. Akun akan diaktifkan setelah pembayaran dikonfirmasi
                        </div>
                        
                        <div class="mb-4">
                            <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-primary btn-lg">
                                <i class="fab fa-whatsapp me-2"></i>Konfirmasi via WhatsApp
                            </a>
                        </div>
                        
                        <div class="text-muted">
                            <p class="mb-1"><strong>User ID:</strong> {{ $user->id }}</p>
                            <p class="mb-1"><strong>WhatsApp:</strong> {{ $user->phone }}</p>
                            <p class="mb-0"><strong>Status:</strong> <span class="badge bg-warning">Menunggu Pembayaran</span></p>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-outline-primary" onclick="checkPaymentStatus()">
                                    <i class="fas fa-sync-alt me-2"></i>Cek Status Pembayaran
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@if($user->whatsapp_verified && !$user->payment_confirmed)
@push('scripts')
<script>
function checkPaymentStatus() {
    fetch('/api/check-payment/{{ $user->id }}')
        .then(response => response.json())
        .then(data => {
            if(data.confirmed) {
                location.reload();
            } else {
                alert('Pembayaran belum dikonfirmasi. Silakan tunggu verifikasi dari admin.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengecek status pembayaran.');
        });
}

// Auto check every 30 seconds
setInterval(function() {
    fetch('/api/check-payment/{{ $user->id }}')
        .then(response => response.json())
        .then(data => {
            if(data.confirmed) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
}, 30000);
</script>
@endpush
@endif
@endsection
