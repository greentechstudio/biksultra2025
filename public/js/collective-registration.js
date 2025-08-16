// Collective Registration JavaScript
let participantCount = 0;
const maxParticipants = 50;

// Data akan diinject dari Laravel blade
let jerseySizes = [];
let raceCategories = [];
let bloodTypes = [];
let eventSources = [];

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== COLLECTIVE REGISTRATION INITIALIZED ===');
    
    // Wait for Laravel data to be injected
    setTimeout(() => {
        console.log('window.laravelData:', window.laravelData);
        
        // Use Laravel data if available
        if (typeof window.laravelData !== 'undefined') {
            jerseySizes = window.laravelData.jerseySizes || [];
            raceCategories = window.laravelData.raceCategories || [];
            bloodTypes = window.laravelData.bloodTypes || [];
            eventSources = window.laravelData.eventSources || [];
            
            console.log('Data loaded from Laravel:');
            console.log('Jersey Sizes:', jerseySizes);
            console.log('Race Categories:', raceCategories);
            console.log('Blood Types:', bloodTypes);
            console.log('Event Sources:', eventSources);
        } else {
            console.warn('window.laravelData is undefined, using fallback data');
        }
        
        // Generate initial 10 forms
        generateInitialForms();
        
        // Setup form validation
        setupFormValidation();
        
        // Setup submit handler
        setupSubmitHandler();
        
        // Update counter display
        updateCounters();
        
        // Initial submit button state check
        updateSubmitButtonState();
    }, 100); // 100ms delay to ensure Laravel data is injected
});

function generateInitialForms() {
    const container = document.getElementById('participantsContainer');
    if (!container) {
        console.error('Participants container not found');
        return;
    }
    
    // Clear container first
    container.innerHTML = '';
    participantCount = 0;
    
    // Generate 5 initial forms
    for (let i = 0; i < 5; i++) {
        addParticipantForm();
    }
    
    // Update remove button visibility after generating initial forms
    updateRemoveButtonsVisibility();
    
    console.log(`Generated ${participantCount} initial forms`);
}

function generateParticipantForm(index) {
    return `
    <div class="participant-form bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 slide-in-right" data-participant="${index}">
        <!-- Header with Progress -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                    ${index}
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Peserta ${index}</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Progress: <span id="formProgress_${index}" class="font-semibold text-blue-600">0%</span>
                    </p>
                </div>
            </div>
            <button type="button" class="remove-participant text-red-500 hover:text-red-700 hover:bg-red-50 p-3 rounded-xl transition-all duration-200" data-participant="${index}">
                <i class="fas fa-trash-alt text-lg"></i>
            </button>
        </div>

        <!-- Progress Bar for this form -->
        <div class="mb-8">
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="formProgressBar_${index}" class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-1">
                <span>Kosong</span>
                <span id="formProgressText_${index}">0 dari 16 field terisi</span>
                <span>Lengkap</span>
            </div>
        </div>

        <!-- Form Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Nama Lengkap *</label>
                    <input type="text" name="participants[${index}][name]" required
                           class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                           placeholder="Masukkan nama lengkap" data-participant="${index}">
                </div>

                <!-- Nama BIB -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Nama di BIB *</label>
                    <input type="text" name="participants[${index}][bib_name]" required
                           class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                           placeholder="Nama yang akan tercetak di BIB" data-participant="${index}">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Email *</label>
                    <input type="email" name="participants[${index}][email]" required
                           class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                           placeholder="contoh@email.com" data-participant="${index}">
                </div>

                <!-- No WhatsApp -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        No Kontak WhatsApp *
                        <small class="text-gray-500">(akan divalidasi otomatis)</small>
                    </label>
                    <div class="whatsapp-input-group">
                        <span class="whatsapp-prefix inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border-2 border-r-0 border-gray-300 rounded-l-xl">
                            +62
                        </span>
                        <input type="text" name="participants[${index}][whatsapp_number]" required
                               class="form-field whatsapp-input px-4 py-3 border-2 border-gray-200 rounded-r-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                               placeholder="8114000805" data-participant="${index}">
                    </div>
                    <p class="mt-2 text-xs text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Masukkan nomor tanpa awalan 0 atau +62. Contoh: 8114000805
                    </p>
                    <div id="whatsappValidation_${index}" class="whatsapp-validation mt-2"></div>
                </div>

                <!-- Nomor HP Alternatif -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Nomor HP Alternatif</label>
                    <input type="text" name="participants[${index}][phone]"
                           class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                           placeholder="081234567890" data-participant="${index}">
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Jenis Kelamin *</label>
                    <select name="participants[${index}][gender]" required
                            class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                            data-participant="${index}">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Tempat Lahir *</label>
                    <input type="text" name="participants[${index}][birth_place]" required
                           class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                           placeholder="Kota tempat lahir" data-participant="${index}">
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Tanggal Lahir *</label>
                    <input type="date" name="participants[${index}][birth_date]" required
                           class="form-field birth-date-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                           data-participant="${index}">
                    <p class="mt-2 text-xs text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Minimal umur 10 tahun
                    </p>
                </div>

                <!-- Ukuran Jersey -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ukuran Jersey *</label>
                    <select name="participants[${index}][jersey_size]" required
                            class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                            data-participant="${index}">
                        <option value="">Pilih Ukuran Jersey</option>
                        ${generateJerseySizeOptions()}
                    </select>
                </div>

                <!-- Kategori Lomba -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Kategori Lomba *</label>
                    <select name="participants[${index}][race_category]" required
                            class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                            data-participant="${index}">
                        <option value="">Pilih Kategori Lomba</option>
                        ${generateRaceCategoryOptions()}
                    </select>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Kota/Kabupaten Tempat Tinggal Saat Ini -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Kota/Kabupaten Tempat Tinggal Saat Ini *</label>
                    <div class="location-autocomplete-container">
                        <input type="text" name="participants[${index}][regency_search]" required
                               class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                               placeholder="Ketik nama kota/kabupaten tempat tinggal saat ini..."
                               data-location-autocomplete
                               data-hidden-input="#regency_id_${index}"
                               data-participant="${index}"
                               autocomplete="off">
                        <input type="hidden" id="regency_id_${index}" name="participants[${index}][regency_id]">
                        <input type="hidden" name="participants[${index}][regency_name]">
                        <input type="hidden" name="participants[${index}][province_name]">
                    </div>
                    <p class="mt-2 text-xs text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Ketik minimal 2 karakter untuk mencari kota/kabupaten tempat tinggal saat ini
                    </p>
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Alamat Lengkap *</label>
                    <textarea name="participants[${index}][address]" required rows="3"
                              class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200 resize-none"
                              placeholder="Alamat lengkap tempat tinggal" data-participant="${index}"></textarea>
                </div>

                <!-- Nama Kontak Darurat -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Nama Kontak Darurat *</label>
                    <input type="text" name="participants[${index}][emergency_contact_name]" required
                           class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                           placeholder="Nama kontak darurat" data-participant="${index}">
                </div>

                <!-- Kontak Darurat - HP -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">No HP Kontak Darurat *</label>
                    <input type="text" name="participants[${index}][emergency_contact_phone]" required
                           class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                           placeholder="081234567890" data-participant="${index}">
                </div>

                <!-- Golongan Darah -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Golongan Darah *</label>
                    <select name="participants[${index}][blood_type]" required
                            class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                            data-participant="${index}">
                        <option value="">Pilih Golongan Darah</option>
                        ${generateBloodTypeOptions()}
                    </select>
                </div>

                <!-- Pekerjaan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Pekerjaan *</label>
                    <input type="text" name="participants[${index}][occupation]" required
                           class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                           placeholder="Pekerjaan/profesi" data-participant="${index}">
                </div>

                <!-- Riwayat Penyakit -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Riwayat Penyakit</label>
                    <textarea name="participants[${index}][medical_history]" rows="2"
                              class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200 resize-none"
                              placeholder="Riwayat penyakit atau kondisi medis khusus (opsional)" data-participant="${index}"></textarea>
                </div>

                <!-- Komunitas/Group -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Komunitas/Group</label>
                    <input type="text" name="participants[${index}][group_community]"
                           class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                           placeholder="Nama komunitas atau group (opsional)" data-participant="${index}">
                </div>

                <!-- Sumber Informasi Event -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Sumber Informasi Event *</label>
                    <select name="participants[${index}][event_source]" required
                            class="form-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:outline-none transition-all duration-200"
                            data-participant="${index}">
                        <option value="">Pilih Sumber Informasi</option>
                        ${generateEventSourceOptions()}
                    </select>
                </div>
            </div>
        </div>
    </div>
    `;
}

