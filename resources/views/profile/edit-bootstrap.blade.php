@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>Edit Profil
                    </h4>
                    <p class="text-muted mb-0 mt-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Penting:</strong> Edit profil hanya dapat dilakukan <strong>satu kali</strong>. 
                        Pastikan semua data sudah benar sebelum menyimpan.
                    </p>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Terdapat kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" id="editProfileForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Data Pribadi -->
                            <div class="col-md-6">
                                <h5 class="mb-3">
                                    <i class="fas fa-user me-2 text-primary"></i>Data Pribadi
                                </h5>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control bg-light text-muted @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required readonly>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-lock me-1"></i>
                                        Email tidak dapat diubah
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                           placeholder="Contoh: 081234567890">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="whatsapp_number" class="form-label">No. WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control bg-light text-muted @error('whatsapp_number') is-invalid @enderror" 
                                           id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}" 
                                           required readonly placeholder="Contoh: 6281234567890">
                                    @error('whatsapp_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-lock me-1"></i>
                                        Nomor WhatsApp tidak dapat diubah
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('gender', $user->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('gender', $user->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="birth_place" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('birth_place') is-invalid @enderror" 
                                           id="birth_place" name="birth_place" value="{{ old('birth_place', $user->birth_place) }}" required>
                                    @error('birth_place')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                           id="birth_date" name="birth_date" 
                                           value="{{ old('birth_date', $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') : '') }}" 
                                           required max="{{ now()->subYears(10)->format('Y-m-d') }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3" required>{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="occupation" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                           id="occupation" name="occupation" value="{{ old('occupation', $user->occupation) }}" required>
                                    @error('occupation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="blood_type" class="form-label">Golongan Darah <span class="text-danger">*</span></label>
                                    <select class="form-select @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type" required>
                                        <option value="">Pilih Golongan Darah</option>
                                        @foreach($bloodTypes as $type)
                                            <option value="{{ $type->name }}" {{ old('blood_type', $user->blood_type) == $type->name ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('blood_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Data Perlombaan & Lainnya -->
                            <div class="col-md-6">
                                <h5 class="mb-3">
                                    <i class="fas fa-running me-2 text-success"></i>Data Perlombaan
                                </h5>

                                <div class="mb-3">
                                    <label for="race_category" class="form-label">Kategori Lomba <span class="text-danger">*</span></label>
                                    <select class="form-select bg-light text-muted @error('race_category') is-invalid @enderror" id="race_category" name="race_category" required disabled>
                                        <option value="">Pilih Kategori Lomba</option>
                                        @if(isset($raceCategories))
                                            @foreach($raceCategories as $category)
                                                <option value="{{ $category->name }}" 
                                                        data-price="{{ $category->price }}"
                                                        {{ old('race_category', $user->race_category) == $category->name ? 'selected' : '' }}>
                                                    {{ $category->name }} - Rp {{ number_format($category->price, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="{{ $user->race_category }}" selected>{{ $user->race_category }}</option>
                                        @endif
                                    </select>
                                    <!-- Hidden input to ensure value is submitted -->
                                    <input type="hidden" name="race_category" value="{{ $user->race_category }}">
                                    @error('race_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-lock me-1"></i>
                                        Kategori lomba tidak dapat diubah
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="bib_name" class="form-label">Nama BIB <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('bib_name') is-invalid @enderror" 
                                           id="bib_name" name="bib_name" value="{{ old('bib_name', $user->bib_name) }}" 
                                           placeholder="Nama yang akan tercetak di BIB" maxlength="20" required>
                                    @error('bib_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Nama yang akan dicetak di nomor BIB Anda (maksimal 20 karakter)
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="jersey_size" class="form-label">Ukuran Jersey <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jersey_size') is-invalid @enderror" id="jersey_size" name="jersey_size" required>
                                        <option value="">Pilih Ukuran Jersey</option>
                                        @if(isset($jerseySizes) && count($jerseySizes) > 0)
                                            @foreach($jerseySizes as $size)
                                                <option value="{{ $size->size }}" {{ old('jersey_size', $user->jersey_size) == $size->size ? 'selected' : '' }}>
                                                    {{ $size->size }} ({{ $size->description }})
                                                </option>
                                            @endforeach
                                        @else
                                            <!-- Fallback jersey sizes if not provided by controller -->
                                            <option value="S" {{ old('jersey_size', $user->jersey_size) == 'S' ? 'selected' : '' }}>S</option>
                                            <option value="M" {{ old('jersey_size', $user->jersey_size) == 'M' ? 'selected' : '' }}>M</option>
                                            <option value="L" {{ old('jersey_size', $user->jersey_size) == 'L' ? 'selected' : '' }}>L</option>
                                            <option value="XL" {{ old('jersey_size', $user->jersey_size) == 'XL' ? 'selected' : '' }}>XL</option>
                                            <option value="XXL" {{ old('jersey_size', $user->jersey_size) == 'XXL' ? 'selected' : '' }}>XXL</option>
                                        @endif
                                    </select>
                                    @error('jersey_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="group_community" class="form-label">Grup/Komunitas</label>
                                    <input type="text" class="form-control @error('group_community') is-invalid @enderror" 
                                           id="group_community" name="group_community" value="{{ old('group_community', $user->group_community) }}" 
                                           placeholder="Nama grup lari atau komunitas (opsional)">
                                    @error('group_community')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="event_source" class="form-label">Sumber Info Event <span class="text-danger">*</span></label>
                                    <select class="form-select @error('event_source') is-invalid @enderror" id="event_source" name="event_source" required>
                                        <option value="">Pilih Sumber Info</option>
                                        @foreach($eventSources as $source)
                                            <option value="{{ $source->name }}" {{ old('event_source', $user->event_source) == $source->name ? 'selected' : '' }}>
                                                {{ $source->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <h5 class="mb-3 mt-4">
                                    <i class="fas fa-phone-alt me-2 text-danger"></i>Kontak Darurat
                                </h5>

                                <div class="mb-3">
                                    <label for="emergency_contact_name" class="form-label">Nama Kontak Darurat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                           id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}" 
                                           required placeholder="Nama kontak darurat">
                                    @error('emergency_contact_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="emergency_contact_phone" class="form-label">Nomor Kontak Darurat <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                           id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}" 
                                           required placeholder="Nomor telepon kontak darurat">
                                    @error('emergency_contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="medical_history" class="form-label">Riwayat Medis</label>
                                    <textarea class="form-control @error('medical_history') is-invalid @enderror" 
                                              id="medical_history" name="medical_history" rows="3" 
                                              placeholder="Riwayat penyakit, alergi, atau kondisi medis lainnya (opsional)">{{ old('medical_history', $user->medical_history) }}</textarea>
                                    @error('medical_history')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <h5 class="mb-3 mt-4">
                                    <i class="fas fa-lock me-2 text-warning"></i>Ubah Password (Opsional)
                                </h5>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        <!-- Alasan Edit -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-warning bg-opacity-10 border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-clipboard-list me-2"></i>Alasan Edit Profil <span class="text-danger">*</span>
                                        </h6>
                                        <div class="mb-3">
                                            <textarea class="form-control @error('edit_reason') is-invalid @enderror" 
                                                      id="edit_reason" name="edit_reason" rows="3" required
                                                      placeholder="Jelaskan alasan Anda mengedit profil (wajib diisi)">{{ old('edit_reason') }}</textarea>
                                            @error('edit_reason')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-warning" id="submitBtn">
                                        <i class="fas fa-save me-1"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Edit Profil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Perhatian!</strong> Edit profil hanya dapat dilakukan <strong>satu kali</strong>. 
                    Setelah Anda menyimpan perubahan, Anda tidak akan dapat mengedit profil lagi.
                </div>
                <p>Apakah Anda yakin ingin menyimpan semua perubahan?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-warning" id="confirmSubmit">
                    <i class="fas fa-save me-1"></i>Ya, Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editProfileForm');
    const submitBtn = document.getElementById('submitBtn');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const confirmSubmit = document.getElementById('confirmSubmit');

    // Handle form submission dengan konfirmasi
    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Validasi form terlebih dahulu
        if (form.checkValidity()) {
            confirmModal.show();
        } else {
            form.reportValidity();
        }
    });

    // Handle konfirmasi submit
    confirmSubmit.addEventListener('click', function() {
        confirmModal.hide();
        form.submit();
    });

    // Auto-format WhatsApp number
    const whatsappInput = document.getElementById('whatsapp_number');
    whatsappInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // Remove non-digits
        
        // Add country code if not present
        if (value.length > 0 && !value.startsWith('62')) {
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            } else if (value.startsWith('8')) {
                value = '62' + value;
            }
        }
        
        this.value = value;
    });

    // Show price info when race category changes
    const raceCategorySelect = document.getElementById('race_category');
    raceCategorySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        
        // Remove existing price info
        const existingInfo = this.parentNode.querySelector('.price-info');
        if (existingInfo) {
            existingInfo.remove();
        }
        
        // Add new price info if category selected
        if (price) {
            const priceInfo = document.createElement('small');
            priceInfo.className = 'text-muted price-info';
            priceInfo.innerHTML = `<i class="fas fa-info-circle me-1"></i>Biaya registrasi: Rp ${parseInt(price).toLocaleString('id-ID')}`;
            this.parentNode.appendChild(priceInfo);
        }
    });

    // Trigger price info on page load if category already selected
    if (raceCategorySelect.value) {
        raceCategorySelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
