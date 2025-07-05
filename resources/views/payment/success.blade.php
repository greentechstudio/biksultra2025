@extends('layouts.base')

@section('title', 'Pembayaran Berhasil - Amazing Sultra Run')

@section('content')
<div class="payment-success-container">
    <div class="success-card">
        <!-- Success Icon -->
        <div class="success-icon">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <!-- Title -->
        <h1 class="success-title">
            🎉 Pembayaran Berhasil!
        </h1>

        <!-- Message -->
        <p class="success-message">
            Terima kasih! Pembayaran registrasi Amazing Sultra Run Anda telah berhasil diproses. 
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
                Login ke Dashboard
            </a>
            <a href="{{ url('/') }}" class="btn btn-secondary">
                Kembali ke Beranda
            </a>
        </div>

        <!-- Contact Info -->
        <div class="contact-info">
            <p>
                Butuh bantuan? <br>
                <span class="contact-number">Hubungi: +62811-4000-805</span>
            </p>
        </div>
    </div>
</div>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
}

.payment-success-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.success-card {
    max-width: 480px;
    width: 100%;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    padding: 40px 30px;
    text-align: center;
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.success-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #4CAF50, #45a049);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    color: white;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.success-title {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.success-message {
    font-size: 16px;
    color: #666;
    margin-bottom: 30px;
    line-height: 1.7;
    text-align: center;
}

.info-box {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    border: 1px solid #90caf9;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    text-align: left;
}

.info-title {
    font-size: 18px;
    font-weight: 600;
    color: #1565c0;
    margin-bottom: 15px;
    text-align: center;
}

.info-list {
    list-style: none;
    padding: 0;
}

.info-list li {
    padding: 8px 0;
    color: #1976d2;
    font-weight: 500;
    border-bottom: 1px solid rgba(25, 118, 210, 0.1);
}

.info-list li:last-child {
    border-bottom: none;
}

.button-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 30px;
}

.btn {
    padding: 15px 30px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #f8f9fa;
    color: #495057;
    border: 2px solid #e9ecef;
}

.btn-secondary:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.contact-info {
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
    color: #6c757d;
    font-size: 14px;
}

.contact-number {
    font-weight: 600;
    color: #495057;
}

/* Responsive Design */
@media (max-width: 768px) {
    .payment-success-container {
        padding: 15px;
    }
    
    .success-card {
        padding: 30px 20px;
    }
    
    .success-title {
        font-size: 24px;
    }
    
    .success-message {
        font-size: 15px;
    }
    
    .btn {
        padding: 12px 25px;
        font-size: 15px;
    }
}

@media (max-width: 480px) {
    .success-card {
        padding: 25px 15px;
    }
    
    .success-icon {
        width: 70px;
        height: 70px;
    }
    
    .success-title {
        font-size: 22px;
    }
    
    .info-box {
        padding: 20px;
    }
}
</style>
@endsection
