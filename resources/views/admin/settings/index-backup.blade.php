@extends('layouts.admin')

@section('title', 'Admin Settings')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-cogs mr-2"></i>Admin Settings
            </h1>
            <p class="mt-2 text-sm text-gray-700">Manage system settings and configurations</p>
        </div>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Dashboard</span>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <span class="ml-4 text-sm font-medium text-gray-500">Settings</span>
                    </div>
                </li>
    </div>

    @if(session('success'))
        <div class="mt-4 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Jersey Sizes Section -->
    <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-tshirt mr-2"></i>Jersey Sizes
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage available jersey sizes for registration</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <!-- Add Jersey Size Form -->
            <form action="{{ route('admin.settings.jersey-sizes.store') }}" method="POST" class="mb-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <input type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror" 
                               name="name" placeholder="Name (e.g., Extra Small)" value="{{ old('name') }}" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <input type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('code') border-red-300 @enderror" 
                               name="code" placeholder="Code (e.g., XS)" value="{{ old('code') }}" required>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-1"></i>Add
                        </button>
                    </div>
                </div>
            </form>

            <!-- Jersey Sizes List -->
            <div class="mt-6 overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($jerseySizes as $size)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $size->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $size->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $size->active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $size->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900" onclick="openEditModal('jersey', {{ $size->id }}, '{{ $size->name }}', '{{ $size->code }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.settings.jersey-sizes.delete', $size) }}" 
                                          method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
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
