@extends('layouts.base')

@section('title', config('event.titles.payment_success'))

@section('content')
<div class="payment-success-container">
    <div class="success-card glass-effect">
        <!-- Success Icon -->
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <!-- Title -->
        <h1 class="status-title success-title">
            🎉 Pembayaran Berhasil!
        </h1>

        <!-- Message -->
        <p class="success-message">
            Terima kasih! Pembayaran registrasi {{ config('event.name') }} {{ config('event.year') }} Anda telah berhasil diproses. 
            Anda akan menerima konfirmasi melalui WhatsApp dalam beberapa saat.
        </p>

        <!-- Information Box -->
        <div class="info-box">
            <h3 class="info-title">Langkah Selanjutnya:</h3>
            <ul class="info-list">
                <li>✅ Membership Anda sudah aktif</li>
                <li>✅ Cek WhatsApp untuk konfirmasi</li>
                <li>✅ Login ke dashboard untuk melengkapi profil</li>
                <li>✅ Lihat jadwal latihan dan event</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="button-container">
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login ke Dashboard
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
