@extends('layouts.app')

@section('title', 'Pengaturan Admin')

@push('styles')
<style>
/* Fix untuk modal backdrop duplikat */
.modal-backdrop.show {
    opacity: 0.5 !important;
}

/* Pastikan hanya ada satu backdrop */
body:has(.modal.show) .modal-backdrop:not(:last-child) {
    display: none !important;
}

/* Fix untuk multiple modal-open class */
body.modal-open {
    padding-right: 0 !important;
}

/* Animasi yang lebih smooth */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
}

/* Prevent modal stack */
.modal {
    z-index: 1050;
}

.modal-backdrop {
    z-index: 1040;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-cogs me-2"></i>Pengaturan Admin</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengaturan</li>
                    </ol>
                </nav>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Jersey Sizes Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-tshirt me-2"></i>Ukuran Jersey</h5>
                </div>
                <div class="card-body">
                    <!-- Add Jersey Size Form -->
                    <form action="{{ route('admin.settings.jersey-sizes.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" placeholder="Nama (contoh: Extra Small)" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       name="code" placeholder="Kode (contoh: XS)" value="{{ old('code') }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Tambah
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Jersey Sizes List -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jerseySizes as $size)
                                <tr>
                                    <td>{{ $size->name }}</td>
                                    <td><span class="badge bg-primary">{{ $size->code }}</span></td>
                                    <td>
                                        <span class="badge {{ $size->active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $size->active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                                data-bs-target="#editJerseyModal{{ $size->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.settings.jersey-sizes.delete', $size) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal for Jersey Size -->
                                <div class="modal fade" id="editJerseyModal{{ $size->id }}" tabindex="-1" 
                                     data-bs-backdrop="static" data-bs-keyboard="false" 
                                     aria-labelledby="editJerseyModalLabel{{ $size->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Ukuran Jersey</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.settings.jersey-sizes.update', $size) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama</label>
                                                        <input type="text" class="form-control" name="name" 
                                                               value="{{ $size->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Kode</label>
                                                        <input type="text" class="form-control" name="code" 
                                                               value="{{ $size->code }}" required>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="active" 
                                                               {{ $size->active ? 'checked' : '' }}>
                                                        <label class="form-check-label">Aktif</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Race Categories Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-trophy me-2"></i>Kategori Lomba</h5>
                </div>
                <div class="card-body">
                    <!-- Add Race Category Form -->
                    <form action="{{ route('admin.settings.race-categories.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" placeholder="Nama (contoh: 5K Fun Run)" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control @error('description') is-invalid @enderror" 
                                       name="description" placeholder="Jarak (contoh: 5K)" value="{{ old('description') }}" required>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       name="price" placeholder="Harga" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Tambah
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Race Categories List -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jarak</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($raceCategories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td><span class="badge bg-info">{{ $category->description }}</span></td>
                                    <td>Rp {{ number_format($category->price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $category->active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $category->active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                                data-bs-target="#editCategoryModal{{ $category->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.settings.race-categories.delete', $category) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal for Race Category -->
                                <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" 
                                     data-bs-backdrop="static" data-bs-keyboard="false" 
                                     aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Kategori Lomba</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.settings.race-categories.update', $category) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama</label>
                                                        <input type="text" class="form-control" name="name" 
                                                               value="{{ $category->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Jarak</label>
                                                        <input type="text" class="form-control" name="description" 
                                                               value="{{ $category->description }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Harga</label>
                                                        <input type="number" class="form-control" name="price" 
                                                               value="{{ $category->price }}" required>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="active" 
                                                               {{ $category->active ? 'checked' : '' }}>
                                                        <label class="form-check-label">Aktif</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Blood Types Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-heartbeat me-2"></i>Golongan Darah</h5>
                </div>
                <div class="card-body">
                    <!-- Add Blood Type Form -->
                    <form action="{{ route('admin.settings.blood-types.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" placeholder="Golongan Darah (contoh: A+)" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Tambah
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Blood Types List -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Golongan Darah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bloodTypes as $type)
                                <tr>
                                    <td><span class="badge bg-danger">{{ $type->name }}</span></td>
                                    <td>
                                        <span class="badge {{ $type->active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $type->active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                                data-bs-target="#editBloodTypeModal{{ $type->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.settings.blood-types.delete', $type) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal for Blood Type -->
                                <div class="modal fade" id="editBloodTypeModal{{ $type->id }}" tabindex="-1" 
                                     data-bs-backdrop="static" data-bs-keyboard="false" 
                                     aria-labelledby="editBloodTypeModalLabel{{ $type->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Golongan Darah</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.settings.blood-types.update', $type) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Golongan Darah</label>
                                                        <input type="text" class="form-control" name="name" 
                                                               value="{{ $type->name }}" required>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="active" 
                                                               {{ $type->active ? 'checked' : '' }}>
                                                        <label class="form-check-label">Aktif</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Event Sources Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2"></i>Sumber Informasi Event</h5>
                </div>
                <div class="card-body">
                    <!-- Add Event Source Form -->
                    <form action="{{ route('admin.settings.event-sources.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" placeholder="Nama (contoh: Instagram)" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control @error('description') is-invalid @enderror" 
                                       name="description" placeholder="Deskripsi (opsional)" value="{{ old('description') }}">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Tambah
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Event Sources List -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($eventSources as $source)
                                <tr>
                                    <td>{{ $source->name }}</td>
                                    <td>{{ $source->description ?: '-' }}</td>
                                    <td>
                                        <span class="badge {{ $source->active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $source->active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                                data-bs-target="#editEventSourceModal{{ $source->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.settings.event-sources.delete', $source) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal for Event Source -->
                                <div class="modal fade" id="editEventSourceModal{{ $source->id }}" tabindex="-1" 
                                     data-bs-backdrop="static" data-bs-keyboard="false" 
                                     aria-labelledby="editEventSourceModalLabel{{ $source->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Sumber Informasi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.settings.event-sources.update', $source) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama</label>
                                                        <input type="text" class="form-control" name="name" 
                                                               value="{{ $source->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Deskripsi</label>
                                                        <input type="text" class="form-control" name="description" 
                                                               value="{{ $source->description }}">
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="active" 
                                                               {{ $source->active ? 'checked' : '' }}>
                                                        <label class="form-check-label">Aktif</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fix untuk modal backdrop duplikat
    const cleanupModal = function() {
        // Remove any existing modal backdrops
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => {
            if (backdrop.parentNode) {
                backdrop.parentNode.removeChild(backdrop);
            }
        });
        
        // Remove modal-open class from body
        document.body.classList.remove('modal-open');
        document.body.style.paddingRight = '';
        document.body.style.overflow = '';
    };

    // Event listener untuk semua modal
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function() {
            // Tutup modal lain yang mungkin masih terbuka
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(openModal => {
                if (openModal !== modal) {
                    const bsModal = bootstrap.Modal.getInstance(openModal);
                    if (bsModal) {
                        bsModal.hide();
                    }
                }
            });
            
            // Cleanup backdrop sebelum membuka modal baru
            setTimeout(cleanupModal, 100);
        });

        modal.addEventListener('hidden.bs.modal', function() {
            // Cleanup setelah modal ditutup
            setTimeout(cleanupModal, 100);
        });
    });

    // Event listener untuk tombol close dan backdrop
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop') || 
            e.target.classList.contains('btn-close') ||
            e.target.closest('.btn-close')) {
            cleanupModal();
        }
    });

    // Cleanup saat ESC ditekan
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cleanupModal();
        }
    });

    // Fix untuk form delete confirmation
    const deleteforms = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteforms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Tutup modal yang terbuka
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                const bsModal = bootstrap.Modal.getInstance(openModal);
                if (bsModal) {
                    bsModal.hide();
                }
            }
            
            // Cleanup backdrop
            cleanupModal();
            
            // Tampilkan konfirmasi delete
            if (confirm('Yakin ingin menghapus data ini?')) {
                this.submit();
            }
        });
        
        // Remove inline onsubmit
        form.removeAttribute('onsubmit');
    });
});
</script>
@endpush
