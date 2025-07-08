@extends('layouts.user')

@section('title', 'WhatsApp Verification')

@section('header')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">
        <i class="fab fa-whatsapp mr-3"></i>
        WhatsApp Verification
    </h1>
</div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="text-center">
                @if($user->whatsapp_verified)
                    <div class="mb-6">
                        <i class="fas fa-check-circle text-green-500 text-6xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-green-600 mb-4">WhatsApp Sudah Terverifikasi!</h2>
                    <p class="text-gray-600 mb-6">
                        Akun WhatsApp Anda sudah berhasil diverifikasi{{ $user->whatsapp_verified_at ? ' pada ' . $user->whatsapp_verified_at->format('d M Y H:i') : '' }}
                    </p>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                    </a>
                @else
                    <div class="mb-6">
                        <i class="fab fa-whatsapp text-green-500 text-6xl"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Verifikasi Akun WhatsApp Anda</h2>
                    <p class="text-gray-600 mb-6">
                        Untuk melanjutkan, silakan verifikasi akun WhatsApp Anda dengan mengklik tombol di bawah ini.
                        Anda akan diarahkan ke WhatsApp dengan pesan verifikasi yang sudah disiapkan.
                    </p>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Langkah-langkah verifikasi:</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ol class="list-decimal list-inside space-y-1">
                                        <li>Klik tombol "Verifikasi via WhatsApp"</li>
                                        <li>Anda akan diarahkan ke aplikasi WhatsApp</li>
                                        <li>Kirim pesan verifikasi yang sudah disiapkan</li>
                                        <li>Tunggu konfirmasi dari admin</li>
                                        <li>Refresh halaman ini untuk melihat status terbaru</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <a href="{{ $whatsappUrl }}" target="_blank" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fab fa-whatsapp mr-2"></i>Verifikasi via WhatsApp
                        </a>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>User ID:</strong> {{ $user->id }}</p>
                            <p><strong>WhatsApp:</strong> {{ $user->phone }}</p>
                            <p><strong>Status:</strong> <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu Verifikasi</span></p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button onclick="checkVerificationStatus()" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-sync-alt mr-2"></i>Cek Status
                            </button>
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
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
