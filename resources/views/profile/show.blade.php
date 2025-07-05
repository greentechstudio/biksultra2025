@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-user-circle me-2"></i>Profil Saya
                    </h4>
                    @if($user->canEditProfile())
                        <a href="{{ route('profile.edit') }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit Profil
                        </a>
                    @else
                        <span class="badge bg-secondary">
                            <i class="fas fa-lock me-1"></i>Profil Sudah Diedit
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(!$user->canEditProfile())
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi:</strong> Anda sudah pernah mengedit profil pada {{ $user->profile_edited_at->format('d/m/Y H:i') }}. 
                            Edit profil hanya dapat dilakukan sekali untuk menjaga integritas data registrasi.
                        </div>
                    @endif

                    <div class="row">
                        <!-- Data Pribadi -->
                        <div class="col-md-6">
                            <h5 class="mb-3">
                                <i class="fas fa-user me-2 text-primary"></i>Data Pribadi
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%" class="fw-bold">Nama Lengkap:</td>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Email:</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">No. Telepon:</td>
                                        <td>{{ $user->phone ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">WhatsApp:</td>
                                        <td>{{ $user->whatsapp_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Jenis Kelamin:</td>
                                        <td>{{ $user->gender }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tempat Lahir:</td>
                                        <td>{{ $user->birth_place }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tanggal Lahir:</td>
                                        <td>{{ \Carbon\Carbon::parse($user->birth_date)->format('d/m/Y') }} 
                                            ({{ \Carbon\Carbon::parse($user->birth_date)->age }} tahun)</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Alamat:</td>
                                        <td>{{ $user->address }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Pekerjaan:</td>
                                        <td>{{ $user->occupation }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Golongan Darah:</td>
                                        <td>{{ $user->blood_type }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Data Perlombaan -->
                        <div class="col-md-6">
                            <h5 class="mb-3">
                                <i class="fas fa-running me-2 text-success"></i>Data Perlombaan
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%" class="fw-bold">Nama BIB:</td>
                                        <td>{{ $user->bib_name }}</td>
                                    </tr>
                                    <tr>
                                        <td width="40%" class="fw-bold">Kategori Lomba:</td>
                                        <td>{{ $user->race_category }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Ukuran Jersey:</td>
                                        <td>{{ $user->jersey_size }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Grup/Komunitas:</td>
                                        <td>{{ $user->group_community ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Sumber Info Event:</td>
                                        <td>{{ $user->event_source }}</td>
                                    </tr>
                                </table>
                            </div>

                            <h5 class="mb-3 mt-4">
                                <i class="fas fa-phone-alt me-2 text-danger"></i>Kontak Darurat
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%" class="fw-bold">Kontak Darurat 1:</td>
                                        <td>{{ $user->emergency_contact_1 }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Kontak Darurat 2:</td>
                                        <td>{{ $user->emergency_contact_2 ?: '-' }}</td>
                                    </tr>
                                </table>
                            </div>

                            @if($user->medical_history)
                            <h5 class="mb-3 mt-4">
                                <i class="fas fa-notes-medical me-2 text-warning"></i>Riwayat Medis
                            </h5>
                            <div class="alert alert-light">
                                {{ $user->medical_history }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Status Edit Profil -->
                    @if(!$user->canEditProfile())
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-history me-2"></i>Riwayat Edit Profil
                                        </h6>
                                        <p class="card-text mb-0">
                                            <strong>Diedit pada:</strong> {{ $user->profile_edited_at->format('d/m/Y H:i') }}<br>
                                            <strong>Catatan:</strong> {{ $user->edit_notes }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Info Footer -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Catatan Penting:</strong> 
                                Edit profil hanya dapat dilakukan <strong>satu kali</strong> setelah registrasi. 
                                Pastikan semua data yang Anda ubah sudah benar sebelum menyimpan perubahan.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
