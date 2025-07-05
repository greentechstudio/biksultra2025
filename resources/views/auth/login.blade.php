@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h3><i class="fas fa-sign-in-alt me-2"></i>Login</h3>
        <p class="mb-0">Masuk ke akun Anda</p>
    </div>
    
    <div class="auth-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">
                    Ingat saya
                </label>
            </div>
            
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </div>
            
            <div class="text-center">
                <p class="mb-2">
                    <a href="{{ route('password.reset') }}" class="text-decoration-none">
                        <i class="fab fa-whatsapp me-1"></i>Lupa Password? Reset via WhatsApp
                    </a>
                </p>
                <p class="mb-0">Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-decoration-none">Daftar di sini</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