// Helper function to get maximum birth date (10 years ago)
function getMaxBirthDate() {
    try {
        const today = new Date();
        const maxDate = new Date(today.getFullYear() - 10, today.getMonth(), today.getDate());
        return maxDate.toISOString().split('T')[0];
    } catch (error) {
        console.error('Error calculating max birth date:', error);
        // Fallback: return a date 10 years ago from current year
        const currentYear = new Date().getFullYear();
        return `${currentYear - 10}-01-01`;
    }
}

// Data generators with improved debugging
function generateJerseySizeOptions() {
    console.log('=== GENERATING JERSEY SIZE OPTIONS ===');
    console.log('window.laravelData available:', !!window.laravelData);
    console.log('Jersey sizes from laravelData:', window.laravelData?.jerseySizes);
    
    const laravelSizes = window.laravelData?.jerseySizes;
    
    if (laravelSizes && Array.isArray(laravelSizes) && laravelSizes.length > 0) {
        console.log('Using Laravel jersey sizes data:', laravelSizes);
        const options = laravelSizes.map(size => {
            console.log('Processing jersey size:', size);
            const sizeText = size.name; // Use 'name' field from database
            const sizeValue = size.name; // Use name as value instead of id
            return `<option value="${sizeValue}">${sizeText}</option>`;
        }).join('');
        console.log('Generated Laravel jersey options:', options);
        return options;
    }
    
    console.log('Using fallback jersey sizes - no Laravel data available');
    return `
        <option value="S">S</option>
        <option value="M">M</option>
        <option value="L">L</option>
        <option value="XL">XL</option>
        <option value="XXL">XXL</option>
    `;
}

function generateRaceCategoryOptions() {
    console.log('=== GENERATING RACE CATEGORY OPTIONS ===');
    console.log('Race categories from laravelData:', window.laravelData?.raceCategories);
    
    const laravelCategories = window.laravelData?.raceCategories;
    
    if (laravelCategories && Array.isArray(laravelCategories) && laravelCategories.length > 0) {
        console.log('Using Laravel race categories data:', laravelCategories);
        const options = laravelCategories.map(cat => {
            console.log('Processing race category:', cat);
            const catText = cat.name; // Use 'name' field from database
            const catValue = cat.name; // Use name as value instead of id
            return `<option value="${catValue}">${catText}</option>`;
        }).join('');
        console.log('Generated Laravel category options:', options);
        return options;
    }
    
    console.log('Using fallback race categories');
    return `
        <option value="5K">5K</option>
        <option value="10K">10K</option>
        <option value="21K">21K</option>
    `;
}

function generateBloodTypeOptions() {
    console.log('=== GENERATING BLOOD TYPE OPTIONS ===');
    console.log('Blood types from laravelData:', window.laravelData?.bloodTypes);
    
    const laravelBloodTypes = window.laravelData?.bloodTypes;
    
    if (laravelBloodTypes && Array.isArray(laravelBloodTypes) && laravelBloodTypes.length > 0) {
        console.log('Using Laravel blood types data:', laravelBloodTypes);
        const options = laravelBloodTypes.map(blood => {
            console.log('Processing blood type:', blood);
            const bloodText = blood.name; // Use 'name' field from database
            const bloodValue = blood.name; // Use name as value instead of id
            return `<option value="${bloodValue}">${bloodText}</option>`;
        }).join('');
        console.log('Generated Laravel blood type options:', options);
        return options;
    }
    
    console.log('Using fallback blood types');
    return `
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="AB">AB</option>
        <option value="O">O</option>
    `;
}

