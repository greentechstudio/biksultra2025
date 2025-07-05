@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users me-2"></i>
        Users Management
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <span class="badge bg-info fs-6">Total: {{ $users->total() }} users</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Daftar Users
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>WhatsApp</th>
                        <th>Status</th>
                        <th>WhatsApp Verified</th>
                        <th>Payment</th>
                        <th>Tgl Daftar</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            <span class="status-badge status-{{ $user->status }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>
                            @if($user->whatsapp_verified)
                                <i class="fas fa-check-circle text-success" title="Verified{{ $user->whatsapp_verified_at ? ' at ' . $user->whatsapp_verified_at->format('d M Y H:i') : '' }}"></i>
                            @else
                                <i class="fas fa-times-circle text-danger" title="Not verified"></i>
                            @endif
                        </td>
                        <td>
                            @if($user->payment_confirmed)
                                <i class="fas fa-check-circle text-success" title="Paid: Rp {{ number_format($user->payment_amount, 0, ',', '.') }}"></i>
                            @else
                                <i class="fas fa-times-circle text-danger" title="Not paid"></i>
                            @endif
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                @if(!$user->whatsapp_verified)
                                    <button class="btn btn-success btn-sm" onclick="verifyWhatsApp({{ $user->id }})" title="Verify WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </button>
                                @endif
                                
                                @if($user->whatsapp_verified && !$user->payment_confirmed)
                                    <button class="btn btn-primary btn-sm" onclick="confirmPayment({{ $user->id }})" title="Confirm Payment">
                                        <i class="fas fa-credit-card"></i>
                                    </button>
                                @endif
                                
                                <button class="btn btn-info btn-sm" onclick="showUserDetails({{ $user->id }})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            <i class="fas fa-inbox me-2"></i>Tidak ada data user
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function verifyWhatsApp(userId) {
    if(confirm('Apakah Anda yakin ingin memverifikasi WhatsApp user ini?')) {
        fetch('/api/verify-whatsapp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: userId
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat verifikasi WhatsApp');
        });
    }
}

function confirmPayment(userId) {
    const amount = prompt('Masukkan jumlah pembayaran (default: 100000):', '100000');
    if(amount !== null) {
        const notes = prompt('Catatan pembayaran (opsional):', '');
        
        fetch('/api/confirm-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: userId,
                amount: parseInt(amount),
                method: 'admin_confirmation',
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat konfirmasi pembayaran');
        });
    }
}

function showUserDetails(userId) {
    // In a real application, you would fetch user details via API
    // For now, we'll show a simple message
    document.getElementById('userDetailsContent').innerHTML = `
        <p>User ID: ${userId}</p>
        <p>Fitur detail user akan segera tersedia...</p>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
    modal.show();
}
</script>
@endpush
@endsection
