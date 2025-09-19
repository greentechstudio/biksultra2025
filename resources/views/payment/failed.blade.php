@extends('layouts.base')

@section('title', config('event.titles.payment_failed'))

@section('content')
<div class="payment-success-container">
    <div class="success-card glass-effect">
        <!-- Error Icon -->
        <div class="error-icon">
            <i class="fas fa-times"></i>
        </div>

        <!-- Title -->
        <h1 class="status-title error-title">
            ❌ Pembayaran Gagal
        </h1>

        <!-- Message -->
        <p class="status-message">
            Maaf, pembayaran Anda tidak dapat diproses. Jangan khawatir, Anda bisa mencoba lagi 
            atau menggunakan metode pembayaran yang berbeda.
        </p>

        <!-- Information Box -->
        <div class="info-box">
            <h3 class="info-title">Yang Bisa Anda Lakukan:</h3>
            <ul class="info-list">
                <li>🔄 Coba lagi dengan metode pembayaran lain</li>
                <li>📞 Hubungi admin untuk bantuan</li>
                <li>🔑 Login ke akun untuk melihat status</li>
                <li>💬 Gunakan WhatsApp untuk bantuan cepat</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="button-container">
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login ke Akun
            </a>
            <a href="{{ url('/') }}" class="btn btn-secondary">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>

        <!-- Contact Info -->
        <div class="contact-info">
            <p>
                Butuh bantuan? <br>
                <a href="tel:+6281140000805">📞 Hubungi: +62811-4000-805</a>
            </p>
        </div>
    </div>
</div>
@endsection