function generateEventSourceOptions() {
    console.log('=== GENERATING EVENT SOURCE OPTIONS ===');
    console.log('Event sources from laravelData:', window.laravelData?.eventSources);
    
    const laravelSources = window.laravelData?.eventSources;
    
    if (laravelSources && Array.isArray(laravelSources) && laravelSources.length > 0) {
        console.log('Using Laravel event sources data:', laravelSources);
        const options = laravelSources.map(source => {
            console.log('Processing event source:', source);
            const sourceText = source.name; // Use 'name' field from database
            const sourceValue = source.name; // Use name as value instead of id
            return `<option value="${sourceValue}">${sourceText}</option>`;
        }).join('');
        console.log('Generated Laravel event source options:', options);
        return options;
    }
    
    console.log('Using fallback event sources');
    return `
        <option value="social_media">Social Media</option>
        <option value="website">Website</option>
        <option value="teman">Teman</option>
        <option value="keluarga">Keluarga</option>
    `;
}

function addParticipantForm() {
    if (participantCount >= maxParticipants) {
        alert(`Maksimal ${maxParticipants} peserta per pendaftaran kolektif`);
        return;
    }

    participantCount++;
    const container = document.getElementById('participantsContainer');
    const formHtml = generateParticipantForm(participantCount);
    container.insertAdjacentHTML('beforeend', formHtml);

    // Setup event listeners for the new form
    setupFormEventListeners(participantCount);
    updateCounters();
    updateRemoveButtonsVisibility();

    console.log(`Added participant form ${participantCount}`);
}

function removeParticipantForm(index) {
    // Check if we have minimum 5 participants
    if (participantCount <= 5) {
        // Show warning message
        showWarningMessage('Minimal 5 peserta diperlukan untuk registrasi kolektif. Form tidak dapat dihapus.');
        return;
    }
    
    const form = document.querySelector(`[data-participant="${index}"]`);
    if (form) {
        // Add confirmation dialog for extra safety
        if (confirm('Apakah Anda yakin ingin menghapus peserta ini?')) {
            form.classList.add('slide-out-right');
            setTimeout(() => {
                form.remove();
                participantCount--;
                renumberForms();
                updateCounters();
                updateRemoveButtonsVisibility();
            }, 500);
        }
    }
}

function showWarningMessage(message) {
    // Create warning notification
    const warningDiv = document.createElement('div');
    warningDiv.className = 'fixed top-4 right-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4 shadow-lg z-50 max-w-md slide-in-right';
    warningDiv.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-yellow-800">${message}</p>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-yellow-400 hover:text-yellow-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(warningDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (warningDiv.parentElement) {
            warningDiv.classList.add('slide-out-right');
            setTimeout(() => {
                if (warningDiv.parentElement) {
                    warningDiv.remove();
                }
            }, 500);
        }
    }, 5000);
}

function updateRemoveButtonsVisibility() {
    const forms = document.querySelectorAll('.participant-form');
    
    forms.forEach((form, index) => {
        const removeBtn = form.querySelector('.remove-participant');
        if (removeBtn) {
            if (participantCount <= 10) {
                // Style remove button as disabled for minimum 10 participants
                removeBtn.style.display = 'block';
                removeBtn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                removeBtn.classList.remove('hover:text-red-700', 'hover:bg-red-50');
                removeBtn.title = 'Minimal 10 peserta diperlukan';
            } else {
                // Show remove button as active if more than 10 participants
                removeBtn.style.display = 'block';
                removeBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                removeBtn.classList.add('hover:text-red-700', 'hover:bg-red-50');
                removeBtn.title = 'Hapus peserta ini';
            }
        }
    });
}

function renumberForms() {
    const forms = document.querySelectorAll('.participant-form');
    participantCount = 0;
    
    forms.forEach((form, index) => {
        participantCount++;
        const newIndex = participantCount;
        const oldIndex = form.getAttribute('data-participant');
        
        // Update form attributes
        form.setAttribute('data-participant', newIndex);
        
        // Update header number
        form.querySelector('.w-12.h-12 div, .w-12.h-12').textContent = newIndex;
        form.querySelector('h3').textContent = `Peserta ${newIndex}`;
        
        // Update progress IDs
        const progressText = form.querySelector(`#formProgress_${oldIndex}`);
        if (progressText) {
            progressText.id = `formProgress_${newIndex}`;
        }
        
        const progressBar = form.querySelector(`#formProgressBar_${oldIndex}`);
        if (progressBar) {
            progressBar.id = `formProgressBar_${newIndex}`;
        }
        
        const progressDetailText = form.querySelector(`#formProgressText_${oldIndex}`);
        if (progressDetailText) {
            progressDetailText.id = `formProgressText_${newIndex}`;
        }
        
        // Update all form inputs
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/\[\d+\]/, `[${newIndex}]`);
                input.setAttribute('name', newName);
            }
            
            const dataParticipant = input.getAttribute('data-participant');
            if (dataParticipant) {
                input.setAttribute('data-participant', newIndex);
            }
        });
        
        // Update remove button
        const removeBtn = form.querySelector('.remove-participant');
        if (removeBtn) {
            removeBtn.setAttribute('data-participant', newIndex);
        }
        
        // Update individual form progress with new index
        updateIndividualFormProgress(newIndex);
    });
}

