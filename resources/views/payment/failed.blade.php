@extends('layouts.base')

@section('title', 'Pembayaran Gagal - Amazing Sultra Run')

@section('content')
<div class="payment-failed-container">
    <div class="failed-card">
        <!-- Error Icon -->
        <div class="failed-icon">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>

        <!-- Title -->
        <h1 class="failed-title">
            ❌ Pembayaran Gagal
        </h1>

        <!-- Message -->
        <p class="failed-message">
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
                Login ke Akun
            </a>
            <a href="{{ url('/') }}" class="btn btn-secondary">
                Kembali ke Beranda
            </a>
        </div>

        <!-- Contact Info -->
        <div class="contact-info">
            <p>
                Butuh bantuan? <br>
                <span class="contact-number">
                    Hubungi: +62811-4000-805<br>
                    WhatsApp: +62811-4000-805
                </span>
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

.payment-failed-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.failed-card {
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

.failed-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    color: white;
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(-5px);
    }
    75% {
        transform: translateX(5px);
    }
}

.failed-title {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.failed-message {
    font-size: 16px;
    color: #666;
    margin-bottom: 30px;
    line-height: 1.7;
    text-align: center;
}

.info-box {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    border: 1px solid #ffc107;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    text-align: left;
}

.info-title {
    font-size: 18px;
    font-weight: 600;
    color: #856404;
    margin-bottom: 15px;
    text-align: center;
}

.info-list {
    list-style: none;
    padding: 0;
}

.info-list li {
    padding: 8px 0;
    color: #856404;
    font-weight: 500;
    border-bottom: 1px solid rgba(133, 100, 4, 0.1);
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
    .payment-failed-container {
        padding: 15px;
    }
    
    .failed-card {
        padding: 30px 20px;
    }
    
    .failed-title {
        font-size: 24px;
    }
    
    .failed-message {
        font-size: 15px;
    }
    
    .btn {
        padding: 12px 25px;
        font-size: 15px;
    }
}

@media (max-width: 480px) {
    .failed-card {
        padding: 25px 15px;
    }
    
    .failed-icon {
        width: 70px;
        height: 70px;
    }
    
    .failed-title {
        font-size: 22px;
    }
    
    .info-box {
        padding: 20px;
    }
}
</style>
@endsection
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
