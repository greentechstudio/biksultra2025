@extends('layouts.guest')

@section('title', 'Register - Event Lari')

@section('content')
<div class="form-container">
    <div class="w-full max-w-4xl mx-auto">
        <div class="glass-effect rounded-2xl overflow-hidden form-card">
            <!-- Header -->
            <div class="custom-gradient-header text-white p-6 text-center">
                <h1 class="text-3xl font-bold mb-2">
                    <i class="fas fa-running mr-3"></i>Registrasi Event Lari
                </h1>
                <p class="text-gray-200">Daftar untuk mengikuti event lari</p>
            </div>
            
            <!-- Form Content -->
            <div class="p-8">
                <form id="registrationForm" method="POST" action="{{ route('register') }}" class="space-y-8">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center mb-6">
                            <div class="bg-red-100 rounded-full p-3 mr-4">
                                <i class="fas fa-user text-custom-red"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Informasi Pribadi</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('name') border-red-500 @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('gender') border-red-500 @enderror" 
                                        id="gender" name="gender" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-2">
                                Tempat Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('birth_place') border-red-500 @enderror" 
                                   id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required>
                            @error('birth_place')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('birth_date') border-red-500 @enderror" 
                                   id="birth_date" name="birth_date" value="{{ old('birth_date') }}" 
                                   max="{{ date('Y-m-d', strtotime('-10 years')) }}" required>
                            @error('birth_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-600">Minimal umur 10 tahun</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('address') border-red-500 @enderror" 
                                  id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Race Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-yellow-100 rounded-full p-3 mr-4">
                            <i class="fas fa-trophy text-yellow-600"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Lomba</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="race_category" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori Lomba <span class="text-red-500">*</span>
                            </label>
                            <select class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('race_category') border-red-500 @enderror" 
                                    id="race_category" name="race_category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="5K" {{ old('race_category') == '5K' ? 'selected' : '' }}>5K </option>
                                <option value="10K" {{ old('race_category') == '10K' ? 'selected' : '' }}>10K </option>
                                <option value="21K" {{ old('race_category') == '21K' ? 'selected' : '' }}>21K - Half Marathon</option>
                            </select>
                            @error('race_category')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jersey_size" class="block text-sm font-medium text-gray-700 mb-2">
                                Ukuran Jersey <span class="text-red-500">*</span>
                            </label>
                            <select class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('jersey_size') border-red-500 @enderror" 
                                    id="jersey_size" name="jersey_size" required>
                                <option value="">Pilih Ukuran</option>
                                <option value="S" {{ old('jersey_size') == 'S' ? 'selected' : '' }}>S</option>
                                <option value="M" {{ old('jersey_size') == 'M' ? 'selected' : '' }}>M</option>
                                <option value="L" {{ old('jersey_size') == 'L' ? 'selected' : '' }}>L</option>
                                <option value="XL" {{ old('jersey_size') == 'XL' ? 'selected' : '' }}>XL</option>
                                <option value="XXL" {{ old('jersey_size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                            </select>
                            @error('jersey_size')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="bib_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama BIB <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('bib_name') border-red-500 @enderror" 
                               id="bib_name" name="bib_name" value="{{ old('bib_name') }}" 
                               placeholder="Nama yang akan tercetak di BIB" maxlength="20" required>
                        @error('bib_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Nama yang akan dicetak di nomor BIB Anda (maksimal 20 karakter)
                        </p>
                    </div>
                </div>

                <!-- Ticket Type Information -->
                <div class="bg-blue-50 rounded-lg p-6 hidden" id="ticketInfo">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 rounded-full p-3 mr-4">
                            <i class="fas fa-ticket-alt text-blue-600"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Tiket</h2>
                    </div>
                    
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="ticket-type-info">
                                    <h3 class="text-lg font-semibold text-gray-800 ticket-type-name">-</h3>
                                    <p class="text-2xl font-bold text-green-600 ticket-price">Rp 0</p>
                                    <div class="ticket-quota">
                                        <small class="text-gray-600">Kuota tersisa: <span class="remaining-quota font-semibold">-</span></small>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="countdown-timer">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Sisa Waktu:</h3>
                                    <div class="timer-display flex space-x-1">
                                        <div class="flex flex-col items-center">
                                            <span class="bg-red-500 text-white px-2 py-1 rounded timer-days">0</span>
                                            <span class="text-xs text-gray-600">hari</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="bg-red-500 text-white px-2 py-1 rounded timer-hours">0</span>
                                            <span class="text-xs text-gray-600">jam</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="bg-red-500 text-white px-2 py-1 rounded timer-minutes">0</span>
                                            <span class="text-xs text-gray-600">menit</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="bg-red-500 text-white px-2 py-1 rounded timer-seconds">0</span>
                                            <span class="text-xs text-gray-600">detik</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <i class="fas fa-phone text-green-600"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Kontak</h2>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('email') border-red-500 @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">
                                No Kontak WhatsApp <span class="text-red-500">*</span>
                                <small class="text-gray-500">(akan divalidasi otomatis)</small>
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-lg">
                                    +62
                                </span>
                                <input type="text" 
                                       class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-r-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('whatsapp_number') border-red-500 @enderror" 
                                       id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}" 
                                       placeholder="8114000805" required>
                            </div>
                            @error('whatsapp_number')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Masukkan nomor tanpa awalan 0 atau +62. Contoh: 8114000805 (awalan akan dihapus otomatis)
                            </p>
                            <!-- WhatsApp validation status -->
                            <div id="whatsapp-validation-status" class="mt-2"></div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor HP Alternatif
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('phone') border-red-500 @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="Contoh: 081234567890">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="emergency_contact_1" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kontak Darurat 1 <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('emergency_contact_1') border-red-500 @enderror" 
                                       id="emergency_contact_1" name="emergency_contact_1" value="{{ old('emergency_contact_1') }}" 
                                       placeholder="Nama & No HP" required>
                                @error('emergency_contact_1')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="emergency_contact_2" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kontak Darurat 2
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('emergency_contact_2') border-red-500 @enderror" 
                                       id="emergency_contact_2" name="emergency_contact_2" value="{{ old('emergency_contact_2') }}" 
                                       placeholder="Nama & No HP">
                                @error('emergency_contact_2')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-purple-100 rounded-full p-3 mr-4">
                            <i class="fas fa-info-circle text-purple-600"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Tambahan</h2>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="group_community" class="block text-sm font-medium text-gray-700 mb-2">
                                Group Lari/Komunitas/Instansi
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('group_community') border-red-500 @enderror" 
                                   id="group_community" name="group_community" value="{{ old('group_community') }}" 
                                   placeholder="Nama komunitas/instansi (opsional)">
                            @error('group_community')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Golongan Darah <span class="text-red-500">*</span>
                                </label>
                                <select class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('blood_type') border-red-500 @enderror" 
                                        id="blood_type" name="blood_type" required>
                                    <option value="">Pilih Golongan Darah</option>
                                    @foreach($bloodTypes as $type)
                                        <option value="{{ $type->name }}" {{ old('blood_type') == $type->name ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('blood_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pekerjaan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('occupation') border-red-500 @enderror" 
                                       id="occupation" name="occupation" value="{{ old('occupation') }}" required>
                                @error('occupation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="medical_history" class="block text-sm font-medium text-gray-700 mb-2">
                                Riwayat Penyakit
                            </label>
                            <textarea class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('medical_history') border-red-500 @enderror" 
                                      id="medical_history" name="medical_history" rows="3" 
                                      placeholder="Sebutkan riwayat penyakit yang relevan (opsional)">{{ old('medical_history') }}</textarea>
                            @error('medical_history')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="event_source" class="block text-sm font-medium text-gray-700 mb-2">
                                Tau Event Ini Darimana? <span class="text-red-500">*</span>
                            </label>
                            <select class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('event_source') border-red-500 @enderror" 
                                    id="event_source" name="event_source" required>
                                <option value="">Pilih Sumber Informasi</option>
                                @foreach($eventSources as $source)
                                    <option value="{{ $source->name }}" {{ old('event_source') == $source->name ? 'selected' : '' }}>
                                        {{ $source->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_source')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <i class="fas fa-key text-indigo-600"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Akun</h2>
                    </div>
                    
                    <!-- Password Auto-Generation Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-magic text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-semibold text-blue-800 mb-2">Password Otomatis</h4>
                                <p class="text-blue-700 text-sm">
                                    Sistem akan membuat password yang aman dan mengirimkannya ke WhatsApp Anda setelah registrasi.
                                </p>
                                <p class="text-blue-600 text-xs mt-1">
                                    Format password: 2 huruf + 4 angka (contoh: ab1234)
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden fields for auto password -->
                    <input type="hidden" name="use_random_password" value="1">
                    <input type="hidden" name="password_type" value="simple">
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="w-full btn-custom-primary text-white font-bold py-4 px-8 rounded-lg">
                        <i class="fas fa-running mr-2"></i>Daftar Event Lari
                    </button>
                    
                    <p class="mt-4 text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Setelah registrasi, password akan dikirim ke WhatsApp Anda untuk login
                    </p>
                    
                    <p class="mt-4 text-center text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">Login di sini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

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
                field.classList.add('border-red-500');
                field.classList.remove('border-gray-300');
                hasError = true;
            } else {
                field.classList.remove('border-red-500');
                field.classList.add('border-gray-300');
            }
        });

        // Birth date validation
        const birthDate = document.getElementById('birth_date');
        if (birthDate.value) {
            const today = new Date();
            const birth = new Date(birthDate.value);
            const age = Math.floor((today - birth) / (365.25 * 24 * 60 * 60 * 1000));
            
            if (age < 10) {
                birthDate.classList.add('border-red-500');
                birthDate.classList.remove('border-gray-300');
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
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });
    });

    // WhatsApp number validation
    const whatsappInput = document.getElementById('whatsapp_number');
    const whatsappStatus = document.getElementById('whatsapp-validation-status');
    const submitBtn = form.querySelector('button[type="submit"]');

    let validationTimeout;
    let lastValidatedNumber = '';
    let isValidWhatsApp = false;

    // Format WhatsApp number input and auto-validate
    whatsappInput.addEventListener('input', function() {
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
        this.classList.remove('border-green-500', 'border-red-500');
        this.classList.add('border-gray-300');
        isValidWhatsApp = false;
        whatsappStatus.innerHTML = '';
        
        // Auto-validate if number is long enough
        if (phoneNumber.length >= 9) {
            clearTimeout(validationTimeout);
            validationTimeout = setTimeout(() => {
                validateWhatsAppNumber(phoneNumber);
            }, 1000); // Wait 1 second after user stops typing
        }
        
        // Update submit button state
        updateSubmitButton();
    });

    // Auto-validate on blur (when user leaves the field)
    whatsappInput.addEventListener('blur', function() {
        const phoneNumber = this.value.trim();
        if (phoneNumber.length >= 9 && phoneNumber !== lastValidatedNumber) {
            clearTimeout(validationTimeout);
            validateWhatsAppNumber(phoneNumber);
        }
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
            
            // Auto-validate if number is long enough
            if (phoneNumber.length >= 9) {
                clearTimeout(validationTimeout);
                validationTimeout = setTimeout(() => {
                    validateWhatsAppNumber(phoneNumber);
                }, 1000);
            }
        }, 10);
    });

    // Validate WhatsApp number function
    function validateWhatsAppNumber(phoneNumber) {
        if (!phoneNumber || phoneNumber.length < 9) {
            showValidationStatus('error', 'Nomor WhatsApp tidak valid');
            return;
        }

        // Show loading state
        showValidationStatus('loading', 'Memvalidasi nomor WhatsApp...');

        // Format to full international format
        const fullNumber = '62' + phoneNumber;
        
        // Make API call to validate
        fetch('{{ secure_url(route("validate-whatsapp", [], false)) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                whatsapp_number: fullNumber
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.valid) {
                isValidWhatsApp = true;
                lastValidatedNumber = phoneNumber;
                whatsappInput.classList.add('border-green-500');
                whatsappInput.classList.remove('border-red-500', 'border-gray-300');
                showValidationStatus('success', 'Nomor WhatsApp valid dan terdaftar');
            } else if (data.success && !data.valid) {
                isValidWhatsApp = false;
                whatsappInput.classList.add('border-red-500');
                whatsappInput.classList.remove('border-green-500', 'border-gray-300');
                showValidationStatus('error', data.message || 'Nomor WhatsApp tidak valid atau tidak terdaftar');
            } else {
                // If API responds but validation fails, check if it's a service error or invalid number
                if (data.success === false) {
                    // True service error (timeout, connection failed) - allow with warning
                    isValidWhatsApp = true;
                    lastValidatedNumber = phoneNumber;
                    whatsappInput.classList.add('border-yellow-500');
                    whatsappInput.classList.remove('border-red-500', 'border-green-500', 'border-gray-300');
                    showValidationStatus('warning', 'Service WhatsApp tidak tersedia. Registrasi tetap dapat dilanjutkan.');
                } else {
                    // Other errors - block registration
                    isValidWhatsApp = false;
                    whatsappInput.classList.add('border-red-500');
                    whatsappInput.classList.remove('border-green-500', 'border-gray-300', 'border-yellow-500');
                    showValidationStatus('error', data.message || 'Nomor WhatsApp tidak valid');
                }
            }
        })
        .catch(error => {
            console.error('Validation error:', error);
            // Network/connection errors - block registration to be safe
            isValidWhatsApp = false;
            whatsappInput.classList.add('border-red-500');
            whatsappInput.classList.remove('border-green-500', 'border-gray-300', 'border-yellow-500');
            showValidationStatus('error', 'Validasi WhatsApp gagal. Silakan coba lagi.');
        })
        .finally(() => {
            updateSubmitButton();
        });
    }

    // Show validation status
    function showValidationStatus(type, message) {
        let className = '';
        let icon = '';
        
        switch(type) {
            case 'loading':
                className = 'bg-blue-50 border border-blue-200 text-blue-800';
                icon = 'fas fa-spinner fa-spin';
                break;
            case 'success':
                className = 'bg-green-50 border border-green-200 text-green-800';
                icon = 'fas fa-check-circle';
                break;
            case 'warning':
                className = 'bg-yellow-50 border border-yellow-200 text-yellow-800';
                icon = 'fas fa-exclamation-triangle';
                break;
            case 'error':
                className = 'bg-red-50 border border-red-200 text-red-800';
                icon = 'fas fa-exclamation-circle';
                break;
        }
        
        whatsappStatus.innerHTML = `
            <div class="${className} px-4 py-2 rounded-lg">
                <i class="${icon} mr-2"></i>${message}
            </div>
        `;
    }

    // Update submit button state
    function updateSubmitButton() {
        updateSubmitButtonState();
    }

    // Handle form submission with reCAPTCHA and WhatsApp validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Check if WhatsApp number is definitively invalid (not just service down)
        const phoneNumber = whatsappInput.value.trim();
        if (phoneNumber.length >= 9 && !isValidWhatsApp && 
            whatsappInput.classList.contains('border-red-500')) {
            alert('Nomor WhatsApp tidak valid atau tidak terdaftar. Silakan periksa kembali nomor yang Anda masukkan.');
            if (phoneNumber !== lastValidatedNumber) {
                validateWhatsAppNumber(phoneNumber);
            }
            return;
        }
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        
        // Execute reCAPTCHA
        if (typeof grecaptcha !== 'undefined') {
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config("services.recaptcha.site_key") }}', {action: 'register'}).then(function(token) {
                    // Add reCAPTCHA token to form
                    let recaptchaInput = document.querySelector('input[name="g-recaptcha-response"]');
                    if (!recaptchaInput) {
                        recaptchaInput = document.createElement('input');
                        recaptchaInput.type = 'hidden';
                        recaptchaInput.name = 'g-recaptcha-response';
                        form.appendChild(recaptchaInput);
                    }
                    recaptchaInput.value = token;
                    
                    // Submit form via API
                    submitRegistration();
                }).catch(function(error) {
                    console.error('reCAPTCHA error:', error);
                    alert('Gagal memverifikasi reCAPTCHA. Silakan coba lagi.');
                    
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-running mr-2"></i>Daftar Event Lari';
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                });
            });
        } else {
            // If reCAPTCHA not loaded, submit anyway
            submitRegistration();
        }
    });

    // Submit registration via API
    function submitRegistration() {
        const formData = new FormData(form);
        
        // Prepare data with proper field mapping
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            phone: formData.get('whatsapp_number'), // API expects 'phone' field, use whatsapp number
            category: formData.get('race_category'), // Backend expects 'category' not 'race_category'
            
            // Additional fields (may not be processed by basic API but keeping for completeness)
            bib_name: formData.get('bib_name'),
            gender: formData.get('gender'),
            birth_place: formData.get('birth_place'),
            birth_date: formData.get('birth_date'),
            address: formData.get('address'),
            jersey_size: formData.get('jersey_size'),
            whatsapp_number: formData.get('whatsapp_number'),
            emergency_contact_1: formData.get('emergency_contact_1'),
            emergency_contact_2: formData.get('emergency_contact_2'),
            group_community: formData.get('group_community'),
            blood_type: formData.get('blood_type'),
            occupation: formData.get('occupation'),
            medical_history: formData.get('medical_history'),
            event_source: formData.get('event_source')
        };

        // Debug: log the data being sent
        console.log('Sending registration data:', data);
        
        // Validate required fields before sending
        if (!data.name || !data.email || !data.phone || !data.category) {
            alert('Mohon lengkapi semua field yang wajib diisi (Nama, Email, WhatsApp, Kategori).');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-running mr-2"></i>Daftar Event Lari';
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            return;
        }

        fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Registrasi berhasil! Nomor registrasi: ' + data.data.registration_number + 
                      '\n\nSilakan login dengan email dan password yang telah dikirim ke WhatsApp Anda.');
                // Redirect to login page
                window.location.href = '/login';
            } else {
                alert('Registrasi gagal: ' + data.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-running mr-2"></i>Daftar Event Lari';
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        })
        .catch(error => {
            console.error('Registration error:', error);
            alert('Terjadi kesalahan saat registrasi. Silakan coba lagi.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-running mr-2"></i>Daftar Event Lari';
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
    }

    // Load reCAPTCHA script
    const script = document.createElement('script');
    script.src = 'https://www.google.com/recaptcha/api.js?render={{ config("services.recaptcha.site_key") }}';
    document.head.appendChild(script);

    // Enhanced ticket functionality with auto-refresh and quota monitoring
    const raceCategorySelect = document.getElementById('race_category');
    const ticketInfoSection = document.getElementById('ticketInfo');
    let currentTicketData = null;
    let isLoadingTicketInfo = false;
    let countdownInterval = null;
    let quotaRefreshInterval = null;
    let currentSelectedCategory = null;
    let isQuotaAvailable = true;

    // Handle race category change
    if (raceCategorySelect) {
        raceCategorySelect.addEventListener('change', function() {
            const category = this.value;
            
            // Clear any existing intervals
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
            if (quotaRefreshInterval) {
                clearInterval(quotaRefreshInterval);
                quotaRefreshInterval = null;
            }
            
            currentSelectedCategory = category;
            
            if (category) {
                fetchTicketInfo(category);
                // Start quota auto-refresh every 5 seconds
                startQuotaAutoRefresh(category);
            } else {
                hideTicketInfo();
                currentSelectedCategory = null;
            }
        });
    }

    // Start quota auto-refresh every 5 seconds
    function startQuotaAutoRefresh(category) {
        if (quotaRefreshInterval) {
            clearInterval(quotaRefreshInterval);
        }
        
        quotaRefreshInterval = setInterval(() => {
            if (currentSelectedCategory === category) {
                refreshQuotaOnly(category);
            }
        }, 5000); // Update every 5 seconds
    }

    // Light API request to refresh only quota information
    function refreshQuotaOnly(category) {
        if (!category || isLoadingTicketInfo) return;
        
        fetch(`/api/ticket-info?category=${encodeURIComponent(category)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.available && data.ticket_type) {
                // Update quota display only
                updateQuotaDisplay(data.ticket_type);
                
                // Check if quota is available
                const remainingQuota = parseInt(data.ticket_type.remaining_quota) || 0;
                isQuotaAvailable = remainingQuota > 0;
                
                // Update submit button state
                updateSubmitButtonState();
            } else {
                // Quota might be exhausted
                isQuotaAvailable = false;
                updateSubmitButtonState();
            }
        })
        .catch(error => {
            console.error('Error refreshing quota:', error);
        });
    }

    // Update quota display with color coding
    function updateQuotaDisplay(ticketType) {
        const quotaElement = document.querySelector('.remaining-quota');
        if (!quotaElement) return;
        
        const remainingQuota = parseInt(ticketType.remaining_quota) || 0;
        const totalQuota = parseInt(ticketType.total_quota) || 1;
        const percentage = (remainingQuota / totalQuota) * 100;
        
        quotaElement.textContent = remainingQuota;
        
        // Color coding based on quota percentage
        const quotaContainer = quotaElement.parentElement;
        quotaContainer.classList.remove('text-green-600', 'text-orange-600', 'text-red-600', 'text-blue-600');
        
        if (remainingQuota === 0) {
            quotaContainer.classList.add('text-red-600');
            quotaElement.innerHTML = '<strong>HABIS</strong>';
        } else if (percentage > 25) {
            quotaContainer.classList.add('text-green-600');
        } else if (percentage > 10) {
            quotaContainer.classList.add('text-orange-600');
        } else {
            quotaContainer.classList.add('text-red-600');
        }
    }

    // Update submit button state based on quota availability
    function updateSubmitButtonState() {
        const submitBtn = form.querySelector('button[type="submit"]');
        const phoneNumber = whatsappInput.value.trim();
        
        // Check multiple conditions for disabling submit button
        const shouldDisable = 
            // WhatsApp validation failed
            (phoneNumber.length >= 9 && !isValidWhatsApp && whatsappInput.classList.contains('border-red-500')) ||
            // Quota not available
            !isQuotaAvailable ||
            // No category selected
            !currentSelectedCategory;
        
        if (shouldDisable) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            
            if (!isQuotaAvailable) {
                submitBtn.innerHTML = '<i class="fas fa-ban mr-2"></i>Kuota Habis - Registrasi Ditutup';
            } else if (phoneNumber.length >= 9 && !isValidWhatsApp && whatsappInput.classList.contains('border-red-500')) {
                submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Validasi WhatsApp Diperlukan';
            } else {
                submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Pilih Kategori Terlebih Dahulu';
            }
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitBtn.innerHTML = '<i class="fas fa-running mr-2"></i>Daftar Event Lari';
        }
    }
    // Fetch ticket information for selected category
    function fetchTicketInfo(category) {
        if (isLoadingTicketInfo) {
            return;
        }
        
        isLoadingTicketInfo = true;
        
        fetch(`/api/ticket-info?category=${encodeURIComponent(category)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.available) {
                currentTicketData = data.ticket_type;
                showTicketInfo(data.ticket_type);
                
                // Check quota availability
                const remainingQuota = parseInt(data.ticket_type.remaining_quota) || 0;
                isQuotaAvailable = remainingQuota > 0;
                
                // Update submit button state
                updateSubmitButtonState();
                
                if (data.ticket_type.time_remaining) {
                    startCountdown(data.ticket_type.time_remaining);
                }
            } else {
                showTicketUnavailable(data.message || 'Tiket tidak tersedia untuk kategori ini');
                isQuotaAvailable = false;
                updateSubmitButtonState();
            }
        })
        .catch(error => {
            console.error('Error fetching ticket info:', error);
            showTicketError();
            isQuotaAvailable = false;
            updateSubmitButtonState();
        })
        .finally(() => {
            isLoadingTicketInfo = false;
        });
    }

    // Show ticket information
    function showTicketInfo(ticketType) {
        ticketInfoSection.classList.remove('hidden');
        
        const remainingQuota = parseInt(ticketType.remaining_quota) || 0;
        const quotaColor = remainingQuota === 0 ? 'text-red-600' : 'text-green-600';
        const quotaText = remainingQuota === 0 ? 'HABIS' : remainingQuota;
        
        ticketInfoSection.innerHTML = `
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i class="fas fa-ticket-alt text-blue-600"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Informasi Tiket</h2>
            </div>
            
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="ticket-type-info">
                            <h3 class="text-lg font-semibold text-gray-800">${ticketType.name} - ${ticketType.category}</h3>
                            <p class="text-2xl font-bold text-green-600">${ticketType.formatted_price || 'Rp 0'}</p>
                            <div class="ticket-quota ${quotaColor}">
                                <small class="text-gray-600">Kuota tersisa: <span class="remaining-quota font-semibold">${quotaText}</span></small>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="countdown-timer">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Sisa Waktu:</h3>
                            <div class="timer-display flex space-x-1">
                                <div class="flex flex-col items-center">
                                    <span class="bg-red-500 text-white px-2 py-1 rounded timer-days">0</span>
                                    <span class="text-xs text-gray-600">hari</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="bg-red-500 text-white px-2 py-1 rounded timer-hours">0</span>
                                    <span class="text-xs text-gray-600">jam</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="bg-red-500 text-white px-2 py-1 rounded timer-minutes">0</span>
                                    <span class="text-xs text-gray-600">menit</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="bg-red-500 text-white px-2 py-1 rounded timer-seconds">0</span>
                                    <span class="text-xs text-gray-600">detik</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Show ticket unavailable message
    function showTicketUnavailable(message) {
        ticketInfoSection.classList.remove('hidden');
        ticketInfoSection.innerHTML = `
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i class="fas fa-ticket-alt text-blue-600"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Informasi Tiket</h2>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                ${message}
            </div>
        `;
    }

    // Show ticket error
    function showTicketError() {
        ticketInfoSection.classList.remove('hidden');
        ticketInfoSection.innerHTML = `
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i class="fas fa-ticket-alt text-blue-600"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Informasi Tiket</h2>
            </div>
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i>
                Gagal memuat informasi tiket. Silakan coba lagi.
            </div>
        `;
    }

    // Hide ticket information
    function hideTicketInfo() {
        ticketInfoSection.classList.add('hidden');
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
        if (quotaRefreshInterval) {
            clearInterval(quotaRefreshInterval);
            quotaRefreshInterval = null;
        }
        isQuotaAvailable = true;
        updateSubmitButtonState();
    }

    // Start countdown timer with seconds
    function startCountdown(timeRemaining) {
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
        
        if (!timeRemaining || timeRemaining.expired) {
            const timerDisplay = document.querySelector('.timer-display');
            if (timerDisplay) {
                timerDisplay.innerHTML = '<span class="text-red-500">Periode berakhir</span>';
            }
            return;
        }
        
        let days = parseInt(timeRemaining.days) || 0;
        let hours = parseInt(timeRemaining.hours) || 0;
        let minutes = parseInt(timeRemaining.minutes) || 0;
        let seconds = parseInt(timeRemaining.seconds) || 0;
        
        // Initial display
        updateTimerDisplay(days, hours, minutes, seconds);
        
        countdownInterval = setInterval(() => {
            seconds--;
            if (seconds < 0) {
                seconds = 59;
                minutes--;
                if (minutes < 0) {
                    minutes = 59;
                    hours--;
                    if (hours < 0) {
                        hours = 23;
                        days--;
                        if (days < 0) {
                            clearInterval(countdownInterval);
                            countdownInterval = null;
                            const timerDisplay = document.querySelector('.timer-display');
                            if (timerDisplay) {
                                timerDisplay.innerHTML = '<span class="text-red-500">Periode berakhir</span>';
                            }
                            return;
                        }
                    }
                }
            }
            
            updateTimerDisplay(days, hours, minutes, seconds);
        }, 1000); // Update every second
    }

    // Update timer display elements including seconds
    function updateTimerDisplay(days, hours, minutes, seconds) {
        const daysElement = document.querySelector('.timer-days');
        const hoursElement = document.querySelector('.timer-hours');
        const minutesElement = document.querySelector('.timer-minutes');
        const secondsElement = document.querySelector('.timer-seconds');
        
        if (daysElement) daysElement.textContent = days;
        if (hoursElement) hoursElement.textContent = hours;
        if (minutesElement) minutesElement.textContent = minutes;
        if (secondsElement) secondsElement.textContent = seconds;
    }

    // Cleanup intervals when page is unloaded
    window.addEventListener('beforeunload', function() {
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
        if (quotaRefreshInterval) {
            clearInterval(quotaRefreshInterval);
        }
    });

    // Initial submit button state check
    updateSubmitButtonState();
});
</script>
    </div>
</div>
@endsection