function setupFormEventListeners(index) {
    // Set max date for birth date input
    const birthDateInput = document.querySelector(`[data-participant="${index}"] .birth-date-input`);
    if (birthDateInput) {
        birthDateInput.max = getMaxBirthDate();
    }

    // Remove button
    const removeBtn = document.querySelector(`[data-participant="${index}"] .remove-participant`);
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            const participantIndex = this.getAttribute('data-participant');
            removeParticipantForm(participantIndex);
        });
    }

    // WhatsApp validation
    const whatsappInput = document.querySelector(`[data-participant="${index}"] .whatsapp-input`);
    if (whatsappInput) {
        let validationTimeout;
        
        whatsappInput.addEventListener('input', function() {
            clearTimeout(validationTimeout);
            const phoneNumber = this.value.replace(/\D/g, ''); // Remove non-digits
            
            // Auto format - remove leading 0 if present
            if (phoneNumber.startsWith('0')) {
                this.value = phoneNumber.substring(1);
                return;
            }
            
            this.value = phoneNumber;
            
            // Debounce validation
            validationTimeout = setTimeout(() => {
                if (phoneNumber.length >= 9) {
                    validateWhatsAppNumber(index, phoneNumber);
                } else if (phoneNumber.length > 0) {
                    showWhatsAppValidationStatus(index, 'error', 'Nomor WhatsApp minimal 9 digit');
                } else {
                    clearWhatsAppValidationStatus(index);
                }
                
                updateSubmitButtonState();
            }, 500);
        });
        
        whatsappInput.addEventListener('blur', function() {
            const phoneNumber = this.value.replace(/\D/g, '');
            if (phoneNumber.length >= 9) {
                validateWhatsAppNumber(index, phoneNumber);
            }
        });
    }

    // Real-time validation for all form fields
    const formFields = document.querySelectorAll(`[data-participant="${index}"] .form-field`);
    formFields.forEach(field => {
        field.addEventListener('input', function() {
            clearTimeout(window.submitButtonUpdateTimeout);
            window.submitButtonUpdateTimeout = setTimeout(() => {
                updateSubmitButtonState();
            }, 300);
        });
        
        field.addEventListener('change', function() {
            // Special validation for birth date
            if (this.name && this.name.includes('birth_date')) {
                validateBirthDate(this, index);
            }
            updateSubmitButtonState();
        });
    });
    
    // Initialize individual form progress
    updateIndividualFormProgress(index);
    
    // Initialize location autocomplete for this form with a small delay to ensure DOM is ready
    setTimeout(() => {
        initializeLocationAutocomplete(index);
    }, 100);
}

function validateBirthDate(input, participantIndex) {
    const selectedDate = new Date(input.value);
    const today = new Date();
    const minDate = new Date(today.getFullYear() - 10, today.getMonth(), today.getDate());
    
    if (selectedDate > minDate) {
        input.classList.add('border-red-500');
        input.classList.remove('border-gray-200', 'border-green-500');
        
        // Show error message
        showBirthDateError(participantIndex, 'Umur minimal 10 tahun');
    } else {
        input.classList.remove('border-red-500');
        input.classList.add('border-gray-200');
        
        // Clear error message
        clearBirthDateError(participantIndex);
    }
}

function showBirthDateError(participantIndex, message) {
    const birthDateInput = document.querySelector(`[data-participant="${participantIndex}"] input[name*="birth_date"]`);
    if (!birthDateInput) return;
    
    // Remove existing error
    const existingError = birthDateInput.parentNode.querySelector('.birth-date-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Add new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'birth-date-error mt-2 text-xs text-red-600';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>${message}`;
    
    birthDateInput.parentNode.appendChild(errorDiv);
}

function clearBirthDateError(participantIndex) {
    const birthDateInput = document.querySelector(`[data-participant="${participantIndex}"] input[name*="birth_date"]`);
    if (!birthDateInput) return;
    
    const existingError = birthDateInput.parentNode.querySelector('.birth-date-error');
    if (existingError) {
        existingError.remove();
    }
}

function validateWhatsAppNumber(participantIndex, phoneNumber) {
    if (!phoneNumber || phoneNumber.length < 9) {
        showWhatsAppValidationStatus(participantIndex, 'error', 'Nomor WhatsApp tidak valid');
        return;
    }

    // Show loading state
    showWhatsAppValidationStatus(participantIndex, 'loading', 'Memvalidasi nomor WhatsApp...');

    // Format to full international format
    const fullNumber = '62' + phoneNumber;
    
    // Make API call to validate
    fetch('/validate-whatsapp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            whatsapp_number: fullNumber
        })
    })
    .then(response => response.json())
    .then(data => {
        const whatsappInput = document.querySelector(`[data-participant="${participantIndex}"] .whatsapp-input`);
        
        if (data.success && data.valid) {
            whatsappInput.classList.add('border-green-500');
            whatsappInput.classList.remove('border-red-500', 'border-gray-200');
            showWhatsAppValidationStatus(participantIndex, 'success', 'Nomor WhatsApp valid dan terdaftar');
        } else if (data.success && !data.valid) {
            whatsappInput.classList.add('border-red-500');
            whatsappInput.classList.remove('border-green-500', 'border-gray-200');
            showWhatsAppValidationStatus(participantIndex, 'error', data.message || 'Nomor WhatsApp tidak valid atau tidak terdaftar');
        } else {
            // Service error - allow with warning
            whatsappInput.classList.add('border-yellow-500');
            whatsappInput.classList.remove('border-red-500', 'border-green-500', 'border-gray-200');
            showWhatsAppValidationStatus(participantIndex, 'warning', 'Service WhatsApp tidak tersedia. Registrasi tetap dapat dilanjutkan.');
        }
    })
    .catch(error => {
        console.error('WhatsApp validation error:', error);
        const whatsappInput = document.querySelector(`[data-participant="${participantIndex}"] .whatsapp-input`);
        whatsappInput.classList.add('border-red-500');
        whatsappInput.classList.remove('border-green-500', 'border-gray-200', 'border-yellow-500');
        showWhatsAppValidationStatus(participantIndex, 'error', 'Validasi WhatsApp gagal. Silakan coba lagi.');
    })
    .finally(() => {
        updateSubmitButtonState();
    });
}

