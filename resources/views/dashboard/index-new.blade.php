@extends('layouts.user')

@section('title', 'Dashboard')

@section('header')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">
        <i class="fas fa-tachometer-alt mr-3"></i>
        Dashboard Event Lari
    </h1>
    <div class="flex items-center">
        <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">{{ $user->name }}</span>
    </div>
</div>
@endsection

@section('content')
<!-- Status Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Peserta -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium uppercase tracking-wider opacity-90">Total Peserta</div>
                    <div class="text-2xl font-bold">{{ $stats['total_users'] }}</div>
                    <div class="text-sm opacity-75">Seluruh registrasi</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-3xl opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pembayaran Lunas -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium uppercase tracking-wider opacity-90">Pembayaran Lunas</div>
                    <div class="text-2xl font-bold">{{ $stats['paid_users'] }}</div>
                    <div class="text-sm opacity-75">Sudah bayar</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-3xl opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Terverifikasi WA -->
    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium uppercase tracking-wider opacity-90">Terverifikasi WA</div>
                    <div class="text-2xl font-bold">{{ $stats['verified_users'] }}</div>
                    <div class="text-sm opacity-75">WhatsApp OK</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fab fa-whatsapp text-3xl opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Menunggu Pembayaran -->
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium uppercase tracking-wider opacity-90">Menunggu Pembayaran</div>
                    <div class="text-2xl font-bold">{{ $stats['pending_users'] }}</div>
                    <div class="text-sm opacity-75">Belum bayar</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-3xl opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Pendapatan Terkonfirmasi -->
    <div class="bg-white shadow rounded-lg border-l-4 border-green-500">
        <div class="px-6 py-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-money-bill-wave text-green-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Pendapatan Terkonfirmasi</h3>
                    <div class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue['confirmed'], 0, ',', '.') }}</div>
                    <p class="text-sm text-gray-500">Dari {{ $stats['paid_users'] }} peserta</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Potensi Pendapatan -->
    <div class="bg-white shadow rounded-lg border-l-4 border-blue-500">
        <div class="px-6 py-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-chart-line text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Potensi Pendapatan</h3>
                    <div class="text-2xl font-bold text-blue-600">Rp {{ number_format($totalRevenue['potential'], 0, ',', '.') }}</div>
                    <p class="text-sm text-gray-500">Total potensi maksimal</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tertunda -->
    <div class="bg-white shadow rounded-lg border-l-4 border-yellow-500">
        <div class="px-6 py-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-hourglass-half text-yellow-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Tertunda</h3>
                    <div class="text-2xl font-bold text-yellow-600">Rp {{ number_format($totalRevenue['pending'], 0, ',', '.') }}</div>
                    <p class="text-sm text-gray-500">Menunggu pembayaran</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Status & Category Breakdown -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Your Status -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">
                <i class="fas fa-user-check mr-2"></i>Status Anda
            </h2>
        </div>
        <div class="px-6 py-6">
            <div class="space-y-4">
                <!-- Registration Status -->
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">Status Pendaftaran:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Terdaftar
                    </span>
                </div>

                <!-- WhatsApp Verification -->
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">Verifikasi WhatsApp:</span>
                    @if($user->whatsapp_verified)
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>Terverifikasi
                        </span>
                    @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            <i class="fas fa-times mr-1"></i>Belum Verifikasi
                        </span>
                    @endif
                </div>

                <!-- Payment Status -->
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">Status Pembayaran:</span>
                    @if($user->payment_confirmed)
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>Lunas
                        </span>
                    @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>Pending
                        </span>
                    @endif
                </div>

                <!-- Race Category -->
                @if($user->race_category)
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">Kategori Lomba:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $user->race_category }}</span>
                </div>
                @endif
            </div>

            @if(!$user->whatsapp_verified || !$user->payment_confirmed)
                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Perhatian!</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                @if(!$user->whatsapp_verified)
                                    <p>• Silakan verifikasi nomor WhatsApp Anda</p>
                                @endif
                                @if(!$user->payment_confirmed)
                                    <p>• Silakan lakukan pembayaran untuk mengaktifkan akun</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Category Statistics -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">
                <i class="fas fa-chart-pie mr-2"></i>Statistik Kategori
            </h2>
        </div>
        <div class="px-6 py-6">
            @if(isset($categoryStats) && count($categoryStats) > 0)
                <div class="space-y-4">
                    @foreach($categoryStats as $category)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                    <i class="fas fa-running text-blue-600"></i>
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                <p class="text-xs text-gray-500">Rp {{ number_format($category->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ $category->users_count ?? 0 }}</p>
                            <p class="text-xs text-gray-500">peserta</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center">Tidak ada data kategori</p>
            @endif
        </div>
    </div>
</div>
@endsection
