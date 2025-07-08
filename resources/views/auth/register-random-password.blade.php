@extends('layouts.app')

@section('title', 'Registrasi dengan Password Otomatis')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Registrasi Amazing Sultra Run
                    </h4>
                    <small class="text-light">Password akan digenerate otomatis dan dikirim via WhatsApp</small>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.random-password') }}" id="registrationForm">
                        @csrf

                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-user me-2"></i>Informasi Pribadi
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="bib_name" class="form-label">Nama di BIB *</label>
                                <input type="text" class="form-control @error('bib_name') is-invalid @enderror" 
                                       id="bib_name" name="bib_name" value="{{ old('bib_name') }}" required>
                                @error('bib_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="whatsapp_number" class="form-label">Nomor WhatsApp *</label>
                                <input type="tel" class="form-control @error('whatsapp_number') is-invalid @enderror" 
                                       id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}" required>
                                <small class="form-text text-muted">Password akan dikirim ke nomor ini</small>
                                @error('whatsapp_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Jenis Kelamin *</label>
                                <select class="form-select @error('gender') is-invalid @enderror" 
                                        id="gender" name="gender" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Type Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-key me-2"></i>Tipe Password
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">Pilih Tipe Password *</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="password_type" 
                                                   id="password_simple" value="simple" {{ old('password_type', 'simple') == 'simple' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="password_simple">
                                                <strong>Sederhana</strong><br>
                                                <small class="text-muted">2 huruf + 4 angka (contoh: ab1234)</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="password_type" 
                                                   id="password_complex" value="complex" {{ old('password_type') == 'complex' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="password_complex">
                                                <strong>Kompleks</strong><br>
                                                <small class="text-muted">8 karakter acak dengan simbol</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="password_type" 
                                                   id="password_memorable" value="memorable" {{ old('password_type') == 'memorable' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="password_memorable">
                                                <strong>Mudah Diingat</strong><br>
                                                <small class="text-muted">Kata + angka (contoh: BrightTiger123)</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Other form fields (birth, address, etc.) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="birth_place" class="form-label">Tempat Lahir *</label>
                                <input type="text" class="form-control @error('birth_place') is-invalid @enderror" 
                                       id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required>
                                @error('birth_place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label">Tanggal Lahir *</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="address" class="form-label">Alamat *</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="jersey_size" class="form-label">Ukuran Jersey *</label>
                                <select class="form-select @error('jersey_size') is-invalid @enderror" 
                                        id="jersey_size" name="jersey_size" required>
                                    <option value="">Pilih Ukuran Jersey</option>
                                    @foreach($jerseySizes as $size)
                                        <option value="{{ $size->size }}" {{ old('jersey_size') == $size->size ? 'selected' : '' }}>
                                            {{ $size->size }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jersey_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="race_category" class="form-label">Kategori Lomba *</label>
                                <select class="form-select @error('race_category') is-invalid @enderror" 
                                        id="race_category" name="race_category" required>
                                    <option value="">Pilih Kategori Lomba</option>
                                    @foreach($raceCategories as $category)
                                        <option value="{{ $category->name }}" 
                                                data-fee="{{ $category->fee }}"
                                                {{ old('race_category') == $category->name ? 'selected' : '' }}>
                                            {{ $category->name }} - Rp {{ number_format($category->fee, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('race_category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Emergency contacts and other fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="emergency_contact_1" class="form-label">Kontak Darurat 1 *</label>
                                <input type="text" class="form-control @error('emergency_contact_1') is-invalid @enderror" 
                                       id="emergency_contact_1" name="emergency_contact_1" value="{{ old('emergency_contact_1') }}" required>
                                @error('emergency_contact_1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="emergency_contact_2" class="form-label">Kontak Darurat 2</label>
                                <input type="text" class="form-control @error('emergency_contact_2') is-invalid @enderror" 
                                       id="emergency_contact_2" name="emergency_contact_2" value="{{ old('emergency_contact_2') }}">
                                @error('emergency_contact_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="blood_type" class="form-label">Golongan Darah *</label>
                                <select class="form-select @error('blood_type') is-invalid @enderror" 
                                        id="blood_type" name="blood_type" required>
                                    <option value="">Pilih Golongan Darah</option>
                                    @foreach($bloodTypes as $bloodType)
                                        <option value="{{ $bloodType->type }}" {{ old('blood_type') == $bloodType->type ? 'selected' : '' }}>
                                            {{ $bloodType->type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('blood_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="occupation" class="form-label">Pekerjaan *</label>
                                <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                       id="occupation" name="occupation" value="{{ old('occupation') }}" required>
                                @error('occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="group_community" class="form-label">Grup/Komunitas</label>
                                <input type="text" class="form-control @error('group_community') is-invalid @enderror" 
                                       id="group_community" name="group_community" value="{{ old('group_community') }}">
                                @error('group_community')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="event_source" class="form-label">Sumber Informasi Event *</label>
                                <select class="form-select @error('event_source') is-invalid @enderror" 
                                        id="event_source" name="event_source" required>
                                    <option value="">Pilih Sumber Informasi</option>
                                    @foreach($eventSources as $source)
                                        <option value="{{ $source->name }}" {{ old('event_source') == $source->name ? 'selected' : '' }}>
                                            {{ $source->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('event_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="medical_history" class="form-label">Riwayat Medis</label>
                                <textarea class="form-control @error('medical_history') is-invalid @enderror" 
                                          id="medical_history" name="medical_history" rows="3" 
                                          placeholder="Ceritakan riwayat medis yang perlu diketahui (opsional)">{{ old('medical_history') }}</textarea>
                                @error('medical_history')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- reCAPTCHA -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    <strong>Verifikasi Keamanan:</strong> Sistem akan memverifikasi bahwa Anda bukan robot.
                                </div>
                                @error('recaptcha')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Daftar dengan Password Otomatis
                                </button>
                                <small class="form-text text-muted mt-2">
                                    Dengan mendaftar, password akan digenerate secara otomatis dan dikirim ke WhatsApp Anda.
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- reCAPTCHA Script -->
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        
        // Execute reCAPTCHA
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'register'}).then(function(token) {
                // Add reCAPTCHA token to form
                const recaptchaInput = document.createElement('input');
                recaptchaInput.type = 'hidden';
                recaptchaInput.name = 'g-recaptcha-response';
                recaptchaInput.value = token;
                form.appendChild(recaptchaInput);
                
                // Submit form
                form.submit();
            }).catch(function(error) {
                console.error('reCAPTCHA error:', error);
                alert('Gagal memverifikasi reCAPTCHA. Silakan coba lagi.');
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Daftar dengan Password Otomatis';
            });
        });
    });
});
</script>
@endpush
@endsection