function showWhatsAppValidationStatus(participantIndex, type, message) {
    const statusContainer = document.getElementById(`whatsappValidation_${participantIndex}`);
    if (!statusContainer) return;
    
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
    
    statusContainer.innerHTML = `
        <div class="${className} px-3 py-2 rounded-lg text-xs">
            <i class="${icon} mr-2"></i>${message}
        </div>
    `;
}

function clearWhatsAppValidationStatus(participantIndex) {
    const statusContainer = document.getElementById(`whatsappValidation_${participantIndex}`);
    if (statusContainer) {
        statusContainer.innerHTML = '';
    }
}

function setupFormValidation() {
    // Global form validation setup
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[type="email"]')) {
            validateEmail(e.target);
        }
        
        if (e.target.matches('input[pattern]')) {
            validatePattern(e.target);
        }
        
        // Update individual form progress when any field changes
        if (e.target.matches('.form-field')) {
            const participantIndex = e.target.getAttribute('data-participant');
            if (participantIndex) {
                updateIndividualFormProgress(participantIndex);
            }
        }
        
        // Update counters when any input changes
        if (e.target.matches('input, select, textarea')) {
            // Debounce the counter update to avoid excessive calls
            clearTimeout(window.counterUpdateTimeout);
            window.counterUpdateTimeout = setTimeout(() => {
                updateCounters();
            }, 300);
        }
    });
    
    // Also monitor change events for selects
    document.addEventListener('change', function(e) {
        if (e.target.matches('select')) {
            // Update individual form progress for selects
            if (e.target.matches('.form-field')) {
                const participantIndex = e.target.getAttribute('data-participant');
                if (participantIndex) {
                    updateIndividualFormProgress(participantIndex);
                }
            }
            
            clearTimeout(window.counterUpdateTimeout);
            window.counterUpdateTimeout = setTimeout(() => {
                updateCounters();
            }, 100);
        }
        
        // Monitor terms checkbox changes
        if (e.target.matches('#terms')) {
            updateSubmitButtonState();
        }
    });
    
    // Monitor reCAPTCHA completion
    window.onRecaptchaSuccess = function() {
        updateSubmitButtonState();
    };
    
    window.onRecaptchaExpired = function() {
        updateSubmitButtonState();
    };
    
    window.onRecaptchaError = function() {
        updateSubmitButtonState();
    };
}

function updateIndividualFormProgress(participantIndex) {
    const form = document.querySelector(`[data-participant="${participantIndex}"]`);
    if (!form) return;
    
    const allFields = form.querySelectorAll('.form-field');
    const requiredFields = form.querySelectorAll('.form-field[required]');
    const totalFields = allFields.length;
    let filledFields = 0;
    let filledRequiredFields = 0;
    
    // Count filled fields
    allFields.forEach(field => {
        if (field.value && field.value.trim() !== '') {
            filledFields++;
        }
    });
    
    // Count filled required fields
    requiredFields.forEach(field => {
        if (field.value && field.value.trim() !== '') {
            filledRequiredFields++;
        }
    });
    
    const percentage = Math.round((filledFields / totalFields) * 100);
    const requiredPercentage = Math.round((filledRequiredFields / requiredFields.length) * 100);
    
    // Update progress text
    const progressText = document.getElementById(`formProgress_${participantIndex}`);
    if (progressText) {
        progressText.textContent = `${percentage}%`;
        
        // Change color based on completion
        if (percentage >= 80) {
            progressText.className = 'font-semibold text-emerald-600';
        } else if (percentage >= 50) {
            progressText.className = 'font-semibold text-yellow-600';
        } else {
            progressText.className = 'font-semibold text-blue-600';
        }
    }
    
    // Update progress bar
    const progressBar = document.getElementById(`formProgressBar_${participantIndex}`);
    if (progressBar) {
        progressBar.style.width = `${percentage}%`;
        
        // Change color based on completion
        if (percentage >= 80) {
            progressBar.className = 'bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full transition-all duration-300 ease-out';
        } else if (percentage >= 50) {
            progressBar.className = 'bg-gradient-to-r from-yellow-500 to-yellow-600 h-2 rounded-full transition-all duration-300 ease-out';
        } else {
            progressBar.className = 'bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300 ease-out';
        }
    }
    
    // Update progress detail text
    const progressDetailText = document.getElementById(`formProgressText_${participantIndex}`);
    if (progressDetailText) {
        progressDetailText.textContent = `${filledFields} dari ${totalFields} field terisi`;
    }
    
    // Update form border based on completion
    if (requiredPercentage === 100) {
        form.classList.remove('border-gray-200', 'border-yellow-300');
        form.classList.add('border-emerald-300');
    } else if (percentage >= 50) {
        form.classList.remove('border-gray-200', 'border-emerald-300');
        form.classList.add('border-yellow-300');
    } else {
        form.classList.remove('border-yellow-300', 'border-emerald-300');
        form.classList.add('border-gray-200');
    }
}

function validateEmail(input) {
    const email = input.value;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailPattern.test(email)) {
        input.classList.add('border-red-500');
        input.classList.remove('border-gray-200');
    } else {
        input.classList.remove('border-red-500');
        input.classList.add('border-gray-200');
    }
}

function validatePattern(input) {
    const value = input.value;
    const pattern = new RegExp(input.getAttribute('pattern'));
    
    if (value && !pattern.test(value)) {
        input.classList.add('border-red-500');
        input.classList.remove('border-gray-200');
    } else {
        input.classList.remove('border-red-500');
        input.classList.add('border-gray-200');
    }
}

