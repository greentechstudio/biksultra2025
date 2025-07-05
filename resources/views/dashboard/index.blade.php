@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2"></i>
        Dashboard Event Lari
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <span class="badge bg-primary fs-6">{{ $user->name }}</span>
        </div>
    </div>
</div>

<!-- Status Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-white">Total Peserta</div>
                        <div class="h4 mb-0 font-weight-bold text-white">{{ $stats['total_users'] }}</div>
                        <small class="text-white-50">Seluruh registrasi</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-white">Pembayaran Lunas</div>
                        <div class="h4 mb-0 font-weight-bold text-white">{{ $stats['paid_users'] }}</div>
                        <small class="text-white-50">Sudah bayar</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-white">Terverifikasi WA</div>
                        <div class="h4 mb-0 font-weight-bold text-white">{{ $stats['verified_users'] }}</div>
                        <small class="text-white-50">WhatsApp OK</small>
                    </div>
                    <div class="col-auto">
                        <i class="fab fa-whatsapp fa-2x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-white">Menunggu Pembayaran</div>
                        <div class="h4 mb-0 font-weight-bold text-white">{{ $stats['pending_users'] }}</div>
                        <small class="text-white-50">Belum bayar</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Summary -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Pendapatan Terkonfirmasi</h6>
            </div>
            <div class="card-body">
                <h3 class="text-success">Rp {{ number_format($totalRevenue['confirmed'], 0, ',', '.') }}</h3>
                <small class="text-muted">Dari {{ $stats['paid_users'] }} peserta</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-header bg-warning text-white">
                <h6 class="mb-0"><i class="fas fa-hourglass-half me-2"></i>Pendapatan Pending</h6>
            </div>
            <div class="card-body">
                <h3 class="text-warning">Rp {{ number_format($totalRevenue['pending'], 0, ',', '.') }}</h3>
                <small class="text-muted">Dari {{ $stats['pending_users'] }} peserta</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Total Potensi Pendapatan</h6>
            </div>
            <div class="card-body">
                <h3 class="text-primary">Rp {{ number_format($totalRevenue['total'], 0, ',', '.') }}</h3>
                <small class="text-muted">Dari {{ $stats['total_users'] }} peserta</small>
            </div>
        </div>
    </div>
</div>

<!-- Category Statistics -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy me-2"></i>Statistik Per Kategori Lomba
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Kategori</th>
                                <th>Jarak</th>
                                <th>Harga</th>
                                <th>Total Peserta</th>
                                <th>Sudah Bayar</th>
                                <th>Belum Bayar</th>
                                <th>Pendapatan Terkonfirmasi</th>
                                <th>Pendapatan Pending</th>
                                <th>Total Potensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categoryStats as $category)
                            <tr>
                                <td>
                                    <strong>{{ $category['name'] }}</strong>
                                    @if(!$category['active'])
                                        <span class="badge bg-secondary ms-2">Nonaktif</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-info">{{ $category['description'] }}</span></td>
                                <td><strong>Rp {{ number_format($category['price'], 0, ',', '.') }}</strong></td>
                                <td>
                                    <span class="badge bg-primary">{{ $category['total_participants'] }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $category['confirmed_participants'] }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ $category['pending_participants'] }}</span>
                                </td>
                                <td class="text-success">
                                    <strong>Rp {{ number_format($category['confirmed_revenue'], 0, ',', '.') }}</strong>
                                </td>
                                <td class="text-warning">
                                    <strong>Rp {{ number_format($category['pending_revenue'], 0, ',', '.') }}</strong>
                                </td>
                                <td class="text-primary">
                                    <strong>Rp {{ number_format($category['total_revenue'], 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada kategori lomba</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="6">TOTAL</th>
                                <th class="text-success">Rp {{ number_format($totalRevenue['confirmed'], 0, ',', '.') }}</th>
                                <th class="text-warning">Rp {{ number_format($totalRevenue['pending'], 0, ',', '.') }}</th>
                                <th class="text-primary">Rp {{ number_format($totalRevenue['total'], 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Registrations and User Status -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Registrasi Terbaru
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Status WA</th>
                                <th>Status Bayar</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $recentUser)
                            <tr>
                                <td>{{ $recentUser->name }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $recentUser->race_category }}</span>
                                </td>
                                <td>
                                    @if($recentUser->whatsapp_verified)
                                        <span class="status-badge status-verified">Verified</span>
                                    @else
                                        <span class="status-badge status-pending">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($recentUser->payment_confirmed)
                                        <span class="status-badge status-paid">Paid</span>
                                    @else
                                        <span class="status-badge status-pending">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $recentUser->created_at ? $recentUser->created_at->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada registrasi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Status Akun Anda
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Informasi Akun</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Nama:</strong></td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        @if($user->race_category)
                        <tr>
                            <td><strong>Kategori:</strong></td>
                            <td><span class="badge bg-info">{{ $user->race_category }}</span></td>
                        </tr>
                        @endif
                    </table>
                </div>
                
                <div class="mb-3">
                    <h6>Status Verifikasi</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>WhatsApp:</span>
                        @if($user->whatsapp_verified)
                            <span class="status-badge status-verified">Verified</span>
                        @else
                            <span class="status-badge status-pending">Pending</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Pembayaran:</span>
                        @if($user->payment_confirmed)
                            <span class="status-badge status-paid">Confirmed</span>
                        @else
                            <span class="status-badge status-pending">Pending</span>
                        @endif
                    </div>
                </div>
                
                @if(!$user->whatsapp_verified || !$user->payment_confirmed)
                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        @if(!$user->whatsapp_verified)
                            Silakan verifikasi WhatsApp Anda.
                        @elseif(!$user->payment_confirmed)
                            Silakan lakukan pembayaran untuk melengkapi registrasi.
                        @endif
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
