@extends('layouts.base')

@section('title', 'Pembayaran Berhasil - Amazing Sultra Run')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <!-- Title -->
        <h1 class="text-2xl font-bold text-gray-900 mb-4">
            🎉 Pembayaran Berhasil!
        </h1>

        <!-- Message -->
        <p class="text-gray-600 mb-6 leading-relaxed">
            Terima kasih! Pembayaran registrasi Amazing Sultra Run Anda telah berhasil diproses. 
            Anda akan menerima konfirmasi melalui WhatsApp dalam beberapa saat.
        </p>

        <!-- Information Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-blue-900 mb-2">Langkah Selanjutnya:</h3>
            <ul class="text-sm text-blue-800 text-left space-y-1">
                <li>✅ Membership Anda sudah aktif</li>
                <li>✅ Cek WhatsApp untuk konfirmasi</li>
                <li>✅ Login ke dashboard untuk melengkapi profil</li>
                <li>✅ Lihat jadwal latihan dan event</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <a href="{{ route('login') }}" 
               class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-200 inline-block font-medium">
                Login ke Dashboard
            </a>
            <a href="{{ url('/') }}" 
               class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-200 transition duration-200 inline-block font-medium">
                Kembali ke Beranda
            </a>
        </div>

        <!-- Contact Info -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Butuh bantuan? <br>
                <span class="font-medium">Hubungi: +62811-4000-805</span>
            </p>
        </div>
    </div>
</div>

<style>
    .min-h-screen {
        min-height: 100vh;
    }
    .bg-gradient-to-br {
        background: linear-gradient(to bottom right, #f0fdf4, #eff6ff);
    }
    .shadow-xl {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endsection