function validateWhatsApp(input) {
    const number = input.value;
    const validationDiv = input.parentElement.querySelector('.whatsapp-validation');
    
    if (!number) {
        validationDiv.classList.add('hidden');
        return;
    }

    validationDiv.classList.remove('hidden');
    validationDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memvalidasi nomor...';
    validationDiv.className = 'whatsapp-validation mt-2 text-sm text-blue-600';

    // API validation
    fetch('/api/validate-whatsapp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ number: number })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            validationDiv.innerHTML = '<i class="fas fa-check-circle"></i> Nomor WhatsApp valid';
            validationDiv.className = 'whatsapp-validation mt-2 text-sm text-green-600';
            input.classList.remove('border-red-500');
            input.classList.add('border-green-500');
        } else {
            validationDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Nomor WhatsApp tidak valid';
            validationDiv.className = 'whatsapp-validation mt-2 text-sm text-red-600';
            input.classList.add('border-red-500');
            input.classList.remove('border-green-500');
        }
    })
    .catch(error => {
        console.error('WhatsApp validation error:', error);
        validationDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Gagal memvalidasi nomor';
        validationDiv.className = 'whatsapp-validation mt-2 text-sm text-yellow-600';
    });
}

function updateCounters() {
    // Update participant counter with animation
    const counterElement = document.getElementById('participantCount');
    if (counterElement) {
        animateCounter(counterElement, parseInt(counterElement.textContent) || 0, participantCount);
    }

    // Calculate completed forms
    const completedForms = calculateCompletedForms();
    const completedElement = document.getElementById('completedCount');
    if (completedElement) {
        animateCounter(completedElement, parseInt(completedElement.textContent) || 0, completedForms);
    }

    // Update completion percentage
    updateCompletionPercentage(completedForms, participantCount);

    // Update add button state
    const addButton = document.getElementById('addParticipantBtn');
    if (addButton) {
        if (participantCount >= maxParticipants) {
            addButton.disabled = true;
            addButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            addButton.disabled = false;
            addButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Update status indicator for minimum participants
    updateMinimumParticipantsIndicator();
    
    // Update submit button state based on form completion
    updateSubmitButtonState();
}

function updateMinimumParticipantsIndicator() {
    const statusContainer = document.getElementById('participantsStatusContainer');
    if (!statusContainer) return;
    
    // Remove existing indicator
    const existingIndicator = statusContainer.querySelector('.minimum-participants-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    // Add indicator based on current count
    const indicator = document.createElement('div');
    indicator.className = 'minimum-participants-indicator text-xs mt-1';
    
    if (participantCount === 5) {
        indicator.innerHTML = '<span class="text-amber-600 font-medium"><i class="fas fa-info-circle mr-1"></i>Minimum tercapai (tidak dapat menghapus form)</span>';
    } else if (participantCount > 5) {
        indicator.innerHTML = `<span class="text-green-600 font-medium"><i class="fas fa-check-circle mr-1"></i>Form dapat dihapus (${participantCount - 5} di atas minimum)</span>`;
    }
    
    statusContainer.appendChild(indicator);
}

function updateSubmitButtonState() {
    const submitBtn = document.getElementById('submitBtn');
    if (!submitBtn) return;
    
    // Check if all forms are completed
    const allFormsComplete = checkAllFormsComplete();
    const completedForms = calculateCompletedForms();
    const completionPercentage = Math.round((completedForms / participantCount) * 100);
    
    console.log('=== SUBMIT BUTTON STATE DEBUG ===');
    console.log('All forms complete:', allFormsComplete);
    console.log('Completed forms:', completedForms);
    console.log('Total participants:', participantCount);
    console.log('Completion percentage:', completionPercentage);
    
    if (allFormsComplete) {
        // Enable submit button
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-400');
        submitBtn.classList.add('bg-gradient-to-r', 'from-red-600', 'to-red-700', 'hover:from-red-700', 'hover:to-red-800', 'hover:shadow-2xl', 'transform', 'hover:-translate-y-1');
        
        // Update button text with success indicator
        submitBtn.innerHTML = `
            <i class="fas fa-check-circle mr-3 animate-pulse"></i>
            Daftarkan Semua Peserta
            <i class="fas fa-arrow-right ml-3"></i>
        `;
        
        submitBtn.title = `Semua ${participantCount} peserta telah melengkapi data. Siap untuk didaftarkan!`;
        
    } else {
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-400');
        submitBtn.classList.remove('bg-gradient-to-r', 'from-red-600', 'to-red-700', 'hover:from-red-700', 'hover:to-red-800', 'hover:shadow-2xl', 'transform', 'hover:-translate-y-1');
        
        // Update button text with progress indicator
        submitBtn.innerHTML = `
            <i class="fas fa-exclamation-triangle mr-3 text-yellow-600"></i>
            Lengkapi Data (${completedForms}/${participantCount} - ${completionPercentage}%)
            <i class="fas fa-lock ml-3"></i>
        `;
        
        const missingCount = participantCount - completedForms;
        submitBtn.title = `Masih ada ${missingCount} peserta yang belum melengkapi data. Progress: ${completionPercentage}%`;
    }
    
    // Add visual indicator in submit button area
    updateSubmitAreaIndicator(allFormsComplete, completedForms, participantCount);
}

function updateSubmitAreaIndicator(allComplete, completed, total) {
    // Find or create indicator container
    let indicatorContainer = document.getElementById('submitIndicatorContainer');
    if (!indicatorContainer) {
        const submitBtn = document.getElementById('submitBtn');
        if (!submitBtn) return;
        
        indicatorContainer = document.createElement('div');
        indicatorContainer.id = 'submitIndicatorContainer';
        indicatorContainer.className = 'text-center mt-3';
        
        // Insert after submit button
        submitBtn.parentNode.insertBefore(indicatorContainer, submitBtn.nextSibling);
    }
    
    // Update indicator content
    if (allComplete) {
        indicatorContainer.innerHTML = `
            <div class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-200 rounded-lg text-green-700">
                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                <span class="font-medium">Semua data telah lengkap dan siap didaftarkan!</span>
            </div>
        `;
    } else {
        const percentage = Math.round((completed / total) * 100);
        indicatorContainer.innerHTML = `
            <div class="inline-flex items-center px-4 py-2 bg-amber-50 border border-amber-200 rounded-lg text-amber-700">
                <i class="fas fa-clock mr-2 text-amber-500"></i>
                <span class="font-medium">Progress: ${completed}/${total} peserta (${percentage}%) - Lengkapi semua data untuk melanjutkan</span>
            </div>
        `;
    }
}

function checkAllFormsComplete() {
    const forms = document.querySelectorAll('.participant-form');
    console.log('=== FORMS COMPLETION CHECK ===');
    console.log('Total forms found:', forms.length);
    
    for (let i = 0; i < forms.length; i++) {
        const form = forms[i];
        console.log(`\n--- Checking Form ${i + 1} ---`);
        
        const requiredInputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        console.log(`Required inputs in form ${i + 1}:`, requiredInputs.length);
        
        for (let j = 0; j < requiredInputs.length; j++) {
            const input = requiredInputs[j];
            const isEmpty = !input.value.trim();
            console.log(`Input ${j + 1} (${input.name || input.type}): "${input.value}" - Empty: ${isEmpty}`);
            
            if (isEmpty) {
                console.log(`❌ Form ${i + 1} incomplete: ${input.name || input.type} is empty`);
                return false;
            }
        }
        
        // Check birth date age validation
        const birthDateInput = form.querySelector('input[name*="birth_date"]');
        if (birthDateInput && birthDateInput.value) {
            const selectedDate = new Date(birthDateInput.value);
            const today = new Date();
            const minDate = new Date(today.getFullYear() - 10, today.getMonth(), today.getDate());
            
            console.log(`Birth date check - Selected: ${selectedDate}, Min allowed: ${minDate}`);
            
            if (selectedDate > minDate) {
                console.log(`❌ Form ${i + 1} age validation failed: age is less than 10 years`);
                return false; // Age is less than 10 years
            }
        }
        
        // Check specific validations
        const whatsappInput = form.querySelector('.whatsapp-input');
        if (whatsappInput) {
            const phoneNumber = whatsappInput.value.replace(/\D/g, '');
            const hasError = whatsappInput.classList.contains('border-red-500');
            console.log(`WhatsApp check - Number: ${phoneNumber}, Length: ${phoneNumber.length}, Has error: ${hasError}`);
            
            if (!phoneNumber || phoneNumber.length < 9) {
                console.log(`❌ Form ${i + 1} WhatsApp validation failed: invalid number`);
                return false;
            }
            // Check if WhatsApp field has error styling
            if (hasError) {
                console.log(`❌ Form ${i + 1} WhatsApp validation failed: has error styling`);
                return false;
            }
        }
        
        const emailInput = form.querySelector('input[type="email"]');
        if (emailInput) {
            const emailValid = isValidEmail(emailInput.value);
            console.log(`Email check - Value: ${emailInput.value}, Valid: ${emailValid}`);
            
            if (!emailValid) {
                console.log(`❌ Form ${i + 1} email validation failed`);
                return false;
            }
        }
        
        console.log(`✅ Form ${i + 1} is complete`);
    }
    
    // Check terms agreement - using the global isTermsAccepted variable
    console.log('Terms agreement check - isTermsAccepted:', typeof window.isTermsAccepted !== 'undefined' ? window.isTermsAccepted : 'undefined');
    
    // If isTermsAccepted is not defined or false, terms not accepted
    if (typeof window.isTermsAccepted === 'undefined' || !window.isTermsAccepted) {
        console.log('❌ Terms not agreed');
        return false;
    }
    
    console.log('✅ All forms and terms are complete');
    return true;
}

function isValidWhatsApp(whatsapp) {
    const cleanWhatsApp = whatsapp.replace(/\D/g, '');
    return cleanWhatsApp.length >= 10 && cleanWhatsApp.length <= 15;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function animateCounter(element, startValue, endValue, suffix = '') {
    if (startValue === endValue) return;
    
    const duration = 800; // Animation duration in ms
    const startTime = performance.now();
    
    function updateValue(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Easing function for smooth animation
        const easeOutCubic = 1 - Math.pow(1 - progress, 3);
        const currentValue = Math.round(startValue + (endValue - startValue) * easeOutCubic);
        
        element.textContent = currentValue + suffix;
        
        if (progress < 1) {
            requestAnimationFrame(updateValue);
        }
    }
    
    requestAnimationFrame(updateValue);
}

function calculateCompletedForms() {
    const forms = document.querySelectorAll('.participant-form');
    let completed = 0;
    
    forms.forEach(form => {
        const requiredInputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let formCompleted = true;
        
        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                formCompleted = false;
            }
        });
        
        if (formCompleted) {
            completed++;
        }
    });
    
    return completed;
}

function updateCompletionPercentage(completed, total) {
    const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;
    
    // Update progress bar if exists
    const progressBar = document.getElementById('completionProgressBar');
    if (progressBar) {
        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);
    }
    
    // Update percentage text if exists
    const percentageText = document.getElementById('completionPercentage');
    if (percentageText) {
        animateCounter(percentageText, parseInt(percentageText.textContent) || 0, percentage, '%');
    }
    
    // Update progress text
    const progressText = document.getElementById('progressText');
    if (progressText) {
        progressText.textContent = `${completed} dari ${total} selesai`;
    }
    
    // Add visual feedback based on completion
    const completedElement = document.getElementById('completedCount');
    if (completedElement) {
        completedElement.className = `text-2xl font-bold transition-all duration-300 ${
            percentage >= 80 ? 'text-emerald-600' : 
            percentage >= 50 ? 'text-yellow-600' : 
            'text-gray-600'
        }`;
    }
    
    // Update percentage element color
    if (percentageText) {
        percentageText.className = `text-2xl font-bold transition-all duration-300 ${
            percentage >= 80 ? 'text-emerald-600' : 
            percentage >= 50 ? 'text-yellow-600' : 
            'text-purple-600'
        }`;
    }
}

