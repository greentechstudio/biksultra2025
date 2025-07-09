@extends('layouts.user')

@section('title', 'Profil Saya')

@section('header')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">
        <i class="fas fa-user-circle mr-3"></i>Profil Saya
    </h1>
    <div class="flex items-center space-x-3">
        @if($user->canEditProfile())
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                <i class="fas fa-edit mr-2"></i>Edit Profil
            </a>
        @else
            <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800">
                <i class="fas fa-lock mr-2"></i>Profil Sudah Diedit
            </span>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        @if(session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(!$user->canEditProfile())
            <div class="rounded-md bg-blue-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">
                            <strong>Informasi:</strong> Anda sudah pernah mengedit profil pada {{ $user->profile_edited_at->format('d/m/Y H:i') }}. 
                            Edit profil hanya dapat dilakukan sekali untuk menjaga integritas data registrasi.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Data Pribadi -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-user mr-3 text-blue-600"></i>Data Pribadi
                </h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Email:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">No. Telepon:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->phone ?: '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">WhatsApp:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->whatsapp_number }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Jenis Kelamin:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->gender }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Tempat Lahir:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->birth_place }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Lahir:</dt>
                            <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->birth_date)->format('d/m/Y') }} 
                                ({{ \Carbon\Carbon::parse($user->birth_date)->age }} tahun)</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Alamat:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->address }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Pekerjaan:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->occupation }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Golongan Darah:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->blood_type }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Data Perlombaan -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-running mr-3 text-green-600"></i>Data Perlombaan
                </h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Nama BIB:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->bib_name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Kategori Lomba:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->race_category }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Ukuran Jersey:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->jersey_size }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Grup/Komunitas:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->group_community ?: '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Sumber Info Event:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->event_source }}</dd>
                        </div>
                    </dl>
                </div>

                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-phone-alt mr-3 text-red-600"></i>Kontak Darurat
                </h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Nama Kontak Darurat:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->emergency_contact_name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Nomor Kontak Darurat:</dt>
                            <dd class="text-sm text-gray-900">{{ $user->emergency_contact_phone }}</dd>
                        </div>
                    </dl>
                </div>

                @if($user->medical_history)
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-notes-medical mr-3 text-yellow-600"></i>Riwayat Medis
                </h3>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">{{ $user->medical_history }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Status Edit Profil -->
        @if(!$user->canEditProfile())
            <div class="mt-8">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-md font-medium text-gray-900 flex items-center mb-3">
                        <i class="fas fa-history mr-2"></i>Riwayat Edit Profil
                    </h4>
                    <div class="text-sm text-gray-600">
                        <p><strong>Diedit pada:</strong> {{ $user->profile_edited_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Catatan:</strong> {{ $user->edit_notes }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Info Footer -->
        <div class="mt-8">
            <div class="rounded-md bg-blue-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">
                            <strong>Catatan Penting:</strong> 
                            Edit profil hanya dapat dilakukan <strong>satu kali</strong> setelah registrasi. 
                            Pastikan semua data yang Anda ubah sudah benar sebelum menyimpan perubahan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
