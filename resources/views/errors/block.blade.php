@extends('errors.layout')
@section('title','404')
@section('message')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Ditutup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fffbea;
            color: #333;
            text-align: center;
            padding-top: 100px;
        }
        h1 {
            font-size: 64px;
            color: #ff6f61;
        }
        p {
            font-size: 20px;
            margin-top: 20px;
        }
        a {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>🙏 Maaf!</h1>
    <p>Registrasi ditutup sementara.</p>
    <p>Silakan cek kembali di lain waktu.</p>
    <a href="{{ url('/') }}">← Kembali ke Beranda</a>
</body>
</html>

@endsection