function setupSubmitHandler() {
    const form = document.getElementById('collectiveRegistrationForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Always prevent default first
            
            if (!validateAllForms()) {
                alert('Mohon lengkapi semua field yang diperlukan');
                return false;
            }

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            }

            // Execute reCAPTCHA v3
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.ready(function() {
                    grecaptcha.execute(window.recaptchaSiteKey, {action: 'collective_register'}).then(function(token) {
                        // Add reCAPTCHA token to form
                        let recaptchaInput = form.querySelector('input[name="g-recaptcha-response"]');
                        if (!recaptchaInput) {
                            recaptchaInput = document.createElement('input');
                            recaptchaInput.type = 'hidden';
                            recaptchaInput.name = 'g-recaptcha-response';
                            form.appendChild(recaptchaInput);
                        }
                        recaptchaInput.value = token;

                        // Submit the form
                        form.submit();
                    }).catch(function(error) {
                        console.error('reCAPTCHA error:', error);
                        alert('Gagal memverifikasi reCAPTCHA. Silakan coba lagi.');
                        
                        // Reset submit button
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fas fa-users mr-2"></i>Daftarkan Semua Peserta';
                        }
                    });
                });
            } else {
                // If reCAPTCHA not loaded, submit anyway
                console.warn('reCAPTCHA not loaded, submitting form without verification');
                form.submit();
            }
        });
    }
}

