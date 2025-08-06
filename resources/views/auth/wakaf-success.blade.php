<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Wakaf Berhasil - Amazing Sultan Ultra Run</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/icons/logo-wakaf-icon.png') }}" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h4>Registrasi Wakaf Berhasil</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                        </div>
                        <h5>Selamat! Registrasi wakaf Anda telah berhasil.</h5>
                        <p>Silakan tunggu, Anda akan diarahkan ke halaman login...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show success alert with details
        @if(isset($data))
        alert(`Registrasi Wakaf Berhasil!

Nama: {{ $data['user_name'] ?? '' }}
Email: {{ $data['email'] ?? '' }}
Nomor Registrasi: {{ $data['registration_number'] ?? '' }}
Kategori Lomba: {{ $data['race_category'] ?? '' }}
Ukuran Jersey: {{ $data['jersey_size'] ?? '' }}
WhatsApp: {{ $data['whatsapp_number'] ?? '' }}
Total Wakaf: Rp {{ number_format($data['amount'] ?? 0, 0, ',', '.') }}

{{ $data['message'] ?? 'Silakan cek WhatsApp Anda untuk detail pembayaran.' }}

Anda akan diarahkan ke halaman login.`);
        @else
        alert('Registrasi wakaf berhasil! Anda akan diarahkan ke halaman login.');
        @endif

        // Redirect to login after 2 seconds
        setTimeout(function() {
            window.location.href = "{{ route('login') }}";
        }, 2000);
    </script>
</body>
</html>
