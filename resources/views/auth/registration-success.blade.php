<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil - BIKSULTRA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 3rem;
            margin: 2rem auto;
            max-width: 600px;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 2rem;
        }
        .warning-icon {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }
        .success-title {
            color: #333;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .success-text {
            color: #666;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
        }
        .payment-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        .whatsapp-note {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-card">
            @if(session('success'))
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="text-center success-title">🎉 Registrasi Berhasil!</h1>
                <p class="text-center success-text">{{ session('success') }}</p>
            @elseif(session('warning'))
                <div class="success-icon warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h1 class="text-center success-title">⚠️ Perhatian!</h1>
                <p class="text-center success-text">{{ session('warning') }}</p>
            @else
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="text-center success-title">Registrasi Berhasil!</h1>
                <p class="text-center success-text">Terima kasih telah mendaftar di BIKSULTRA!</p>
            @endif

            <div class="payment-info">
                <h5><i class="fas fa-credit-card text-primary me-2"></i>Langkah Selanjutnya:</h5>
                <ol>
                    <li><strong>Cek Email Anda</strong> - Link pembayaran telah dikirim</li>
                    <li><strong>Lakukan Pembayaran</strong> - Klik link dan ikuti petunjuk pembayaran</li>
                    <li><strong>Konfirmasi Otomatis</strong> - Status membership akan diupdate setelah pembayaran berhasil</li>
                    <li><strong>Akses Penuh</strong> - Login untuk mengakses semua fitur BIKSULTRA</li>
                </ol>
            </div>

            <div class="whatsapp-note">
                <i class="fab fa-whatsapp text-success me-2"></i>
                <small><strong>Tips:</strong> Pastikan WhatsApp Anda aktif untuk menerima link pembayaran. Jika tidak menerima pesan dalam 5 menit, silakan hubungi admin kami.</small>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="btn btn-primary me-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Login Sekarang
                </a>
                <a href="{{ url('/') }}" class="btn btn-secondary">
                    <i class="fas fa-home me-2"></i>Kembali ke Beranda
                </a>
            </div>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="fas fa-phone me-1"></i>Butuh bantuan? 
                    <a href="https://wa.me/6281234567890" target="_blank" class="text-decoration-none">
                        Hubungi Admin WhatsApp
                    </a>
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>