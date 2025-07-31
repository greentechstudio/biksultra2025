@extends('layouts.app')

@section('title', 'Registrasi Kolektif Berhasil - Amazing Sultra Run')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-red-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success Hero Section -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-8 py-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-100 rounded-full mb-6">
                    <i class="fas fa-check-circle text-emerald-600 text-4xl"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    🎉 Registrasi Kolektif Berhasil!
                </h1>
                <p class="text-emerald-100 text-lg max-w-2xl mx-auto">
                    Selamat! Semua peserta telah berhasil didaftarkan untuk Amazing Sultra Run 2025
                </p>
            </div>
        </div>

        <!-- Success Details -->
        @if (session('success_message'))
            <div class="bg-white rounded-2xl shadow-lg border border-emerald-200 p-8 mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-emerald-500 text-xl mt-1"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-xl font-semibold text-emerald-800 mb-3">
                            Detail Registrasi
                        </h3>
                        <p class="text-gray-700 mb-4">{{ session('success_message') }}</p>
                        
                        @if (session('registration_numbers'))
                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-ticket-alt text-emerald-600 mr-2"></i>
                                    Nomor Registrasi Peserta
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach (session('registration_numbers') as $regNumber)
                                        <div class="bg-emerald-50 border-2 border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-center font-mono text-sm font-bold shadow-sm hover:shadow-md transition-all duration-200">
                                            <i class="fas fa-id-card mr-2"></i>
                                            {{ $regNumber }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Participant Details -->
                        @if (session('successful_users'))
                            <div class="mt-8">
                                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-users text-emerald-600 mr-2"></i>
                                    Detail Peserta Terdaftar ({{ session('success_count', 0) }} peserta)
                                </h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-gray-50 rounded-lg overflow-hidden">
                                        <thead class="bg-emerald-100">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-emerald-800 uppercase tracking-wider">Nama</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-emerald-800 uppercase tracking-wider">Email</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-emerald-800 uppercase tracking-wider">Kategori</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-emerald-800 uppercase tracking-wider">Biaya</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-emerald-200">
                                            @foreach (session('successful_users') as $user)
                                            <tr class="hover:bg-emerald-50 transition-colors duration-150">
                                                <td class="px-4 py-3">
                                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $user->bib_name }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-900">
                                                    {{ $user->email }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-900">
                                                    {{ $user->race_category_name }}
                                                </td>
                                                <td class="px-4 py-3 text-sm font-medium text-emerald-600">
                                                    Rp {{ number_format($user->registration_fee, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Errors Section -->
                        @if (session('errors') && count(session('errors')) > 0)
                            <div class="mt-8">
                                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-exclamation-triangle text-orange-600 mr-2"></i>
                                    Perhatian - {{ count(session('errors')) }} Peserta Perlu Ditindaklanjuti
                                </h4>
                                <div class="space-y-3">
                                    @foreach (session('errors') as $key => $error)
                                    <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded">
                                        <div class="font-medium text-orange-800">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                                        <div class="text-orange-700">{{ $error }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Next Steps -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-list-check text-blue-600 mr-3"></i>
                Langkah Selanjutnya
            </h3>
            
            <div class="space-y-6">
                <!-- Step 1: WhatsApp Notifications -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold text-sm">1</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">
                            <i class="fab fa-whatsapp text-green-600 mr-2"></i>
                            Cek WhatsApp Anda
                        </h4>
                        <p class="text-gray-600">
                            Setiap peserta akan menerima notifikasi WhatsApp dengan:
                        </p>
                        <ul class="text-gray-600 text-sm mt-2 ml-4 space-y-1">
                            <li>• Password login untuk akun</li>
                            <li>• Detail data registrasi</li>
                            <li>• Link aktivasi akun</li>
                        </ul>
                    </div>
                </div>

                <!-- Step 2: Payment -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-orange-600 font-bold text-sm">2</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">
                            <i class="fas fa-credit-card text-orange-600 mr-2"></i>
                            Pembayaran Kolektif
                        </h4>
                        <p class="text-gray-600">
                            Group leader (peserta pertama) akan menerima link pembayaran via WhatsApp untuk semua peserta sekaligus.
                        </p>
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mt-3">
                            <p class="text-orange-800 text-sm font-medium">
                                <i class="fas fa-clock mr-1"></i>
                                Batas waktu pembayaran: 24 jam
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Account Access -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-green-600 font-bold text-sm">3</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">
                            <i class="fas fa-user-circle text-green-600 mr-2"></i>
                            Login ke Akun
                        </h4>
                        <p class="text-gray-600 mb-3">
                            Setelah pembayaran berhasil, semua peserta dapat login dengan email dan password yang dikirim via WhatsApp.
                        </p>
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-200">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Informasi Penting
            </h3>
            <div class="space-y-3 text-blue-700">
                <p class="flex items-start">
                    <i class="fas fa-shipping-fast text-blue-600 mr-3 mt-1"></i>
                    Jersey akan dikirim ke alamat group leader setelah pembayaran dikonfirmasi
                </p>
                <p class="flex items-start">
                    <i class="fas fa-users text-blue-600 mr-3 mt-1"></i>
                    Semua peserta dalam grup yang sama akan aktif bersamaan setelah pembayaran
                </p>
                <p class="flex items-start">
                    <i class="fas fa-question-circle text-blue-600 mr-3 mt-1"></i>
                    Jika ada pertanyaan, hubungi customer service melalui WhatsApp
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center space-y-4">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register.kolektif') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-xl hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Daftar Grup Lain
                </a>
                <a href="{{ url('/') }}" 
                   class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>
            </div>
            
            <p class="text-gray-500 text-sm mt-6">
                Terima kasih telah mendaftar Amazing Sultra Run 2025! 🏃‍♂️🏃‍♀️
            </p>
        </div>
    </div>
</div>
@endsection