function validateAllForms() {
    const forms = document.querySelectorAll('.participant-form');
    let allValid = true;

    forms.forEach(form => {
        const requiredInputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        
        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('border-red-500');
                allValid = false;
            } else {
                input.classList.remove('border-red-500');
            }
        });
    });

    return allValid;
}

// Global functions for button handlers
window.addParticipantForm = addParticipantForm;
window.removeParticipantForm = removeParticipantForm;

// Add debug functions to window for manual testing
window.debugSubmitButton = function() {
    console.log('=== MANUAL DEBUG TRIGGER ===');
    updateSubmitButtonState();
};

window.debugCheckForms = function() {
    console.log('=== MANUAL FORMS CHECK ===');
    return checkAllFormsComplete();
};

// Add CSS for notification animations
const notificationStyles = `
<style>
.slide-in-right {
    animation: slideInRight 0.5s ease-out forwards;
}

.slide-out-right {
    animation: slideOutRight 0.5s ease-in forwards;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Fix WhatsApp input layout */
.whatsapp-input-group {
    display: flex;
    align-items: stretch;
    width: 100%;
}

.whatsapp-prefix {
    flex-shrink: 0;
    min-width: auto;
    white-space: nowrap;
}

.whatsapp-input {
    flex: 1;
    min-width: 0;
    border-left: 0 !important;
}

/* Ensure proper border radius connection */
.whatsapp-prefix + .whatsapp-input {
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
}

/* Form grid responsiveness */
@media (max-width: 1024px) {
    .participant-form .grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

/* Input focus states */
.whatsapp-input:focus + .whatsapp-prefix,
.whatsapp-prefix:focus-within + .whatsapp-input {
    border-color: #ef4444;
}
</style>
`;

// Inject notification styles if not already present
if (!document.querySelector('#notification-styles')) {
    const styleElement = document.createElement('div');
    styleElement.id = 'notification-styles';
    styleElement.innerHTML = notificationStyles;
    document.head.appendChild(styleElement);
}

// Initialize location autocomplete for a specific participant form
function initializeLocationAutocomplete(participantIndex) {
    console.log(`Attempting to initialize location autocomplete for participant ${participantIndex}`);
    
    // Check if LocationAutocomplete is available
    if (typeof LocationAutocomplete === 'undefined') {
        console.error('LocationAutocomplete class not available! Make sure location-autocomplete.js is loaded.');
        return;
    }
    
    console.log('LocationAutocomplete class is available');
    
    const regencySearchSelector = `[data-participant="${participantIndex}"] input[name*="regency_search"]`;
    const regencySearchInput = document.querySelector(regencySearchSelector);
    
    console.log(`Looking for input with selector: ${regencySearchSelector}`);
    console.log('Found input element:', regencySearchInput);
    
    if (regencySearchInput) {
        try {
            console.log(`Creating LocationAutocomplete instance for participant ${participantIndex}`);
            // Initialize autocomplete for this specific input using selector string
            const autocomplete = new LocationAutocomplete(regencySearchSelector);
            
            console.log(`LocationAutocomplete instance created successfully for participant ${participantIndex}`);
            
            // Listen for location selection
            regencySearchInput.addEventListener('locationSelected', function(e) {
                const selection = e.detail;
                
                console.log(`Location selected for participant ${participantIndex}:`, selection);
                
                // Update hidden fields for this participant
                const regencyIdField = document.querySelector(`#regency_id_${participantIndex}`);
                const regencyNameField = document.querySelector(`[data-participant="${participantIndex}"] input[name*="regency_name"]`);
                const provinceNameField = document.querySelector(`[data-participant="${participantIndex}"] input[name*="province_name"]`);
                
                if (regencyIdField) regencyIdField.value = selection.id;
                if (regencyNameField) regencyNameField.value = selection.name;
                if (provinceNameField) provinceNameField.value = selection.province_name;
                
                // Trigger form progress update
                updateIndividualFormProgress(participantIndex);
                updateSubmitButtonState();
            });
            
            console.log(`Location autocomplete fully initialized for participant ${participantIndex}`);
        } catch (error) {
            console.error(`Error initializing location autocomplete for participant ${participantIndex}:`, error);
        }
    } else {
        console.error(`Regency search input not found for participant ${participantIndex} with selector: ${regencySearchSelector}`);
    }
}
