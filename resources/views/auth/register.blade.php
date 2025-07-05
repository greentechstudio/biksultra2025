@extends('layouts.guest')

@section('title', 'Register - Event Lari')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h3><i class="fas fa-running me-2"></i>Registrasi Event Lari</h3>
        <p class="mb-0">Daftar untuk mengikuti event lari</p>
    </div>
    
    <div class="auth-body">
        <form id="registrationForm" method="POST" action="{{ route('register') }}">
            @csrf
            
            <!-- Basic Information -->
            <div class="section-header">
                <h5><i class="fas fa-user me-2"></i>Informasi Pribadi</h5>
            </div>
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="birth_place" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('birth_place') is-invalid @enderror" 
                           id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required>
                    @error('birth_place')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                           id="birth_date" name="birth_date" value="{{ old('birth_date') }}" 
                           max="{{ date('Y-m-d', strtotime('-10 years')) }}" required>
                    @error('birth_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Minimal umur 10 tahun</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                <textarea class="form-control @error('address') is-invalid @enderror" 
                          id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Race Information -->
            <div class="section-header">
                <h5><i class="fas fa-trophy me-2"></i>Informasi Lomba</h5>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="race_category" class="form-label">Kategori Lomba <span class="text-danger">*</span></label>
                    <select class="form-control @error('race_category') is-invalid @enderror" id="race_category" name="race_category" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($raceCategories as $category)
                            <option value="{{ $category->name }}" {{ old('race_category') == $category->name ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->description }}) - Rp {{ number_format($category->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('race_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="jersey_size" class="form-label">Ukuran Jersey <span class="text-danger">*</span></label>
                    <select class="form-control @error('jersey_size') is-invalid @enderror" id="jersey_size" name="jersey_size" required>
                        <option value="">Pilih Ukuran</option>
                        @foreach($jerseySizes as $size)
                            <option value="{{ $size->code }}" {{ old('jersey_size') == $size->code ? 'selected' : '' }}>
                                {{ $size->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('jersey_size')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="bib_name" class="form-label">Nama BIB <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('bib_name') is-invalid @enderror" 
                       id="bib_name" name="bib_name" value="{{ old('bib_name') }}" 
                       placeholder="Nama yang akan tercetak di BIB" maxlength="20" required>
                @error('bib_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>
                    Nama yang akan dicetak di nomor BIB Anda (maksimal 20 karakter)
                </div>
            </div>

            <!-- Contact Information -->
            <div class="section-header">
                <h5><i class="fas fa-phone me-2"></i>Informasi Kontak</h5>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="whatsapp_number" class="form-label">
                    No Kontak WhatsApp <span class="text-danger">*</span>
                    <small class="text-muted">(akan divalidasi otomatis)</small>
                </label>
                <div class="input-group">
                    <span class="input-group-text">+62</span>
                    <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" 
                           id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}" 
                           placeholder="81234567890" required>
                </div>
                @error('whatsapp_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>
                    Masukkan nomor tanpa awalan 0 atau +62. Contoh: 81234567890 (awalan akan dihapus otomatis)
                </div>
                <!-- WhatsApp validation status will be inserted here by JavaScript -->
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Nomor HP Alternatif</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                       id="phone" name="phone" value="{{ old('phone') }}" 
                       placeholder="Contoh: 081234567890">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="emergency_contact_1" class="form-label">Kontak Darurat 1 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('emergency_contact_1') is-invalid @enderror" 
                           id="emergency_contact_1" name="emergency_contact_1" value="{{ old('emergency_contact_1') }}" 
                           placeholder="Nama & No HP" required>
                    @error('emergency_contact_1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="emergency_contact_2" class="form-label">Kontak Darurat 2</label>
                    <input type="text" class="form-control @error('emergency_contact_2') is-invalid @enderror" 
                           id="emergency_contact_2" name="emergency_contact_2" value="{{ old('emergency_contact_2') }}" 
                           placeholder="Nama & No HP">
                    @error('emergency_contact_2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Additional Information -->
            <div class="section-header">
                <h5><i class="fas fa-info-circle me-2"></i>Informasi Tambahan</h5>
            </div>

            <div class="mb-3">
                <label for="group_community" class="form-label">Group Lari/Komunitas/Instansi</label>
                <input type="text" class="form-control @error('group_community') is-invalid @enderror" 
                       id="group_community" name="group_community" value="{{ old('group_community') }}" 
                       placeholder="Nama komunitas/instansi (opsional)">
                @error('group_community')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="blood_type" class="form-label">Golongan Darah <span class="text-danger">*</span></label>
                    <select class="form-control @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type" required>
                        <option value="">Pilih Golongan Darah</option>
                        @foreach($bloodTypes as $type)
                            <option value="{{ $type->name }}" {{ old('blood_type') == $type->name ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('blood_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="occupation" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                           id="occupation" name="occupation" value="{{ old('occupation') }}" required>
                    @error('occupation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="medical_history" class="form-label">Riwayat Penyakit</label>
                <textarea class="form-control @error('medical_history') is-invalid @enderror" 
                          id="medical_history" name="medical_history" rows="3" 
                          placeholder="Sebutkan riwayat penyakit yang relevan (opsional)">{{ old('medical_history') }}</textarea>
                @error('medical_history')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="event_source" class="form-label">Tau Event Ini Darimana? <span class="text-danger">*</span></label>
                <select class="form-control @error('event_source') is-invalid @enderror" id="event_source" name="event_source" required>
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

            <!-- Account Information -->
            <div class="section-header">
                <h5><i class="fas fa-key me-2"></i>Informasi Akun</h5>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                <div class="form-text">Minimum 8 karakter</div>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-running me-2"></i>Daftar Event Lari
                </button>
            </div>
            
            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Setelah registrasi, Anda akan diarahkan ke WhatsApp untuk konfirmasi dan pembayaran
                </small>
            </div>
            
            <div class="text-center mt-2">
                <p class="mb-0">Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
                </p>
            </div>
        </form>
    </div>
</div>

<style>
.section-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 15px;
    margin: 20px -20px 15px -20px;
    border-radius: 8px;
}

.section-header h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}

.text-danger {
    color: #dc3545 !important;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 12px 20px;
    font-weight: 600;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.auth-card {
    max-width: 800px;
    margin: 0 auto;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="text"][placeholder*="081"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Remove non-numeric characters
            let value = this.value.replace(/\D/g, '');
            
            // Limit to 15 digits
            if (value.length > 15) {
                value = value.substring(0, 15);
            }
            
            // Add formatting if starts with 0
            if (value.startsWith('0')) {
                this.value = value;
            } else if (value.startsWith('62')) {
                this.value = value;
            } else if (value.length > 0) {
                this.value = '0' + value;
            }
        });
    });

    // Form validation on submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let hasError = false;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                hasError = true;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Birth date validation
        const birthDate = document.getElementById('birth_date');
        if (birthDate.value) {
            const today = new Date();
            const birth = new Date(birthDate.value);
            const age = Math.floor((today - birth) / (365.25 * 24 * 60 * 60 * 1000));
            
            if (age < 10) {
                birthDate.classList.add('is-invalid');
                hasError = true;
                alert('Minimal umur 10 tahun untuk mengikuti event ini.');
            }
        }

        if (hasError) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi.');
        }
    });

    // Real-time validation
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });

    // WhatsApp number validation
    const whatsappInput = document.getElementById('whatsapp_number');
    const whatsappStatus = document.createElement('div');
    whatsappStatus.className = 'mt-2';
    whatsappInput.parentNode.appendChild(whatsappStatus);

    let validationTimeout;
    let lastValidatedNumber = '';
    let isValidWhatsApp = false;

    whatsappInput.addEventListener('input', function() {
        clearTimeout(validationTimeout);
        let phoneNumber = this.value.trim();
        
        // Auto-remove leading 0 or +62
        if (phoneNumber.startsWith('0')) {
            phoneNumber = phoneNumber.substring(1);
            this.value = phoneNumber;
        } else if (phoneNumber.startsWith('+62')) {
            phoneNumber = phoneNumber.substring(3);
            this.value = phoneNumber;
        } else if (phoneNumber.startsWith('62')) {
            phoneNumber = phoneNumber.substring(2);
            this.value = phoneNumber;
        }
        
        // Only allow numeric input
        phoneNumber = phoneNumber.replace(/\D/g, '');
        this.value = phoneNumber;
        
        // Reset validation state
        this.classList.remove('is-valid', 'is-invalid');
        isValidWhatsApp = false;
        
        if (phoneNumber.length < 9) {
            whatsappStatus.innerHTML = '';
            return;
        }

        if (phoneNumber === lastValidatedNumber) {
            return; // Already validated this number
        }

        // Show loading
        whatsappStatus.innerHTML = `
            <div class="text-info">
                <i class="fas fa-spinner fa-spin me-1"></i>
                Memeriksa nomor WhatsApp...
            </div>
        `;

        // Delay validation to avoid too many requests
        validationTimeout = setTimeout(() => {
            validateWhatsAppNumber(phoneNumber);
        }, 1500);
    });

    // Handle paste event to clean up pasted content
    whatsappInput.addEventListener('paste', function(e) {
        setTimeout(() => {
            let phoneNumber = this.value.trim();
            
            // Auto-remove leading 0 or +62 from pasted content
            if (phoneNumber.startsWith('0')) {
                phoneNumber = phoneNumber.substring(1);
            } else if (phoneNumber.startsWith('+62')) {
                phoneNumber = phoneNumber.substring(3);
            } else if (phoneNumber.startsWith('62')) {
                phoneNumber = phoneNumber.substring(2);
            }
            
            // Only allow numeric input
            phoneNumber = phoneNumber.replace(/\D/g, '');
            this.value = phoneNumber;
        }, 10);
    });

    async function validateWhatsAppNumber(phoneNumber) {
        try {
            // Format phone number (phoneNumber sudah dibersihkan dari 0 dan +62)
            let formattedNumber = phoneNumber.replace(/\D/g, ''); // Remove non-digits
            
            // Add 62 prefix for Indonesia
            if (formattedNumber.startsWith('8')) {
                formattedNumber = '62' + formattedNumber;
            } else if (!formattedNumber.startsWith('62')) {
                formattedNumber = '62' + formattedNumber;
            }

            console.log('Validating WhatsApp number:', formattedNumber);

            const response = await fetch('/api/check-whatsapp-number', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    number: formattedNumber
                })
            });

            if (!response.ok) {
                console.error(`HTTP error! status: ${response.status}`);
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            lastValidatedNumber = phoneNumber;

            console.log('WhatsApp validation result:', result);
            console.log('Raw API response in result:', result.raw_response);

            if (result.success) {
                if (result.hasWhatsapp) {
                    whatsappStatus.innerHTML = `
                        <div class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            ${result.message || 'Nomor WhatsApp valid dan aktif'}
                        </div>
                    `;
                    whatsappInput.classList.remove('is-invalid');
                    whatsappInput.classList.add('is-valid');
                    isValidWhatsApp = true;
                } else {
                    whatsappStatus.innerHTML = `
                        <div class="text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            ${result.message || 'Nomor tidak terdaftar di WhatsApp'}
                        </div>
                    `;
                    whatsappInput.classList.remove('is-valid');
                    whatsappInput.classList.add('is-invalid');
                    isValidWhatsApp = false;
                }
            } else {
                whatsappStatus.innerHTML = `
                    <div class="text-danger">
                        <i class="fas fa-times-circle me-1"></i>
                        ${result.message || 'Gagal memverifikasi nomor'}
                    </div>
                `;
                isValidWhatsApp = false;
            }
        } catch (error) {
            console.error('WhatsApp validation error:', error);
            whatsappStatus.innerHTML = `
                <div class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Tidak dapat memverifikasi nomor saat ini. Anda tetap dapat melanjutkan pendaftaran.
                </div>
            `;
            isValidWhatsApp = null; // Allow form submission despite validation failure
        }
    }

    // Add form validation before submit
    const registrationForm = document.getElementById('registrationForm');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(e) {
            const whatsappNumber = whatsappInput.value.trim();
            
            if (whatsappNumber && isValidWhatsApp === false) {
                e.preventDefault();
                alert('Mohon pastikan nomor WhatsApp yang Anda masukkan valid dan terdaftar di WhatsApp.');
                whatsappInput.focus();
                return false;
            }
            
            // Additional validation can be added here
            return true;
        });
    }

});
</script>
@endsection
