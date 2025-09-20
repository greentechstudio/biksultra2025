<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di {{ $event_name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: black;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .welcome-message {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .user-info {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .user-info h3 {
            margin-top: 0;
            color: #1976d2;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
        }
        .info-value {
            color: #333;
            font-weight: 500;
        }
        .next-steps {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .next-steps h3 {
            margin-top: 0;
            color: #856404;
        }
        .step {
            margin: 15px 0;
            padding: 10px 0;
        }
        .step-number {
            display: inline-block;
            background: #667eea;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            text-align: center;
            line-height: 25px;
            font-weight: bold;
            margin-right: 10px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            text-align: center;
            margin: 10px 5px;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #28a745;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }
        .contact-info {
            margin: 15px 0;
            padding: 15px;
            background: #e8f5e8;
            border-radius: 8px;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 10px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 5px;
            }
            .header {
                padding: 20px 15px;
            }
            .content {
                padding: 20px 15px;
            }
            .info-row {
                flex-direction: column;
            }
            .info-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎉 Selamat Datang!</h1>
            <p>{{ $event_full_name }} {{ $event_year }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Welcome Message -->
            <div class="welcome-message">
                <h2>Halo {{ $user->name }}! 👋</h2>
                <p>Terima kasih telah berpartisipasi pada event <strong>{{ $event_name }} NIGHTRUN 2025</strong>! Kami sangat senang menyambut Anda sebagai peserta dalam event yang diselenggarakan oleh {{ $event_organizer }}.</p>
            </div>

            <!-- User Information -->
            <div class="user-info">
                <h3>📋 Informasi Registrasi Anda</h3>
                <div class="info-row">
                    <span class="info-label">Nama Lengkap:</span>
                    <span class="info-value"> {{ $user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama BIB:</span>
                    <span class="info-value"> {{ $user->bib_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. BIB:</span>
                    <span class="info-value"><strong> {{ $user->registration_number }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"> {{ $user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kategori:</span>
                    <span class="info-value"> {{ $user->race_category }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ukuran Jersey:</span>
                    <span class="info-value"> {{ $user->jersey_size }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">WhatsApp:</span>
                    <span class="info-value"> {{ $user->whatsapp_number }}</span>
                </div>
            </div>

            <!-- Login Credentials -->
            

            <!-- Payment Information -->
            @if(isset($payment_url) && $payment_url)
            <div style="background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; padding: 20px; margin: 20px 0;">
                <h3 style="color: #856404; margin-top: 0;">💳 Informasi Pembayaran</h3>
                @if(isset($payment_amount) && is_numeric($payment_amount))
                <div class="info-row">
                    <span class="info-label">Biaya Registrasi:</span>
                    <span class="info-value"><strong>Rp {{ number_format((float)$payment_amount, 0, ',', '.') }}</strong></span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Berlaku hingga:</span>
                    <span class="info-value">24 jam dari sekarang</span>
                </div>
                <div style="text-align: center; margin: 20px 0;">
                    <a href="{{ $payment_url }}" style="display: inline-block; background: #ffc107; color: #212529; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; font-size: 16px;">
                        💳 BAYAR SEKARANG
                    </a>
                </div>
                <p style="margin: 15px 0 0 0; color: #856404; font-size: 14px;">
                    <strong>⚠️ Penting:</strong> Link pembayaran akan kedaluwarsa dalam 24 jam. Silakan selesaikan pembayaran sesegera mungkin.
                </p>
            </div>
            @endif

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>🚀 Langkah Selanjutnya</h3>
                
                

                <div class="step">
                    <span class="step-number">1</span>
                    <strong>Lakukan Pembayaran</strong> - Klik link pembayaran untuk menyelesaikan registrasi Anda.
                </div>
                
                <div class="step">
                    <span class="step-number">2</span>
                    <strong>Dapatkan Konfirmasi</strong> - Setelah pembayaran berhasil, Anda akan menerima konfirmasi otomatis.
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="https://www.instagram.com/biknightrun" class="btn btn-secondary">💬 Hubungi Admin</a>
            </div>

            <!-- Contact Information -->
            <div class="contact-info">
                <h4>📞 Butuh Bantuan?</h4>
                <p>Jika Anda mengalami kesulitan atau memiliki pertanyaan, jangan ragu untuk menghubungi kami:</p>
                <p>
                    <strong>DM Instagram:</strong> <a href="https://www.instagram.com/biknightrun">biknightrun</a><br>
                    <strong>Email :</strong> {{ $support_email }}
                </p>
            </div>

            <!-- Important Notes -->
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <h4 style="color: #856404; margin-top: 0;">⚠️ Penting untuk Diingat:</h4>
                <ul style="margin: 10px 0; padding-left: 20px; color: #856404;">
                    <li>Simpan email ini sebagai bukti registrasi</li>
                    <li>Link pembayaran berlaku selama 24 jam</li>
                    <li>Hubungi admin jika tidak menerima pesan dalam 5 menit</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0; color: #666;">
                <strong>{{ $event_name }} {{ $event_year }}</strong><br>
                Diselenggarakan oleh {{ $event_organizer }}
            </p>
            
            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Instagram</a> |
                <a href="#">Twitter</a>
            </div>
            
            <p style="font-size: 12px; color: #999; margin: 15px 0 0 0;">
                Email ini dikirim otomatis, mohon tidak membalas langsung ke email ini.<br>
                Jika ada pertanyaan, silakan hubungi admin melalui WhatsApp atau email support.
            </p>
        </div>
    </div>
</body>
</html>