@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user me-2"></i>
        Profile
    </h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Profile
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.profile') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" 
                                       id="email" value="{{ $user->email }}" disabled>
                                <div class="form-text">Email tidak dapat diubah</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor WhatsApp</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status Akun</label>
                                <input type="text" class="form-control" 
                                       id="status" value="{{ ucfirst($user->status) }}" disabled>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi Akun
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Tanggal Daftar:</strong></td>
                        <td>{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>WhatsApp Verified:</strong></td>
                        <td>
                            @if($user->whatsapp_verified)
                                <span class="badge bg-success">Ya</span><br>
                                <small class="text-muted">{{ $user->whatsapp_verified_at ? $user->whatsapp_verified_at->format('d M Y H:i') : '' }}</small>
                            @else
                                <span class="badge bg-danger">Belum</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Payment Confirmed:</strong></td>
                        <td>
                            @if($user->payment_confirmed)
                                <span class="badge bg-success">Ya</span><br>
                                <small class="text-muted">{{ $user->payment_confirmed_at ? $user->payment_confirmed_at->format('d M Y H:i') : '' }}</small><br>
                                <small class="text-muted">Rp {{ number_format($user->payment_amount, 0, ',', '.') }}</small>
                            @else
                                <span class="badge bg-danger">Belum</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Last Update:</strong></td>
                        <td>{{ $user->updated_at ? $user->updated_at->format('d M Y H:i') : '-' }}</td>
                    </tr>
                </table>
                
                @if(!$user->whatsapp_verified || !$user->payment_confirmed)
                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            @if(!$user->whatsapp_verified)
                                Silakan verifikasi WhatsApp terlebih dahulu.
                            @elseif(!$user->payment_confirmed)
                                Silakan konfirmasi pembayaran untuk mengaktifkan akun.
                            @endif
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
