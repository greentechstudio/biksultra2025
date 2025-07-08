@extends('layouts.user')

@section('title', 'Edit Profil')

@section('header')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">
        <i class="fas fa-user-edit mr-3"></i>Edit Profil
    </h1>
    <div class="flex items-center">
        <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Edit hanya sekali
        </span>
    </div>
</div>
@endsection

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-800">
                        <strong>Penting:</strong> Edit profil hanya dapat dilakukan <strong>satu kali</strong>. 
                        Pastikan semua data sudah benar sebelum menyimpan.
                    </p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                        <ul class="list-disc list-inside text-sm text-red-700 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" id="editProfileForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Data Pribadi -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-user mr-3 text-blue-600"></i>Data Pribadi
                    </h3>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               value="{{ old('name', $user->name) }}">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                               value="{{ old('email', $user->email) }}">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                        <input type="tel" id="phone" name="phone"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                               value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700">WhatsApp <span class="text-red-500">*</span></label>
                        <input type="tel" id="whatsapp_number" name="whatsapp_number" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('whatsapp_number') border-red-500 @enderror"
                               value="{{ old('whatsapp_number', $user->whatsapp_number) }}"
                               placeholder="62812345678">
                        @error('whatsapp_number')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Format: 62812345678 (tanpa tanda +)</p>
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select id="gender" name="gender" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('gender') border-red-500 @enderror">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('gender', $user->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender', $user->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_place" class="block text-sm font-medium text-gray-700">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" id="birth_place" name="birth_place" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('birth_place') border-red-500 @enderror"
                               value="{{ old('birth_place', $user->birth_place) }}">
                        @error('birth_place')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" id="birth_date" name="birth_date" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('birth_date') border-red-500 @enderror"
                               value="{{ old('birth_date', $user->birth_date) }}">
                        @error('birth_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat <span class="text-red-500">*</span></label>
                        <textarea id="address" name="address" rows="3" required
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="occupation" class="block text-sm font-medium text-gray-700">Pekerjaan <span class="text-red-500">*</span></label>
                        <input type="text" id="occupation" name="occupation" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('occupation') border-red-500 @enderror"
                               value="{{ old('occupation', $user->occupation) }}">
                        @error('occupation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="blood_type" class="block text-sm font-medium text-gray-700">Golongan Darah <span class="text-red-500">*</span></label>
                        <select id="blood_type" name="blood_type" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('blood_type') border-red-500 @enderror">
                            <option value="">Pilih Golongan Darah</option>
                            @foreach($bloodTypes as $bloodType)
                                <option value="{{ $bloodType->type }}" {{ old('blood_type', $user->blood_type) == $bloodType->type ? 'selected' : '' }}>
                                    {{ $bloodType->type }}
                                </option>
                            @endforeach
                        </select>
                        @error('blood_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Data Perlombaan -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-running mr-3 text-green-600"></i>Data Perlombaan
                    </h3>

                    <div>
                        <label for="race_category" class="block text-sm font-medium text-gray-700">Kategori Lomba <span class="text-red-500">*</span></label>
                        <select id="race_category" name="race_category" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('race_category') border-red-500 @enderror">
                            <option value="">Pilih Kategori Lomba</option>
                            @foreach($raceCategories as $category)
                                <option value="{{ $category->name }}" 
                                        data-price="{{ $category->price }}"
                                        {{ old('race_category', $user->race_category) == $category->name ? 'selected' : '' }}>
                                    {{ $category->name }} - Rp {{ number_format($category->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('race_category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bib_name" class="block text-sm font-medium text-gray-700">Nama BIB <span class="text-red-500">*</span></label>
                        <input type="text" id="bib_name" name="bib_name" required maxlength="20"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('bib_name') border-red-500 @enderror"
                               value="{{ old('bib_name', $user->bib_name) }}"
                               placeholder="Nama yang akan tercetak di BIB">
                        @error('bib_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Nama yang akan dicetak di nomor BIB Anda (maksimal 20 karakter)
                        </p>
                    </div>

                    <div>
                        <label for="jersey_size" class="block text-sm font-medium text-gray-700">Ukuran Jersey <span class="text-red-500">*</span></label>
                        <select id="jersey_size" name="jersey_size" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('jersey_size') border-red-500 @enderror">
                            <option value="">Pilih Ukuran Jersey</option>
                            @foreach($jerseySizes as $size)
                                <option value="{{ $size->size }}" {{ old('jersey_size', $user->jersey_size) == $size->size ? 'selected' : '' }}>
                                    {{ $size->size }} ({{ $size->description }})
                                </option>
                            @endforeach
                        </select>
                        @error('jersey_size')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="group_community" class="block text-sm font-medium text-gray-700">Grup/Komunitas</label>
                        <input type="text" id="group_community" name="group_community"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('group_community') border-red-500 @enderror"
                               value="{{ old('group_community', $user->group_community) }}"
                               placeholder="Nama grup atau komunitas (opsional)">
                        @error('group_community')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="event_source" class="block text-sm font-medium text-gray-700">Sumber Info Event <span class="text-red-500">*</span></label>
                        <select id="event_source" name="event_source" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('event_source') border-red-500 @enderror">
                            <option value="">Pilih Sumber Info Event</option>
                            @foreach($eventSources as $source)
                                <option value="{{ $source->name }}" {{ old('event_source', $user->event_source) == $source->name ? 'selected' : '' }}>
                                    {{ $source->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_source')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-phone-alt mr-3 text-red-600"></i>Kontak Darurat
                    </h3>

                    <div>
                        <label for="emergency_contact_1" class="block text-sm font-medium text-gray-700">Kontak Darurat 1 <span class="text-red-500">*</span></label>
                        <input type="text" id="emergency_contact_1" name="emergency_contact_1" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('emergency_contact_1') border-red-500 @enderror"
                               value="{{ old('emergency_contact_1', $user->emergency_contact_1) }}"
                               placeholder="Nama dan nomor telepon">
                        @error('emergency_contact_1')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emergency_contact_2" class="block text-sm font-medium text-gray-700">Kontak Darurat 2</label>
                        <input type="text" id="emergency_contact_2" name="emergency_contact_2"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('emergency_contact_2') border-red-500 @enderror"
                               value="{{ old('emergency_contact_2', $user->emergency_contact_2) }}"
                               placeholder="Nama dan nomor telepon (opsional)">
                        @error('emergency_contact_2')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="medical_history" class="block text-sm font-medium text-gray-700">Riwayat Medis</label>
                        <textarea id="medical_history" name="medical_history" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('medical_history') border-red-500 @enderror"
                                  placeholder="Riwayat penyakit, alergi, atau kondisi medis khusus (opsional)">{{ old('medical_history', $user->medical_history) }}</textarea>
                        @error('medical_history')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-between">
                <a href="{{ route('profile.show') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">Konfirmasi Edit Profil</h3>
            <div class="mt-2 px-7 py-3">
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Perhatian!</strong> Edit profil hanya dapat dilakukan <strong>satu kali</strong>. 
                        Setelah Anda menyimpan perubahan, Anda tidak akan dapat mengedit profil lagi.
                    </p>
                </div>
                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menyimpan semua perubahan?</p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmCancel" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </button>
                <button id="confirmSubmit" class="px-4 py-2 bg-yellow-600 text-white text-base font-medium rounded-md w-32 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                    Ya, Simpan
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
    const confirmModal = document.getElementById('confirmModal');
    const confirmSubmit = document.getElementById('confirmSubmit');
    const confirmCancel = document.getElementById('confirmCancel');

    // Handle form submission dengan konfirmasi
    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Validasi form terlebih dahulu
        if (form.checkValidity()) {
            confirmModal.classList.remove('hidden');
        } else {
            form.reportValidity();
        }
    });

    // Handle konfirmasi submit
    confirmSubmit.addEventListener('click', function() {
        confirmModal.classList.add('hidden');
        form.submit();
    });

    // Handle cancel modal
    confirmCancel.addEventListener('click', function() {
        confirmModal.classList.add('hidden');
    });

    // Close modal on click outside
    confirmModal.addEventListener('click', function(e) {
        if (e.target === confirmModal) {
            confirmModal.classList.add('hidden');
        }
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
            const priceInfo = document.createElement('p');
            priceInfo.className = 'mt-2 text-sm text-gray-500 price-info';
            priceInfo.innerHTML = `<i class="fas fa-info-circle mr-1"></i>Biaya registrasi: Rp ${parseInt(price).toLocaleString('id-ID')}`;
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
