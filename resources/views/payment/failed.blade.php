@extends('layouts.base')

@section('title', 'Pembayaran Gagal - Amazing Sultra Run')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <!-- Error Icon -->
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>

        <!-- Title -->
        <h1 class="text-2xl font-bold text-gray-900 mb-4">
            ❌ Pembayaran Gagal
        </h1>

        <!-- Message -->
        <p class="text-gray-600 mb-6 leading-relaxed">
            Maaf, pembayaran Anda tidak dapat diproses. Jangan khawatir, Anda bisa mencoba lagi 
            atau menggunakan metode pembayaran yang berbeda.
        </p>

        <!-- Information Box -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-yellow-900 mb-2">Yang Bisa Anda Lakukan:</h3>
            <ul class="text-sm text-yellow-800 text-left space-y-1">
                <li>🔄 Coba lagi dengan metode pembayaran lain</li>
                <li>📞 Hubungi admin untuk bantuan</li>
                <li>🔑 Login ke akun untuk melihat status</li>
                <li>💬 Gunakan WhatsApp untuk bantuan cepat</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <a href="{{ route('login') }}" 
               class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-200 inline-block font-medium">
                Login ke Akun
            </a>
            <a href="{{ url('/') }}" 
               class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-200 transition duration-200 inline-block font-medium">
                Kembali ke Beranda
            </a>
        </div>

        <!-- Contact Info -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-500 mb-2">
                <strong>Butuh Bantuan?</strong>
            </p>
            <div class="space-y-1 text-sm">
                <p>📞 <span class="font-medium">+62811-4000-805</span></p>
                <p>📧 <span class="font-medium">hola@dominantsite.com</span></p>
                <p>💬 <span class="font-medium">WhatsApp Admin</span></p>
            </div>
        </div>

        <!-- Additional Help -->
        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-xs text-blue-700">
                <strong>Tips:</strong> Pastikan saldo mencukupi dan koneksi internet stabil. 
                Jika masalah berlanjut, hubungi bank penerbit kartu Anda.
            </p>
        </div>
    </div>
</div>

<style>
    .min-h-screen {
        min-height: 100vh;
    }
    .bg-gradient-to-br {
        background: linear-gradient(to bottom right, #fef2f2, #fff7ed);
    }
    .shadow-xl {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endsection
