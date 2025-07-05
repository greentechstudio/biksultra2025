@extends('layouts.app')

@section('title', 'Dashboard - Status Pendaftaran')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title mb-2">
                                <i class="fas fa-running me-2"></i>
                                Selamat Datang, {{ $user->name }}!
                            </h4>
                            <p class="card-text mb-0">
                                Status pendaftaran event lari Anda dan informasi penting lainnya.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="fas fa-user-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Status Cards -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-id-card me-2"></i>Informasi Pendaftaran
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%" class="fw-bold">Nama:</td>
                            <td>{{ $userStatus['name'] }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email:</td>
                            <td>{{ $userStatus['email'] }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Kategori Lomba:</td>
                            <td>
                                <span class="badge bg-primary">{{ $userStatus['race_category'] }}</span>
                                @if($categoryInfo)
                                    <br><small class="text-muted">{{ $categoryInfo['description'] }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Ukuran Jersey:</td>
                            <td><span class="badge bg-secondary">{{ $userStatus['jersey_size'] }}</span></td>
                        </tr>
                        @if($categoryInfo)
                        <tr>
                            <td class="fw-bold">Biaya Registrasi:</td>
                            <td class="fw-bold text-success">Rp {{ number_format($categoryInfo['price'], 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    </table>

                    <!-- Edit Profile Button -->
                    <div class="mt-3">
                        @if($userStatus['can_edit_profile'])
                            <a href="{{ route('profile.edit') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit Profil
                            </a>
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle me-1"></i>
                                Edit profil hanya dapat dilakukan sekali
                            </small>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-lock me-1"></i>Profil Sudah Diedit
                            </span>
                            <small class="text-muted d-block mt-1">
                                Diedit pada: {{ $userStatus['profile_edited_at'] ? $userStatus['profile_edited_at']->format('d/m/Y H:i') : '-' }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Verification & Payment -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>Status Verifikasi & Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <!-- WhatsApp Verification Status -->
                    <div class="mb-4">
                        <h6 class="fw-bold">
                            <i class="fab fa-whatsapp me-2 text-success"></i>Verifikasi WhatsApp
                        </h6>
                        @if($userStatus['whatsapp_verified'])
                            <div class="alert alert-success py-2">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Terverifikasi</strong>
                                <br><small>{{ $userStatus['whatsapp_verified_at'] ? $userStatus['whatsapp_verified_at']->format('d/m/Y H:i') : '' }}</small>
                            </div>
                        @else
                            <div class="alert alert-warning py-2">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Menunggu Verifikasi</strong>
                                <br><small>Silakan hubungi admin untuk verifikasi WhatsApp</small>
                            </div>
                        @endif
                    </div>

                    <!-- Payment Status -->
                    <div class="mb-3">
                        <h6 class="fw-bold">
                            <i class="fas fa-credit-card me-2 text-primary"></i>Status Pembayaran
                        </h6>
                        @if($userStatus['payment_confirmed'])
                            <div class="alert alert-success py-2">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Pembayaran Dikonfirmasi</strong>
                                <br><small>{{ $userStatus['payment_confirmed_at'] ? $userStatus['payment_confirmed_at']->format('d/m/Y H:i') : '' }}</small>
                                @if($userStatus['payment_amount'])
                                    <br><small class="fw-bold">Jumlah: Rp {{ number_format($userStatus['payment_amount'], 0, ',', '.') }}</small>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-danger py-2">
                                <i class="fas fa-times-circle me-2"></i>
                                <strong>Belum Dikonfirmasi</strong>
                                <br><small>Silakan lakukan pembayaran dan hubungi admin untuk konfirmasi</small>
                            </div>
                        @endif
                    </div>

                    <!-- Overall Status -->
                    <div class="mt-4">
                        <h6 class="fw-bold">
                            <i class="fas fa-flag me-2 text-warning"></i>Status Keseluruhan
                        </h6>
                        @php
                            $overallStatus = 'pending';
                            $statusText = 'Pendaftaran Belum Lengkap';
                            $statusClass = 'warning';
                            
                            if ($userStatus['whatsapp_verified'] && $userStatus['payment_confirmed']) {
                                $overallStatus = 'complete';
                                $statusText = 'Pendaftaran Lengkap';
                                $statusClass = 'success';
                            } elseif ($userStatus['whatsapp_verified'] || $userStatus['payment_confirmed']) {
                                $overallStatus = 'partial';
                                $statusText = 'Pendaftaran Sebagian';
                                $statusClass = 'info';
                            }
                        @endphp
                        
                        <span class="badge bg-{{ $statusClass }} fs-6 px-3 py-2">
                            <i class="fas fa-{{ $overallStatus === 'complete' ? 'check' : ($overallStatus === 'partial' ? 'clock' : 'exclamation') }} me-1"></i>
                            {{ $statusText }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Petunjuk Penting
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary">
                                <i class="fas fa-list-ol me-2"></i>Langkah-langkah Pendaftaran:
                            </h6>
                            <ol class="list-group list-group-numbered">
                                <li class="list-group-item border-0 px-0">
                                    Lengkapi data profil (jika diperlukan)
                                    @if($userStatus['can_edit_profile'])
                                        <span class="badge bg-warning ms-2">Dapat Diedit</span>
                                    @else
                                        <span class="badge bg-success ms-2">Selesai</span>
                                    @endif
                                </li>
                                <li class="list-group-item border-0 px-0">
                                    Verifikasi WhatsApp
                                    @if($userStatus['whatsapp_verified'])
                                        <span class="badge bg-success ms-2">Selesai</span>
                                    @else
                                        <span class="badge bg-warning ms-2">Pending</span>
                                    @endif
                                </li>
                                <li class="list-group-item border-0 px-0">
                                    Lakukan pembayaran registrasi
                                    @if($userStatus['payment_confirmed'])
                                        <span class="badge bg-success ms-2">Selesai</span>
                                    @else
                                        <span class="badge bg-danger ms-2">Belum</span>
                                    @endif
                                </li>
                                <li class="list-group-item border-0 px-0">
                                    Konfirmasi pembayaran ke admin
                                    @if($userStatus['payment_confirmed'])
                                        <span class="badge bg-success ms-2">Selesai</span>
                                    @else
                                        <span class="badge bg-warning ms-2">Pending</span>
                                    @endif
                                </li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>Hal Penting:
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Edit profil hanya dapat dilakukan <strong>satu kali</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Pastikan data yang dimasukkan sudah benar
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Simpan bukti pembayaran untuk konfirmasi
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Hubungi admin jika ada kendala
                                </li>
                            </ul>

                            @if($categoryInfo)
                            <div class="alert alert-info mt-3">
                                <h6 class="fw-bold">
                                    <i class="fas fa-money-bill me-2"></i>Info Pembayaran:
                                </h6>
                                <p class="mb-2">
                                    <strong>Kategori:</strong> {{ $categoryInfo['name'] }}<br>
                                    <strong>Biaya:</strong> Rp {{ number_format($categoryInfo['price'], 0, ',', '.') }}
                                </p>
                                <small class="text-muted">
                                    Silakan hubungi admin untuk info rekening pembayaran
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh status setiap 30 detik
    setInterval(function() {
        // Hanya refresh jika ada status yang pending
        @if(!$userStatus['whatsapp_verified'] || !$userStatus['payment_confirmed'])
            location.reload();
        @endif
    }, 30000);
});
</script>
@endsection
