@extends('layouts.user')

@section('title', 'Profile')

@section('header')
<h1 class="text-2xl font-bold text-gray-900">
    <i class="fas fa-user mr-3"></i>
    Profile
</h1>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Edit Profile Form -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </h2>
            </div>
            <div class="px-6 py-6">
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" 
                                   id="email" 
                                   value="{{ $user->email }}" 
                                   disabled
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                            <p class="mt-2 text-sm text-gray-500">Email tidak dapat diubah</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                            <input type="text" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}" 
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-300 @enderror">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Akun</label>
                            <input type="text" 
                                   id="status" 
                                   value="{{ ucfirst($user->status) }}" 
                                   disabled
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Account Information -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Akun
                </h2>
            </div>
            <div class="px-6 py-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-medium text-gray-500">Tanggal Daftar:</span>
                        <span class="text-sm text-gray-900">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-medium text-gray-500">WhatsApp Verified:</span>
                        <div class="text-right">
                            @if($user->whatsapp_verified)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ya</span>
                                @if($user->whatsapp_verified_at)
                                    <p class="text-xs text-gray-500 mt-1">{{ $user->whatsapp_verified_at->format('d M Y H:i') }}</p>
                                @endif
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Belum</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-medium text-gray-500">Payment Confirmed:</span>
                        <div class="text-right">
                            @if($user->payment_confirmed)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ya</span>
                                @if($user->payment_confirmed_at)
                                    <p class="text-xs text-gray-500 mt-1">{{ $user->payment_confirmed_at->format('d M Y H:i') }}</p>
                                @endif
                                @if($user->payment_amount)
                                    <p class="text-xs text-gray-500">Rp {{ number_format($user->payment_amount, 0, ',', '.') }}</p>
                                @endif
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Belum</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-medium text-gray-500">Last Update:</span>
                        <span class="text-sm text-gray-900">{{ $user->updated_at ? $user->updated_at->format('d M Y H:i') : '-' }}</span>
                    </div>
                </div>
                
                @if(!$user->whatsapp_verified || !$user->payment_confirmed)
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">
                                    @if(!$user->whatsapp_verified)
                                        Silakan verifikasi WhatsApp terlebih dahulu.
                                    @elseif(!$user->payment_confirmed)
                                        Silakan konfirmasi pembayaran untuk mengaktifkan akun.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
