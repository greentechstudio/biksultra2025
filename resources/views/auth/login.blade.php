@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-center">
        <div class="mb-4">
            <i class="fas fa-running text-4xl text-white"></i>
        </div>
        <h2 class="text-2xl font-bold text-white mb-2">Amazing Sultra Run</h2>
        <p class="text-blue-100">Masuk ke akun Anda</p>
    </div>
    
    <!-- Form -->
    <div class="p-6">
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-gray-400"></i>Email
                </label>
                <input type="email" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 ring-red-500 @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Masukkan email Anda"
                       required>
                @error('email')
                    <p class="text-red-500 text-sm mt-2">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>
            
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-gray-400"></i>Password
                </label>
                <input type="password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 ring-red-500 @enderror" 
                       id="password" 
                       name="password" 
                       placeholder="Masukkan password Anda"
                       required>
                @error('password')
                    <p class="text-red-500 text-sm mt-2">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>
            
            <!-- Remember Me -->
            <div class="flex items-center">
                <input type="checkbox" 
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                       id="remember" 
                       name="remember">
                <label class="ml-2 block text-sm text-gray-700" for="remember">
                    Ingat saya
                </label>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
            </button>
            
            <!-- Links -->
            <div class="text-center space-y-3 pt-4 border-t border-gray-200">
                <p>
                    <a href="{{ route('password.reset') }}" 
                       class="text-green-600 hover:text-green-700 font-medium transition-colors">
                        <i class="fab fa-whatsapp mr-1"></i>Lupa Password? Reset via WhatsApp
                    </a>
                </p>
                <p class="text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" 
                       class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
                        Daftar di sini
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
