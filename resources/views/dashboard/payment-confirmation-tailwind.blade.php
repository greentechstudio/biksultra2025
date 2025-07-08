@extends('layouts.user')

@section('title', 'Payment Confirmation')

@section('header')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">
        <i class="fas fa-credit-card mr-3"></i>
        Payment Confirmation
    </h1>
</div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="text-center">
                @if($user->payment_confirmed)
                    <div class="mb-6">
                        <i class="fas fa-check-circle text-green-500 text-6xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-green-600 mb-4">Pembayaran Sudah Terkonfirmasi!</h2>
                    <p class="text-gray-600 mb-6">
                        Pembayaran Anda sudah berhasil dikonfirmasi{{ $user->payment_confirmed_at ? ' pada ' . $user->payment_confirmed_at->format('d M Y H:i') : '' }}
                    </p>
                    <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                        <h3 class="text-sm font-medium text-green-800 mb-2">Detail Pembayaran:</h3>
                        <div class="text-sm text-green-700 space-y-1">
                            <p>Jumlah: <strong>Rp {{ number_format($user->payment_amount, 0, ',', '.') }}</strong></p>
                            <p>Metode: <strong>{{ ucfirst($user->payment_method) }}</strong></p>
                            @if($user->payment_notes)
                                <p>Catatan: <strong>{{ $user->payment_notes }}</strong></p>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                    </a>
                @else
                    @if(!$user->whatsapp_verified)
                        <div class="mb-6">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-6xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-yellow-600 mb-4">WhatsApp Belum Terverifikasi</h2>
                        <p class="text-gray-600 mb-6">
                            Anda harus memverifikasi WhatsApp terlebih dahulu sebelum melakukan konfirmasi pembayaran.
                        </p>
                        <a href="{{ route('dashboard.whatsapp') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fab fa-whatsapp mr-2"></i>Verifikasi WhatsApp Dulu
                        </a>
                    @else
                        <div class="mb-6">
                            <i class="fas fa-money-bill-wave text-blue-500 text-6xl"></i>
                        </div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Konfirmasi Pembayaran Anda</h2>
                        <p class="text-gray-600 mb-6">
                            Silakan konfirmasi pembayaran Anda dengan mengklik tombol di bawah ini.
                            Anda akan diarahkan ke WhatsApp untuk mengirim bukti pembayaran.
                        </p>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Informasi Pembayaran:</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Jumlah yang harus dibayar: <strong>Rp 100.000</strong></li>
                                            <li>Metode: Transfer Bank / E-Wallet</li>
                                            <li>Kirim bukti transfer via WhatsApp</li>
                                            <li>Tunggu konfirmasi dari admin</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Langkah-langkah konfirmasi:</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ol class="list-decimal list-inside space-y-1">
                                            <li>Lakukan pembayaran terlebih dahulu</li>
                                            <li>Klik tombol "Konfirmasi via WhatsApp"</li>
                                            <li>Kirim bukti pembayaran via WhatsApp</li>
                                            <li>Tunggu verifikasi dari admin</li>
                                            <li>Akun akan diaktifkan setelah pembayaran dikonfirmasi</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <a href="{{ $whatsappUrl }}" target="_blank" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fab fa-whatsapp mr-2"></i>Konfirmasi via WhatsApp
                            </a>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><strong>User ID:</strong> {{ $user->id }}</p>
                                <p><strong>WhatsApp:</strong> {{ $user->phone }}</p>
                                <p><strong>Status:</strong> <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu Pembayaran</span></p>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button onclick="checkPaymentStatus()" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-sync-alt mr-2"></i>Cek Status Pembayaran
                                </button>
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
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
