@extends('layouts.guest')

@section('title', 'Wakaf Registration - Amazing Sultra Run 2025')

@section('content')
<style>
/* Additional styles for terms modal */
#termsContent::-webkit-scrollbar {
    width: 8px;
}

#termsContent::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

#termsContent::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 4px;
}

#termsContent::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

.modal-backdrop {
    backdrop-filter: blur(4px);
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

/* Smooth progress bar animation */
#progressBar {
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Button state transitions */
#acceptTermsBtn {
    transition: all 0.3s ease;
}

/* Success message animation */
.slide-in-right {
    animation: slideInRight 0.5s ease-out;
}

/* Wakaf amount input styling */
#wakaf_amount::-webkit-outer-spin-button,
#wakaf_amount::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

#wakaf_amount[type=number] {
    -moz-appearance: textfield;
}

/* Format display for wakaf amount */
#wakaf_amount::placeholder {
    color: #9ca3af;
    font-style: italic;
}

/* Hover effect for wakaf amount */
#wakaf_amount:hover {
    border-color: #10b981;
    box-shadow: 0 0 0 1px #10b981;
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

.slide-out-right {
    animation: slideOutRight 0.5s ease-in;
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
</style>

<!-- SatuWakaf Payment Modal -->
<div id="wakafPaymentModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity modal-backdrop" aria-hidden="true"></div>
        
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-hand-holding-heart text-green-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                            Wakaf Asrama Tahifdz Quran Al Hijrah
                        </h3>
                        
                        <!-- Wakaf Form -->
                        <div id="wakafFormSection">
                            <div class="space-y-4">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-green-800 mb-2">Program Wakaf Amazing Sultra Run 2025</h4>
                                    <p class="text-green-700 text-sm">
                                        Untuk mengikuti kategori 5K Wakaf, Anda perlu melakukan donasi wakaf terlebih dahulu. 
                                        untuk mendukung Asrama Tahifdz Quran Al Hijrah di Sulawesi Tenggara.
                                    </p>
                                    <!-- Campaign Image -->
                                    <div class="mt-6">
                                        <img src="https://apps.satuwakaf.id/_next/image?url=https%3A%2F%2Fstorage.googleapis.com%2Fziswaf-asset-prod%2Fimages%2Fcampaigns%2F9Wyk18rXVMMAMbL860A6.jpg&w=1920&q=75" 
                                            alt="Wakaf Asrama Tahifdz Quran Al Hijrah" 
                                            class="h-auto max-h-64 object-contain rounded-lg shadow-md">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="wakaf_wakif_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Wakif <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                               id="wakaf_wakif_name" 
                                               placeholder="Nama untuk wakif"
                                               required>
                                    </div>
                                    <div>
                                        <label for="wakaf_email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                               id="wakaf_email" 
                                               placeholder="email@contoh.com"
                                               required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="wakaf_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nomor HP <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md">
                                                +62
                                            </span>
                                            <input type="text" 
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                                   id="wakaf_phone" 
                                                   placeholder="8123456789 (tanpa 0)"
                                                   maxlength="13"
                                                   required>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Masukkan nomor tanpa awalan 0. Contoh: 8123456789</p>
                                    </div>
                                    <div>
                                        <label for="wakaf_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                            Jumlah Wakaf <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                            <input type="number" 
                                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                                   id="wakaf_amount" 
                                                   name="wakaf_amount"
                                                   min="10000"
                                                   step="1000"
                                                   placeholder=""
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div id="wakafPaymentSection" class="hidden">
                            <div class="text-center">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <h4 class="font-semibold text-blue-800 mb-2">Pembayaran Wakaf</h4>
                                    <p class="text-blue-700 text-sm mb-2">
                                        Scan QR Code berikut untuk melakukan pembayaran wakaf
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        ID Transaksi: <span id="transaction_id" class="font-mono font-semibold"></span>
                                    </p>
                                </div>

                                <div id="qr_code_container" class="mb-4">
                                    <!-- QR Code will be inserted here -->
                                </div>

                                <div class="space-y-2 text-sm text-gray-600">
                                    <p><strong>Jumlah:</strong> <span id="payment_amount"></span></p>
                                    <p><strong>Metode:</strong> QRIS</p>
                                    <p><strong>Batas Waktu:</strong> <span id="payment_expired"></span></p>
                                </div>

                                <div id="payment_status" class="mt-4">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-clock text-yellow-600 mr-2"></i>
                                            <span class="text-yellow-800 text-sm">Menunggu pembayaran...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Success Section -->
                        <div id="wakafSuccessSection" class="hidden">
                            <div class="text-center">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                    <i class="fas fa-check-circle text-green-600 text-4xl mb-4"></i>
                                    <h4 class="font-semibold text-green-800 mb-2">Wakaf Berhasil!</h4>
                                    <p class="text-green-700 text-sm mb-4">
                                        Terima kasih atas kontribusi wakaf Anda. Pembayaran telah terverifikasi.
                                    </p>
                                    <p class="text-sm text-gray-600 mb-4">
                                        ID Transaksi: <span id="verified_transaction_id" class="font-mono font-semibold"></span>
                                    </p>
                                    <button type="button" id="continueToRegistration" 
                                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-arrow-right mr-2"></i>Lanjutkan ke Registrasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="processWakafBtn" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <i class="fas fa-hand-holding-heart mr-2"></i>
                    <span>Proses Wakaf</span>
                </button>
                <button type="button" id="cancelWakafBtn" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Ikrar Wakaf Modal -->
<div id="ikrarWakafModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity modal-backdrop" aria-hidden="true"></div>
        
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="text-center">
                    <!-- Bismillah Image -->
                    <div class="mb-6">
                        <img src="https://apps.satuwakaf.id/assets/bismillah.svg" 
                             alt="Bismillah" 
                             class="mx-auto h-16 w-auto">
                    </div>
                    
                    <!-- Ikrar Wakaf Title -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            Ikrar Wakaf
                        </h3>
                        
                        <!-- Ikrar Text -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-justify">
                            <p class="text-gray-800 leading-relaxed">
                                Dengan ini saya sebagai Wakif telah mengikrarkan Wakaf kepada 
                                <strong>Dompet Dhuafa Sulawesi Tenggara</strong> untuk program 
                                <strong>Wakaf Asrama Tahifdz Quran Al Hijrah di Sulawesi Tenggara</strong> 
                                sebesar <strong class="text-green-600" id="ikrarAmount">Rp 0</strong> 
                                dalam bentuk Wakaf.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 text-center">
                <button type="button" id="confirmIkrarBtn" 
                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-6 py-3 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="fas fa-check-circle mr-2"></i>
                    Saya sudah baca Ikrar Wakaf dan lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div id="termsModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity modal-backdrop" aria-hidden="true"></div>
        
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                            Syarat dan Ketentuan Amazing Sultra Run 2025
                        </h3>
                        
                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Progress Membaca</span>
                                <span id="readProgress">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <p class="text-xs text-orange-600 mt-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Harap baca hingga akhir untuk melanjutkan registrasi
                            </p>
                        </div>
                        
                        <!-- Scrollable Content -->
                        <div id="termsContent" class="mt-4 max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="space-y-4 text-sm text-gray-700">
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-blue-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="font-bold text-blue-900 mb-2">SYARAT & KETENTUAN</h4>
                                            <p class="text-sm text-blue-700">
                                                Dengan mengambil bagian dalam Amazing Sultra Run 2025 (selanjutnya disebut "Acara") termasuk tetapi tidak terbatas pada mendaftar melalui website lomba, seorang pendaftar (selanjutnya disebut "PESERTA") menerima dan menyetujui untuk mematuhi dan terikat atas segala aturan dan peraturan, syarat dan ketentuan Acara yang diterapkan oleh Penyelenggara dan AMAZING SULTRA RUN (selanjutnya disebut sebagai "AMAZING SULTRA RUN").
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>1)</strong> PESERTA memastikan dan menyatakan kebenaran segala informasi yang diberikan pada saat melakukan pendaftaran. Penyelenggara dan AMAZING SULTRA RUN memiliki hak untuk mewajibkan PESERTA menunjukkan dokumen pengenal resmi (seperti KTP, paspor atau SIM) agar dapat berpartisipasi di Acara ini, jika dipandang perlu. Jika menurut Penyelenggara dan AMAZING SULTRA RUN ditemukan ketidakcocokan, PESERTA dapat didiskualifikasi dari Acara.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>2)</strong> PESERTA bertanggung jawab penuh atas dan menanggung segala risiko berupa cedera badan, kematian, atau kerusakan properti yang mungkin diderita atau didapat PESERTA karena berpartisipasi di Acara ini dan dimana cedera, kematian atau kerusakan properti mungkin muncul, sebagai akibat, atau sebagai bagian dari atau disebabkan oleh atau karena kelalaian dan/ atau pengabaian ketentuan-ketentuan (seperti yang ditunjukkan di bawah) atau salah satu dari mereka atau hal lainnya, dan terlepas hal yang sama terjadi sebelum, sesudah atau ketika berkompetisi dan/atau berpartisipasi di Acara. PESERTA mengerti dan memahami bahwa terdapat risiko-risiko dan bahaya-bahaya yang berkaitan dengan keikutsertaan di Acara ini yang dapat menyebabkan cedera badan, cacat, dan kematian. Segala risiko dan bahaya yang berkaitan dengan partisipasi di Acara ini dipahami oleh PESERTA terlepas segala risiko dan bahaya mungkin disebabkan oleh pengabaian ketentuan-ketentuan dan lainnya.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>3)</strong> Sehubungan dengan risiko-risiko yang ditanggung oleh PESERTA, maka PESERTA melepaskan, mengabaikan, dan setuju untuk tidak menuntut Penyelenggara/promotor, AMAZING SULTRA RUN, para peserta, Sponsor Acara and agen PR Acara, rekanan yang berpartisipasi, organisasi yang menjamin, (atau afiliasi lainnya), pejabat resmi, pemilik kendaraan, pengemudi, para sponsor, pemasang iklan, para pemilik, para penyewa, pemberi sewa dari lokasi lomba,yang menyelenggarakan Acara dan para petugas, para agen, dan para karyawan (untuk keperluan yang disebutkan ini akan disebut sebagai Pers) dari segala kewajiban kepada diri anda, perwakilan pribadi anda, pihak yang ditunjuk, dan para pelaksanan, dari segala dan seluruh klaim, tuntutan, kerugian atau kerusakan dari PESERTA atau kerusakan properti, terlepas hal tersebut terjadi atau disebabkan atau diduga sebagai akibat baik keseluruhan maupun sebagian karena kelalaian Pers atau lainnya.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>4)</strong> Dengan ini PESERTA setuju bahwa Pelepasan dan Pengabaian Tanggung Jawab, Asumsi Risiko dan Perjanjian Kerusakan sebagaimana dimaksud dalam poin 3 diatas, termasukan kelalaian operasi penyelamatan (jika ada) dan dimaksudkan seluas mungkin dan mencakup sebanyak mungkin sebagaimana yang diijinkan oleh hukum Indonesia dimana Acara ini dilaksanakan.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>5)</strong> PESERTA memahami bahwa uang pendaftaran yang dibayarkan PESERTA kepada Penyelenggara Acara, Penyelenggara Acara akan mengeluarkan biaya yang besar dan pengeluaran untuk penyelenggaraan Acara. Dengan ini PESERTA menyetujui bahwa jika terjadi kondisi dimana lomba dibatalkan disebabkan oleh kondisi yang di luar kuasa penyelanggara Acara dan AMAZING SULTRA RUN termasuk tapi tidak terbatas pada badai, hujan, pasang laut atau cuaca, angin, atau kuasa Tuhan atau tindakan terorisme atau kondisi-kondisi lainnya, uang pendaftaran PESERTA tidak dapat dikembalikan.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>6)</strong> Setelah pendaftaran selesai dilaksanakan, PESERTA yang akhirnya tidak dapat berpartisipasi di Acara tidak akan mendapatkan pengembalian uang pendaftaran. Slot lomba tidak dapat dipindahkan kepada orang lain atau PESERTA tidak boleh mengubah kategori lomba menjadi kategori jarak yang lebih tinggi atau kategori jarak yang lebih rendah.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>7)</strong> Salinan surat elektronik konfirmasi bersama dengan satu (1) buah tanda pengenal resmi harus ditunjukkan oleh PESERTA ketika mengambil paket lomba. Surat kuasa yang disertai dengan salinan tanda pengenal dibutuhkan jika PESERTA mengambil paket lomba atas nama PESERTA yang lain.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>8)</strong> Paket lomba harus diambil sesuai dengan hari pengambilan yang sudah dijadwalkan. Permintaan untuk mengambil setelah hari yang sudah ditetapkan tidak akan dilayani.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>9)</strong> PESERTA yang berusia di bawah 15 tahun harus didampingi oleh orang tua atau wali ketika mengambil paket lomba. Orang tua atau wali akan diminta untuk menandatangani surat persetujuan dan menunjukkan tanda pengenal resmi berupa KTP/Paspor/SIM.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>10)</strong> PESERTA yang tidak memulai lomba sesuai kategorinya tidak akan diperbolehkan berpartisipasi dan secara otomatis akan didiskualifikasi dari lomba. Sama halnya, PESERTA yang memulai lomba lebih awal dari kategori yang dia ikuti juga akan didiskualifikasi.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>11)</strong> PESERTA diwajibkan untuk menggunakan nomor dada lomba yang telah disediakan. PESERTA yang tidak menggunakan nomor dada akan diminta untuk keluar dari rute oleh petugas keamanan dan/ atau Penyelenggara.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>12)</strong> Penutupan jalan akan berakhir setelah dua (2) jam sejak lomba dimulai. PESERTA yang meneruskan lomba diperbolehkan untuk melanjutkan lomba tetapi dengan menanggung sendiri atas risiko yang mungkin muncul: (a) Penyelenggara dan/atau AMAZING SULTRA RUN memiliki hak untuk sewaktu-waktu mengakhiri penutupan jalan lebih awal dari yang dijadwalkan atas kebijakan Penyelenggara dan/atau AMAZING SULTRA RUN; dan (b) Mengakhiri penutupan jalan berdasarkan persetujuan dari pihak pemerintah yang berwenang jika penutupan jalan berakhir lebih awal.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>13)</strong> Binatang peliharaan, sepeda, sepatu roda, kereta bayi, kereta dorong, sepatu yang beroda atau dapat dipasang roda dan objek lain yang memiliki roda tidak diperbolehkan untuk berada di rute selain kendaraan lomba resmi dan kendaraan medis.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>14)</strong> Rute lomba dapat diubah sewaktu-waktu untuk alasan keamanan peserta dan pertimbangan lalu lintas.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>15)</strong> Semua pemenang wajib hadir pada saat Acara Penyerahan Hadiah untuk menerima penghargaan dan hadiah. Penyelenggara memiliki hak untuk tidak memberikan hadiah (baik dalam bentuk uang tunai maupun bentuk lain) untuk pemenang-pemenang yang tidak hadir pada saat Acara Penyerahan Hadiah.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>16)</strong> Keputusan Penyelenggara bersifat final. Korespondensi atau perselisihan lebih lanjut tidak akan dilayani.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>17)</strong> Para pemenang podium diminta untuk menunjukkan tanda pengenal yang sah dan diakui Penyelenggara dan AMAZING SULTRA RUN untuk mengklaim penghargaan dan hadiah.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>18)</strong> Untuk para pemenang podium atau para pemenang podium potensial, perselisihan dan protes harus disampaikan di tempat dalam waktu tiga puluh (30) menit setelah hasil dipublikasikan di papan pengumuman hasil lomba atau tepat setelah upAcara pemenang, yang mana yang lebih dulu.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>19)</strong> Segala hadiah diberikan berdasarkan persediaan dimana Penyelenggara dan AMAZING SULTRA RUN memiliki hak untuk membatalkan, mengubah, mengganti atau membatalkan segala hadiah sewaktu-waktu dengan atau tanpa melakukan pemberitahuan kepada Peserta.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>20)</strong> Segala hadiah dan premium tidak dapat dipertukarkan dan tidak dapat diuangkan atas segala kondisi apapun.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>21)</strong> Penyelenggara memilki hak untuk mewajibkan PESERTA menunjukkan dokumen pengenal resmi (seperti KTP, Paspor, KITAS atau SIM) yang dibutuhkan untuk mendukung terjadinya perselisihan atau pengajuan protes.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>22)</strong> Dengan berpartisipasi di Acara ini, PESERTA setuju untuk ambil bagian dalam segala promosi atau publikasi yang akan dilakukan oleh AMAZING SULTRA RUN dan dengan ini PESERTA dan tanpa syarat memberikan hak kepada AMAZING SULTRA RUN untuk merekam dan menggunakan performa peserta, penampilan, kesukaan, nama, suara dan/atau hal tertentu dari PESERTA (jika dimungkinkan) di segala cara yang AMAZING SULTRA RUN anggap pantas. PESERTA mengetahui bahwa AMAZING SULTRA RUN akan memiliki kebebasan untuk mempublikasi dan menggunakan segala bentuk rekaman yang dibuat oleh AMAZING SULTRA RUN, termasuk tapi tidak terbatas pada rekaman telefon, rekaman suara, rekaman visual dan foto (jika ada), untuk keperluan promosi dan publikasi Acara (sekarang atau di masa yang akan datang). Jika dimungkinkan, setiap PESERTA mengabaikan segala bentuk hak cipta intelektual yang PESERTA mungkin dapatkan atau punya di bawah hukum (dan segala aturan lanjutan atau amendemen lebih lanjut) berkaitan dengan rekaman-rekaman yang sudah disebutkan tadi dan bentuk lainnya atau hak moral yang peserta mungkin punya atau dapatkan atas nama hukum.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>23)</strong> Penyelenggara dan/ atau AMAZING SULTRA RUN memiliki hak untuk membatasi dan/atau menolak pendaftaran PESERTA untuk mengikuti Acara tanpa perlu memberitahukan alasan apa pun.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>24)</strong> Penyelenggara dan/ atau AMAZING SULTRA RUN memiliki hak untuk mengubah segala syarat-syarat dan ketentuan-ketentuan dan/atau menghentikan Acara berdasarkan kebijakan Penyelenggara dan/ atau AMAZING SULTRA RUN dan itu dilakukan tanpa pemberitahuan sebelumnya.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>25)</strong> Bahwa dengan ini Peserta mengetahui dan menyetujui apabila terdapat Peserta yang tidak kunjung mengambil BIB pada waktu yang ditentukan (pada saat Racepack Collection yaitu tanggal 05-06 September 2025) kemudian tidak ada konfirmasi kepada pihak penyelenggara, maka BIB dinyatakan hangus/tidak dapat digunakan oleh Peserta.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>26)</strong> Sehubungan dengan hal tersebut kami selaku Pihak Amazing Sultra Run 2025 berharap bahwa Peserta dapat mengambil Race Pack pada tgl 05-06 September 2025.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>27)</strong> Dengan adanya hal tersebut maka Peserta membebaskan Pihak Penyelenggara AMAZING SULTRA RUN 2025 dari segala jenis tanggung jawab dan/atau ganti rugi dalam bentuk apapun.
                                    </p>
                                </div>
                                
                                <div class="bg-red-50 border-l-4 border-red-400 p-4 mt-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-red-700">
                                                <strong>PENTING:</strong> Dengan mendaftar dalam Amazing Sultra Run 2025, peserta dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku di atas.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center mt-6 pt-4 border-t border-gray-300">
                                    <p class="text-sm text-gray-600 font-medium">
                                        Amazing Sultra Run 2025
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Untuk informasi lebih lanjut, hubungi panitia melalui kontak yang tersedia.
                                    </p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Dokumen ini telah Anda baca hingga akhir. Terima kasih.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="acceptTermsBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-400 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm cursor-not-allowed" disabled>
                    <i class="fas fa-spinner fa-spin mr-2" id="loadingIcon"></i>
                    <span id="btnText">Membaca... (0%)</span>
                </button>
                <button type="button" id="closeTermsBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<div class="form-container">
    <div class="max-w-4xl mx-auto p-4">
        <div class="glass-effect rounded-2xl overflow-hidden form-card">
            <!-- Header with Wakaf Badge -->
            <div class="bg-gradient-to-r from-red-600 to-red-800 text-white p-8">
                <div class="flex flex-col sm:flex-row items-center justify-between">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-bold mb-2">Amazing Sultra Run 2025</h1>
                        <p class="text-red-100 text-lg">Wakaf Registration - Kategori 5K</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <div class="bg-green-500 text-white px-4 py-2 rounded-full flex items-center gap-2">
                            <i class="fas fa-hand-holding-heart"></i>
                            <span class="font-semibold">Program Wakaf</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-2xl font-bold">5K</div>
                        <div class="text-sm text-red-100">Kategori Khusus</div>
                    </div>
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-2xl font-bold">{{ $wakafTicketType ? 'Rp ' . number_format($wakafTicketType->price, 0, ',', '.') : 'Rp 0' }}</div>
                        <div class="text-sm text-red-100">Harga Tiket</div>
                    </div>
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-2xl font-bold">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="text-sm text-red-100">Event Date TBA</div>
                    </div>
                </div>
            </div>
            
            <!-- Session Messages -->
            <div class="px-8 pt-4">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <i class="fas fa-check-circle mr-3 text-green-600"></i>
                        <div>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                
                @if (session('warning'))
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3 text-yellow-600"></i>
                        <div>
                            <p class="font-medium">{{ session('warning') }}</p>
                        </div>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <i class="fas fa-times-circle mr-3 text-red-600"></i>
                        <div>
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
                
                @if ($errors->has('wakaf'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <i class="fas fa-times-circle mr-3 text-red-600"></i>
                        <div>
                            <p class="font-medium">{{ $errors->first('wakaf') }}</p>
                        </div>
                    </div>
                @endif
                
                @if ($errors->has('payment'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <i class="fas fa-credit-card mr-3 text-red-600"></i>
                        <div>
                            <p class="font-medium">{{ $errors->first('payment') }}</p>
                        </div>
                    </div>
                @endif
                
                @if ($errors->has('registration'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <i class="fas fa-user-times mr-3 text-red-600"></i>
                        <div>
                            <p class="font-medium">{{ $errors->first('registration') }}</p>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Form Content -->
            <div class="p-8 sm:p-8 p-4">
                <form id="registrationForm" method="POST" action="{{ route('register.wakaf.post') }}" class="space-y-8 sm:space-y-8 space-y-6">
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

                        <div>
                            <label for="regency_search" class="block text-sm font-medium text-gray-700 mb-2">
                                Kota/Kabupaten Tempat Tinggal Saat Ini <span class="text-red-500">*</span>
                            </label>
                            <div class="location-autocomplete-container">
                                <input type="text" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('regency_name') border-red-500 @enderror" 
                                       id="regency_search" 
                                       name="regency_search"
                                       value="{{ old('regency_name') }}" 
                                       placeholder="Ketik nama kota/kabupaten tempat tinggal saat ini..."
                                       data-location-autocomplete
                                       data-hidden-input="#regency_id"
                                       autocomplete="off"
                                       required>
                                <input type="hidden" id="regency_id" name="regency_id" value="{{ old('regency_id') }}">
                                <input type="hidden" id="regency_name" name="regency_name" value="{{ old('regency_name') }}">
                                <input type="hidden" id="province_name" name="province_name" value="{{ old('province_name') }}">
                            </div>
                            @error('regency_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('regency_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Ketik minimal 2 karakter untuk mencari kota/kabupaten tempat tinggal saat ini
                            </p>
                        </div>

                        <div>
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

                        <!-- Fixed Wakaf Category Display -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-hand-holding-heart text-green-600 mr-3 text-xl"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-green-800">Kategori Wakaf - 5K</h3>
                                    <p class="text-green-700 text-sm">Program khusus dengan kontribusi sosial</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="jersey_size_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ukuran Jersey <span class="text-red-500">*</span>
                                </label>
                                <select class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('jersey_size_id') border-red-500 @enderror" 
                                        id="jersey_size_id" name="jersey_size_id" required>
                                    <option value="">Pilih Ukuran</option>
                                    @foreach($jerseySizes as $size)
                                        <option value="{{ $size->id }}" {{ old('jersey_size_id') == $size->id ? 'selected' : '' }}>
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jersey_size_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
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
                                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Kontak Darurat <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('emergency_contact_name') border-red-500 @enderror" 
                                           id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" 
                                           placeholder="Nama kontak darurat" required>
                                    @error('emergency_contact_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nomor Kontak Darurat <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" 
                                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red transition-colors @error('emergency_contact_phone') border-red-500 @enderror" 
                                           id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" 
                                           placeholder="08xxxxxxxxxx" 
                                           pattern="[0-9+\-\s]+"
                                           title="Hanya angka, tanda +, -, dan spasi yang diperbolehkan"
                                           required>
                                    @error('emergency_contact_phone')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-sm text-gray-600">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Format: 08xxxxxxxxxx atau +628xxxxxxxxxx
                                    </p>
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
                            <i class="fas fa-hand-holding-heart mr-2"></i>Daftar
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // SatuWakaf Integration Variables
    const wakafPaymentModal = document.getElementById('wakafPaymentModal');
    const wakafFormSection = document.getElementById('wakafFormSection');
    const wakafPaymentSection = document.getElementById('wakafPaymentSection');
    const wakafSuccessSection = document.getElementById('wakafSuccessSection');
    const processWakafBtn = document.getElementById('processWakafBtn');
    const cancelWakafBtn = document.getElementById('cancelWakafBtn');
    const continueToRegistrationBtn = document.getElementById('continueToRegistration');
    
    // Check if critical elements exist
    if (!wakafPaymentModal) {
        console.error('Wakaf payment modal not found! Check if the modal HTML exists.');
        return;
    }
    
    let currentWakafId = null;
    let paymentCheckInterval = null;
    let isWakafVerified = false;

    // Terms Modal Management
    const termsModal = document.getElementById('termsModal');
    const termsContent = document.getElementById('termsContent');
    const progressBar = document.getElementById('progressBar');
    const readProgress = document.getElementById('readProgress');
    const acceptTermsBtn = document.getElementById('acceptTermsBtn');
    const closeTermsBtn = document.getElementById('closeTermsBtn');
    const loadingIcon = document.getElementById('loadingIcon');
    const btnText = document.getElementById('btnText');
    const formContainer = document.querySelector('.form-container');
    
    let isTermsAccepted = false;
    let readPercentage = 0;
    let hasReachedEnd = false;
    let autoCloseTimer = null;
    
    // Check if wakaf is already verified from previous session
    const storedWakafStatus = localStorage.getItem('wakafVerified');
    const wakafVerifiedTime = localStorage.getItem('wakafVerifiedTime');
    const storedTermsStatus = localStorage.getItem('termsAccepted');
    const termsAcceptedTime = localStorage.getItem('termsAcceptedTime');
    
    // Check if wakaf verification is still valid (within 15 minutes)
    if (storedWakafStatus === 'true' && wakafVerifiedTime) {
        const verifiedTime = new Date(wakafVerifiedTime);
        const currentTime = new Date();
        const timeDiff = currentTime - verifiedTime;
        const minutesDiff = timeDiff / (1000 * 60);
        
        if (minutesDiff < 15) {
            // Wakaf still valid
            isWakafVerified = true;
            
            // Check if terms are also already accepted
            if (storedTermsStatus === 'true' && termsAcceptedTime) {
                const termsTime = new Date(termsAcceptedTime);
                const termsTimeDiff = currentTime - termsTime;
                const termsMinutesDiff = termsTimeDiff / (1000 * 60);
                
                if (termsMinutesDiff < 15) {
                    // Both wakaf and terms are valid, go directly to form
                    isTermsAccepted = true;
                    
                    // Show form container
                    if (formContainer) {
                        formContainer.style.display = 'block';
                    }
                    
                    // Hide both modals
                    if (wakafPaymentModal) {
                        wakafPaymentModal.style.display = 'none';
                    }
                    if (termsModal) {
                        termsModal.style.display = 'none';
                    }
                } else {
                    // Wakaf valid but terms expired, show terms modal
                    localStorage.removeItem('termsAccepted');
                    localStorage.removeItem('termsAcceptedTime');
                    showTermsModal();
                    
                    // Show form container but keep it hidden until terms are accepted
                    if (formContainer) {
                        formContainer.style.display = 'block';
                    }
                    
                    // Hide wakaf modal
                    if (wakafPaymentModal) {
                        wakafPaymentModal.style.display = 'none';
                    }
                }
            } else {
                // Wakaf valid but no terms acceptance, show terms modal
                showTermsModal();
                
                // Show form container but keep it hidden until terms are accepted
                if (formContainer) {
                    formContainer.style.display = 'block';
                }
                
                // Hide wakaf modal
                if (wakafPaymentModal) {
                    wakafPaymentModal.style.display = 'none';
                }
            }
        } else {
            // Wakaf verification expired, clear storage and show wakaf modal
            localStorage.removeItem('wakafVerified');
            localStorage.removeItem('wakafVerifiedTime');
            localStorage.removeItem('wakafTransactionId');
            localStorage.removeItem('termsAccepted');
            localStorage.removeItem('termsAcceptedTime');
            showWakafPaymentModal();
            
            // Hide both terms modal and form until wakaf is completed
            if (formContainer) {
                formContainer.style.display = 'none';
            }
            if (termsModal) {
                termsModal.style.display = 'none';
            }
        }
    } else {
        // No valid wakaf verification, show wakaf modal first
        showWakafPaymentModal();
        
        // Hide both terms modal and form until wakaf is completed
        if (formContainer) {
            formContainer.style.display = 'none';
        }
        if (termsModal) {
            termsModal.style.display = 'none';
        }
    }

    // Wakaf Payment Modal Functions
    function showWakafPaymentModal() {
        wakafPaymentModal.classList.remove('hidden');
        wakafPaymentModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Ensure wakaf form section is shown initially
        showWakafFormSection();
        
        // Debug: Check if form elements exist
        console.log('Wakaf form elements check:');
        console.log('- wakaf_email:', !!document.getElementById('wakaf_email'));
        console.log('- wakaf_phone:', !!document.getElementById('wakaf_phone'));
        console.log('- wakaf_amount:', !!document.getElementById('wakaf_amount'));
        console.log('- wakaf_wakif_name:', !!document.getElementById('wakaf_wakif_name'));
    }
    
    function hideWakafPaymentModal() {
        wakafPaymentModal.classList.add('hidden');
        wakafPaymentModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Ikrar Wakaf Modal Functions
    const ikrarWakafModal = document.getElementById('ikrarWakafModal');
    const confirmIkrarBtn = document.getElementById('confirmIkrarBtn');
    
    function showIkrarWakafModal() {
        // Validate form first - safer approach with null checks
        const email = document.getElementById('wakaf_email')?.value?.trim() || '';
        let phone = document.getElementById('wakaf_phone')?.value?.trim() || '';
        const amountInput = document.getElementById('wakaf_amount')?.value || '';
        const wakifName = document.getElementById('wakaf_wakif_name')?.value?.trim() || '';

        // Check if all required elements exist
        if (!document.getElementById('wakaf_email') || 
            !document.getElementById('wakaf_phone') || 
            !document.getElementById('wakaf_amount') || 
            !document.getElementById('wakaf_wakif_name')) {
            alert('Form wakaf tidak ditemukan. Silakan refresh halaman.');
            return;
        }

        if (!email || !phone || !amountInput || !wakifName) {
            alert('Mohon lengkapi semua field yang wajib diisi.');
            return;
        }

        // Clean and validate phone number
        phone = phone.replace(/\D/g, '');
        phone = phone.replace(/^0+/, '');
        
        if (phone.length < 8 || phone.length > 13) {
            alert('Nomor HP harus antara 8-13 digit (tanpa awalan 0)');
            return;
        }

        // Get amount
        const amount = parseInt(amountInput);
        if (!amount || amount < 10000) {
            alert('Jumlah wakaf minimal Rp 10.000');
            return;
        }

        // Set amount in ikrar modal
        const ikrarAmountElement = document.getElementById('ikrarAmount');
        if (ikrarAmountElement) {
            ikrarAmountElement.textContent = 'Rp ' + amount.toLocaleString('id-ID');
        }
        
        // Show modal
        ikrarWakafModal.classList.remove('hidden');
        ikrarWakafModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    
    function hideIkrarWakafModal() {
        ikrarWakafModal.classList.add('hidden');
        ikrarWakafModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Confirm Ikrar Button
    confirmIkrarBtn.addEventListener('click', function() {
        hideIkrarWakafModal();
        processWakafDonation();
    });

    // Close ikrar modal when clicking backdrop
    ikrarWakafModal.addEventListener('click', function(e) {
        if (e.target === ikrarWakafModal || e.target.classList.contains('modal-backdrop')) {
            hideIkrarWakafModal();
        }
    });

    // Wakaf amount validation
    document.getElementById('wakaf_amount').addEventListener('input', function() {
        const amount = parseInt(this.value);
        const errorMsg = document.getElementById('wakaf_amount_error');
        
        // Remove existing error message
        if (errorMsg) {
            errorMsg.remove();
        }
        
        if (this.value && amount < 10000) {
            const error = document.createElement('p');
            error.id = 'wakaf_amount_error';
            error.className = 'text-xs text-red-500 mt-1';
            error.textContent = 'Jumlah wakaf minimal Rp 10.000';
            this.parentNode.parentNode.appendChild(error);
            this.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
            this.classList.remove('border-gray-300', 'focus:border-green-500', 'focus:ring-green-500');
        } else {
            this.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
            this.classList.add('border-gray-300', 'focus:border-green-500', 'focus:ring-green-500');
        }
    });

    // Format currency display
    document.getElementById('wakaf_amount').addEventListener('blur', function() {
        if (this.value) {
            const amount = parseInt(this.value);
            if (amount >= 10000) {
                // Format with thousand separators for better readability
                const formatted = amount.toLocaleString('id-ID');
                this.setAttribute('data-formatted', 'Rp ' + formatted);
            }
        }
    });

    // Wakaf phone number validation and formatting
    document.getElementById('wakaf_phone').addEventListener('input', function() {
        // Remove all non-numeric characters
        let value = this.value.replace(/\D/g, '');
        
        // Remove leading zeros
        value = value.replace(/^0+/, '');
        
        // Update the input value
        this.value = value;
        
        // Visual feedback for valid format
        if (value.length >= 8 && value.length <= 13) {
            this.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
            this.classList.add('border-gray-300', 'focus:border-green-500', 'focus:ring-green-500');
        } else if (value.length > 0) {
            this.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
            this.classList.remove('border-gray-300', 'focus:border-green-500', 'focus:ring-green-500');
        }
    });

    // Prevent non-numeric characters on keypress
    document.getElementById('wakaf_phone').addEventListener('keypress', function(e) {
        // Allow backspace, delete, tab, escape, enter
        if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
            // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true)) {
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    // Process Wakaf Button
    processWakafBtn.addEventListener('click', function() {
        if (wakafFormSection.style.display !== 'none' && !wakafFormSection.classList.contains('hidden')) {
            showIkrarWakafModal();
        }
    });

    // Cancel Wakaf Button
    cancelWakafBtn.addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin membatalkan proses wakaf? Anda tidak akan dapat melanjutkan registrasi tanpa menyelesaikan wakaf terlebih dahulu.')) {
            // Stop payment checking if active
            if (paymentCheckInterval) {
                clearInterval(paymentCheckInterval);
                paymentCheckInterval = null;
            }
            
            // Reset to form section
            showWakafFormSection();
        }
    });

    // Continue to Registration Button
    continueToRegistrationBtn.addEventListener('click', function() {
        hideWakafPaymentModal();
        showTermsModal();
    });

    function showWakafFormSection() {
        // Ensure form section is visible
        if (wakafFormSection) {
            wakafFormSection.classList.remove('hidden');
        }
        
        // Hide other sections
        if (wakafPaymentSection) {
            wakafPaymentSection.classList.add('hidden');
        }
        if (wakafSuccessSection) {
            wakafSuccessSection.classList.add('hidden');
        }
        
        // Reset process button
        if (processWakafBtn) {
            processWakafBtn.innerHTML = '<i class="fas fa-hand-holding-heart mr-2"></i>Proses Wakaf';
            processWakafBtn.disabled = false;
        }
        
        // Validate that form fields exist, if not show error
        const requiredFields = ['wakaf_email', 'wakaf_phone', 'wakaf_amount', 'wakaf_wakif_name'];
        const missingFields = requiredFields.filter(fieldId => !document.getElementById(fieldId));
        
        if (missingFields.length > 0) {
            console.error('Missing wakaf form fields:', missingFields);
            // Optionally show user-friendly error
            if (wakafFormSection) {
                wakafFormSection.innerHTML = `
                    <div class="text-center text-red-600 p-4">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                        <p>Form wakaf tidak dapat dimuat. Silakan refresh halaman.</p>
                        <button onclick="location.reload()" class="mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Refresh Halaman
                        </button>
                    </div>
                `;
            }
        }
    }

    function showWakafPaymentSection() {
        wakafFormSection.classList.add('hidden');
        wakafPaymentSection.classList.remove('hidden');
        wakafSuccessSection.classList.add('hidden');
        processWakafBtn.style.display = 'none';
    }

    function showWakafSuccessSection() {
        wakafFormSection.classList.add('hidden');
        wakafPaymentSection.classList.add('hidden');
        wakafSuccessSection.classList.remove('hidden');
        processWakafBtn.style.display = 'none';
        cancelWakafBtn.style.display = 'none';
    }

    function processWakafDonation() {
        // Validate form
        const donorName = "ASRRUN"; // Set fixed donor name
        const email = document.getElementById('wakaf_email')?.value?.trim() || '';
        let phone = document.getElementById('wakaf_phone')?.value?.trim() || '';
        const amountInput = document.getElementById('wakaf_amount')?.value || '';
        const wakifName = document.getElementById('wakaf_wakif_name')?.value?.trim() || '';

        // Check if all required elements exist
        if (!document.getElementById('wakaf_email') || 
            !document.getElementById('wakaf_phone') || 
            !document.getElementById('wakaf_amount') || 
            !document.getElementById('wakaf_wakif_name')) {
            alert('Form wakaf tidak ditemukan. Silakan refresh halaman.');
            return;
        }

        if (!email || !phone || !amountInput || !wakifName) {
            alert('Mohon lengkapi semua field yang wajib diisi.');
            return;
        }

        // Clean and validate phone number
        phone = phone.replace(/\D/g, ''); // Remove non-numeric
        phone = phone.replace(/^0+/, ''); // Remove leading zeros
        
        if (phone.length < 8 || phone.length > 13) {
            alert('Nomor HP harus antara 8-13 digit (tanpa awalan 0)');
            return;
        }

        // Get amount directly from input
        const amount = parseInt(amountInput);
        if (!amount || amount < 10000) {
            alert('Jumlah wakaf minimal Rp 10.000');
            return;
        }

        // Format phone number for API (add country code)
        const formattedPhone = '62' + phone;

        // Disable button and show loading
        processWakafBtn.disabled = true;
        processWakafBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

        // Prepare wakaf data
        const wakafData = {
            campaign_id: "f4978631-99ad-4879-ac72-cfcbfc11611b",
            amount: amount,
            maintenance_fee: 0,
            payment_method_id: "c7d3a66e-e9ca-4c7f-a020-ad75da1472b7", // QRIS
            is_anonymous: false, // Always show donor name
            willing_to_contact_by_lembaga: true,
            donor_name: donorName,
            email: email,
            phone_number: formattedPhone,
            wakif_name: wakifName,
            wakif_phone: formattedPhone,
            wakif_address: null,
            wakif_city: "74.71", // Default city code
            wakif_province: "74", // Default province code
            subdomain: "apps"
        };

        // Call SatuWakaf API
        fetch('https://api.satuwakafindonesia.id/donations/non-login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(wakafData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data && data.data.length > 0) {
                currentWakafId = data.data[0].id;
                
                // Get detailed payment info
                return fetch(`https://api.satuwakafindonesia.id/donations/non-login/${currentWakafId}`);
            } else {
                throw new Error(data.message || 'Gagal membuat donasi wakaf');
            }
        })
        .then(response => response.json())
        .then(paymentData => {
            if (paymentData.status === 'success' && paymentData.data && paymentData.data.length > 0) {
                const donation = paymentData.data[0];
                displayPaymentInfo(donation);
                showWakafPaymentSection();
                startPaymentStatusCheck();
            } else {
                throw new Error('Gagal mendapatkan informasi pembayaran');
            }
        })
        .catch(error => {
            console.error('Wakaf API error:', error);
            alert('Terjadi kesalahan saat memproses wakaf: ' + error.message);
            
            // Re-enable button
            processWakafBtn.disabled = false;
            processWakafBtn.innerHTML = '<i class="fas fa-hand-holding-heart mr-2"></i>Proses Wakaf';
        });
    }

    function displayPaymentInfo(donation) {
        console.log('displayPaymentInfo called with:', donation);
        console.log('QR string available:', !!donation.payment.qr_string);
        console.log('QR string length:', donation.payment.qr_string ? donation.payment.qr_string.length : 0);
        
        // Set transaction ID with safety check
        const transactionElement = document.getElementById('transaction_id');
        if (transactionElement) {
            transactionElement.textContent = donation.id;
        }
        
        // Set payment amount with safety check
        const totalAmount = donation.amount.total_amount;
        const paymentAmountElement = document.getElementById('payment_amount');
        if (paymentAmountElement) {
            paymentAmountElement.textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
        }
        
        // Set expiry time with safety check
        const expiredAt = new Date(donation.payment.expired_at);
        const paymentExpiredElement = document.getElementById('payment_expired');
        if (paymentExpiredElement) {
            paymentExpiredElement.textContent = expiredAt.toLocaleString('id-ID');
        }
        
        // Generate QR Code
        if (donation.payment.qr_string) {
            const qrContainer = document.getElementById('qr_code_container');
            qrContainer.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin text-2xl text-blue-500 mb-2"></i><p class="text-sm text-gray-600">Generating QR Code...</p></div>';
            
            // Decode base64 QR string
            let qrData;
            try {
                qrData = atob(donation.payment.qr_string);
                console.log('QR data decoded successfully, length:', qrData.length);
                console.log('QR data preview:', qrData.substring(0, 100));
            } catch (e) {
                console.error('Failed to decode QR string:', e);
                qrData = donation.payment.qr_string; // Use as is if decode fails
                console.log('Using raw QR string instead');
            }
            
            // Create canvas for QR code
            const canvas = document.createElement('canvas');
            canvas.className = 'mx-auto';
            canvas.id = 'qr-canvas';
            
            // Wait for QRious library to load, then generate
            let attempts = 0;
            const maxAttempts = 10; // Reduce attempts since QRious is lighter
            const generateQR = () => {
                console.log('Attempting to generate QR code with QRious, attempt:', attempts + 1);
                console.log('QRious available:', typeof QRious !== 'undefined');
                console.log('Window QRious available:', typeof window.QRious !== 'undefined');
                
                if (typeof QRious !== 'undefined' || typeof window.QRious !== 'undefined') {
                    const QRiousLib = window.QRious || QRious;
                    console.log('QRious library loaded, generating QR for data:', qrData.substring(0, 50) + '...');
                    
                    try {
                        const qr = new QRiousLib({
                            element: canvas,
                            value: qrData,
                            size: 280,
                            level: 'M'
                        });
                        
                        console.log('QR Code generated successfully');
                        
                        // Clear container and add QR code
                        qrContainer.innerHTML = '';
                        
                        // Wrap canvas in a container
                        const qrWrapper = document.createElement('div');
                        qrWrapper.className = 'bg-white p-4 inline-block rounded-lg shadow-lg border border-gray-200';
                        qrWrapper.appendChild(canvas);
                        qrContainer.appendChild(qrWrapper);
                        
                        // Add instruction text
                        const instruction = document.createElement('div');
                        instruction.className = 'mt-4 text-center';
                        instruction.innerHTML = `
                            <p class="text-sm text-gray-700 font-medium mb-1">Scan QR Code untuk pembayaran</p>
                            <p class="text-xs text-gray-500">Gunakan aplikasi: DANA, GoPay, OVO, ShopeePay, atau aplikasi bank</p>
                        `;
                        qrContainer.appendChild(instruction);
                        
                        // Add copy button for manual payment
                        const copySection = document.createElement('div');
                        copySection.className = 'mt-3 text-center';
                        copySection.innerHTML = `
                            <button onclick="copyQRString()" class="text-xs text-blue-600 underline hover:text-blue-800">
                                <i class="fas fa-copy mr-1"></i>Salin kode QR (untuk input manual)
                            </button>
                        `;
                        qrContainer.appendChild(copySection);
                        
                        // Store QR data globally for copy function
                        window.currentQRData = qrData;
                        
                    } catch (error) {
                        console.error('QRious generation error:', error);
                        // Fallback to placeholder
                        qrContainer.innerHTML = `
                            <div class="w-72 h-72 bg-gray-100 flex items-center justify-center text-gray-500 text-sm mx-auto rounded-lg border-2 border-dashed border-gray-300">
                                <div class="text-center">
                                    <i class="fas fa-qrcode text-4xl mb-2"></i>
                                    <p class="font-medium">QR Code QRIS</p>
                                    <p class="text-xs mt-2">Error generating QR Code</p>
                                    <p class="text-xs text-red-500 mt-1">Silakan hubungi admin</p>
                                </div>
                            </div>
                        `;
                    }
                } else {
                    // QRious library not loaded, try again
                    attempts++;
                    if (attempts < maxAttempts) {
                        console.log('QRious library not ready, retrying in 500ms...');
                        setTimeout(generateQR, 500);
                    } else {
                        console.error('QRious library failed to load after', maxAttempts, 'attempts');
                        // Show error message with manual QR data
                        qrContainer.innerHTML = `
                            <div class="w-72 h-72 bg-yellow-50 flex flex-col items-center justify-center text-yellow-700 text-sm mx-auto rounded-lg border-2 border-dashed border-yellow-300 p-4">
                                <div class="text-center">
                                    <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                                    <p class="font-medium">Library QR Code gagal dimuat</p>
                                    <p class="text-xs mt-2 mb-3">Gunakan kode manual di bawah:</p>
                                    <div class="bg-white p-2 rounded border text-xs font-mono break-all max-h-20 overflow-y-auto">
                                        ${qrData}
                                    </div>
                                    <button onclick="copyManualQR('${qrData}')" class="mt-2 text-xs text-blue-600 underline">
                                        <i class="fas fa-copy mr-1"></i>Salin kode
                                    </button>
                                </div>
                            </div>
                        `;
                    }
                }
            };
            
            generateQR();
            
            // Also add a timeout fallback
            setTimeout(() => {
                if (qrContainer.innerHTML.includes('Generating QR Code...')) {
                    console.log('QR generation timeout, showing manual fallback');
                    qrContainer.innerHTML = `
                        <div class="w-72 h-72 bg-orange-50 flex flex-col items-center justify-center text-orange-700 text-sm mx-auto rounded-lg border-2 border-dashed border-orange-300 p-4">
                            <div class="text-center">
                                <i class="fas fa-clock text-4xl mb-2"></i>
                                <p class="font-medium">QR Code loading timeout</p>
                                <p class="text-xs mt-2 mb-3">Gunakan kode manual:</p>
                                <div class="bg-white p-2 rounded border text-xs font-mono break-all max-h-20 overflow-y-auto">
                                    ${qrData}
                                </div>
                                <div class="mt-3 space-y-1">
                                    <button onclick="copyManualQR('${qrData}')" class="text-xs text-blue-600 underline block">
                                        <i class="fas fa-copy mr-1"></i>Salin kode
                                    </button>
                                    <button onclick="location.reload()" class="text-xs text-green-600 underline block">
                                        <i class="fas fa-refresh mr-1"></i>Refresh halaman
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }, 3000); // 3 second timeout (faster)
        } else {
            // Fallback if no QR string
            const qrContainer = document.getElementById('qr_code_container');
            qrContainer.innerHTML = `
                <div class="w-72 h-72 bg-gray-100 flex items-center justify-center text-gray-500 text-sm mx-auto rounded-lg border-2 border-dashed border-gray-300">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-4xl mb-2 text-yellow-500"></i>
                        <p class="font-medium">QR Code tidak tersedia</p>
                        <p class="text-xs mt-2">Silakan hubungi admin untuk bantuan</p>
                    </div>
                </div>
            `;
        }
    }

    // Global function to copy QR string
    window.copyQRString = function() {
        if (window.currentQRData) {
            navigator.clipboard.writeText(window.currentQRData).then(() => {
                alert('Kode QR berhasil disalin ke clipboard');
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = window.currentQRData;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Kode QR berhasil disalin ke clipboard');
            });
        }
    };

    // Global function to copy manual QR string
    window.copyManualQR = function(qrData) {
        navigator.clipboard.writeText(qrData).then(() => {
            alert('Kode QR berhasil disalin ke clipboard');
        }).catch(() => {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = qrData;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Kode QR berhasil disalin ke clipboard');
        });
    };

    function startPaymentStatusCheck() {
        if (paymentCheckInterval) {
            clearInterval(paymentCheckInterval);
        }
        
        paymentCheckInterval = setInterval(() => {
            checkPaymentStatus();
        }, 3000); // Check every 3 seconds
    }

    function checkPaymentStatus() {
        if (!currentWakafId) return;
        
        fetch(`https://api.satuwakafindonesia.id/donations/non-login/${currentWakafId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data && data.data.length > 0) {
                const donation = data.data[0];
                const status = donation.status.name;
                
                updatePaymentStatus(status, donation);
                
                if (donation.verified === 1 || status === 'VERIFIED') {
                    // Payment verified!
                    clearInterval(paymentCheckInterval);
                    paymentCheckInterval = null;
                    isWakafVerified = true;
                    
                    // Store wakaf verification in localStorage
                    localStorage.setItem('wakafVerified', 'true');
                    localStorage.setItem('wakafVerifiedTime', new Date().toISOString());
                    localStorage.setItem('wakafTransactionId', donation.id);
                    
                    // Update verified transaction ID with safety check
                    const verifiedTransactionElement = document.getElementById('verified_transaction_id');
                    if (verifiedTransactionElement) {
                        verifiedTransactionElement.textContent = donation.id;
                    }
                    
                    showWakafSuccessSection();
                }
            }
        })
        .catch(error => {
            console.error('Payment status check error:', error);
        });
    }

    function updatePaymentStatus(status, donation) {
        const statusContainer = document.getElementById('payment_status');
        if (!statusContainer) {
            console.error('Payment status container not found');
            return;
        }
        
        let statusHtml = '';
        
        switch(status) {
            case 'PENDING':
                statusHtml = `
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-yellow-600 mr-2"></i>
                            <span class="text-yellow-800 text-sm">Menunggu pembayaran...</span>
                        </div>
                    </div>
                `;
                break;
            case 'PAID':
                statusHtml = `
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                            <span class="text-blue-800 text-sm">Pembayaran diterima, menunggu verifikasi...</span>
                        </div>
                    </div>
                `;
                break;
            case 'VERIFIED':
                statusHtml = `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span class="text-green-800 text-sm">Pembayaran terverifikasi!</span>
                        </div>
                    </div>
                `;
                break;
            case 'FAILED':
            case 'EXPIRED':
                statusHtml = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i class="fas fa-times-circle text-red-600 mr-2"></i>
                            <span class="text-red-800 text-sm">Pembayaran gagal atau kedaluwarsa</span>
                        </div>
                        <button type="button" onclick="location.reload()" class="mt-2 text-sm text-red-600 underline">
                            Coba lagi
                        </button>
                    </div>
                `;
                if (paymentCheckInterval) {
                    clearInterval(paymentCheckInterval);
                    paymentCheckInterval = null;
                }
                break;
        }
        
        statusContainer.innerHTML = statusHtml;
    }

    // Terms Modal Functions (shown after wakaf completion)
    function showTermsModal() {
        termsModal.classList.remove('hidden');
        termsModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    
    function hideTermsModal() {
        termsModal.classList.add('hidden');
        termsModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Show form after terms are accepted
        if (formContainer && isTermsAccepted) {
            formContainer.style.display = 'block';
        }
    }
    
    // Track scrolling in terms content
    termsContent.addEventListener('scroll', function() {
        const scrollTop = this.scrollTop;
        const scrollHeight = this.scrollHeight;
        const clientHeight = this.clientHeight;
        const scrollable = scrollHeight - clientHeight;
        
        if (scrollable > 0) {
            readPercentage = Math.round((scrollTop / scrollable) * 100);
            readPercentage = Math.min(100, Math.max(0, readPercentage));
        } else {
            readPercentage = 100; // If content is not scrollable, consider as fully read
        }
        
        // Update progress bar
        progressBar.style.width = readPercentage + '%';
        readProgress.textContent = readPercentage + '%';
        
        // Update button text
        if (readPercentage < 100) {
            btnText.textContent = `Membaca... (${readPercentage}%)`;
            acceptTermsBtn.disabled = true;
            acceptTermsBtn.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-400 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm cursor-not-allowed';
            loadingIcon.style.display = 'inline-block';
        } else {
            // User has read to the end
            if (!hasReachedEnd) {
                hasReachedEnd = true;
                btnText.textContent = 'Setuju & Lanjutkan';
                acceptTermsBtn.disabled = false;
                acceptTermsBtn.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm';
                loadingIcon.style.display = 'none';
                
                // Show success message
                showReadCompleteMessage();
                
                // Start auto-close countdown
                startAutoCloseCountdown();
            }
        }
    });
    
    function showReadCompleteMessage() {
        // Create a temporary success message
        const successMsg = document.createElement('div');
        successMsg.className = 'bg-green-50 border border-green-200 rounded-lg p-3 mb-4';
        successMsg.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <span class="text-green-800 text-sm font-medium">
                    Anda telah membaca seluruh syarat dan ketentuan!
                </span>
            </div>
        `;
        
        // Insert before the scrollable content
        termsContent.parentNode.insertBefore(successMsg, termsContent);
        
        // Remove after 5 seconds
        setTimeout(() => {
            if (successMsg.parentNode) {
                successMsg.parentNode.removeChild(successMsg);
            }
        }, 5000);
    }
    
    function startAutoCloseCountdown() {
        let countdown = 10; // Auto close in 10 seconds
        
        function updateCountdown() {
            if (countdown > 0 && hasReachedEnd && !isTermsAccepted) {
                btnText.textContent = `Setuju & Lanjutkan (${countdown}s)`;
                countdown--;
                autoCloseTimer = setTimeout(updateCountdown, 1000);
            } else if (countdown === 0 && hasReachedEnd && !isTermsAccepted) {
                // Auto accept terms
                acceptTerms();
            }
        }
        
        updateCountdown();
    }
    
    function acceptTerms() {
        isTermsAccepted = true;
        
        // Clear auto-close timer
        if (autoCloseTimer) {
            clearTimeout(autoCloseTimer);
            autoCloseTimer = null;
        }
        
        // Store acceptance in localStorage
        localStorage.setItem('termsAccepted', 'true');
        localStorage.setItem('termsAcceptedTime', new Date().toISOString());
        
        // Hide modal and show form
        hideTermsModal();
        
        // Show success notification
        showSuccessNotification();
    }
    
    function showSuccessNotification() {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-500 translate-x-full';
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>Wakaf berhasil & syarat ketentuan diterima</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Slide in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 500);
            }
        }, 5000);
    }
    
    // Event listeners for terms modal
    acceptTermsBtn.addEventListener('click', acceptTerms);
    closeTermsBtn.addEventListener('click', function() {
        // Don't allow closing without accepting terms, but check if wakaf is verified
        if (!isWakafVerified) {
            alert('Anda harus menyelesaikan wakaf terlebih dahulu sebelum dapat melanjutkan.');
            showWakafPaymentModal();
        } else {
            alert('Anda harus membaca dan menyetujui syarat dan ketentuan untuk melanjutkan pendaftaran.');
        }
    });
    
    // Global function for terms link
    window.showTermsModal = showTermsModal;
    
    function showTermsModal() {
        termsModal.classList.remove('hidden');
        termsModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    
    function hideTermsModal() {
        termsModal.classList.add('hidden');
        termsModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Show form after terms are accepted
        if (formContainer && isTermsAccepted) {
            formContainer.style.display = 'block';
        }
    }
    
    // Track scrolling in terms content
    termsContent.addEventListener('scroll', function() {
        const scrollTop = this.scrollTop;
        const scrollHeight = this.scrollHeight;
        const clientHeight = this.clientHeight;
        const scrollable = scrollHeight - clientHeight;
        
        if (scrollable > 0) {
            readPercentage = Math.round((scrollTop / scrollable) * 100);
            readPercentage = Math.min(100, Math.max(0, readPercentage));
        } else {
            readPercentage = 100; // If content is not scrollable, consider as fully read
        }
        
        // Update progress bar
        progressBar.style.width = readPercentage + '%';
        readProgress.textContent = readPercentage + '%';
        
        // Update button text
        if (readPercentage < 100) {
            btnText.textContent = `Membaca... (${readPercentage}%)`;
            acceptTermsBtn.disabled = true;
            acceptTermsBtn.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-400 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm cursor-not-allowed';
            loadingIcon.style.display = 'inline-block';
        } else {
            // User has read to the end
            if (!hasReachedEnd) {
                hasReachedEnd = true;
                btnText.textContent = 'Setuju & Lanjutkan';
                acceptTermsBtn.disabled = false;
                acceptTermsBtn.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm';
                loadingIcon.style.display = 'none';
                
                // Show success message
                showReadCompleteMessage();
                
                // Start auto-close countdown
                startAutoCloseCountdown();
            }
        }
    });
    
    function showReadCompleteMessage() {
        // Create a temporary success message
        const successMsg = document.createElement('div');
        successMsg.className = 'bg-green-50 border border-green-200 rounded-lg p-3 mb-4';
        successMsg.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <span class="text-green-800 text-sm font-medium">
                    Anda telah membaca seluruh syarat dan ketentuan!
                </span>
            </div>
        `;
        
        // Insert before the scrollable content
        termsContent.parentNode.insertBefore(successMsg, termsContent);
        
        // Remove after 5 seconds
        setTimeout(() => {
            if (successMsg.parentNode) {
                successMsg.parentNode.removeChild(successMsg);
            }
        }, 5000);
    }
    
    function startAutoCloseCountdown() {
        let countdown = 10; // Auto close in 10 seconds
        
        function updateCountdown() {
            if (countdown > 0 && hasReachedEnd && !isTermsAccepted) {
                btnText.textContent = `Setuju & Lanjutkan (${countdown}s)`;
                countdown--;
                autoCloseTimer = setTimeout(updateCountdown, 1000);
            } else if (countdown === 0 && hasReachedEnd && !isTermsAccepted) {
                // Auto accept terms
                acceptTerms();
            }
        }
        
        updateCountdown();
    }
    
    function acceptTerms() {
        isTermsAccepted = true;
        
        // Clear auto-close timer
        if (autoCloseTimer) {
            clearTimeout(autoCloseTimer);
            autoCloseTimer = null;
        }
        
        // Store acceptance in localStorage
        localStorage.setItem('termsAccepted', 'true');
        localStorage.setItem('termsAcceptedTime', new Date().toISOString());
        
        // Hide modal and show form
        hideTermsModal();
        
        // Show success notification
        showSuccessNotification();
    }
    
    function showSuccessNotification() {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-500 translate-x-full';
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>Syarat dan ketentuan telah diterima</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Slide in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 500);
            }
        }, 5000);
    }
    
    // Event listeners
    acceptTermsBtn.addEventListener('click', acceptTerms);
    closeTermsBtn.addEventListener('click', function() {
        // Don't allow closing without accepting terms
        alert('Anda harus membaca dan menyetujui syarat dan ketentuan untuk melanjutkan pendaftaran.');
    });
    
    // Global function for terms link
    window.showTermsModal = showTermsModal;

    // Form submission handling with reCAPTCHA v3
    const form = document.getElementById('registrationForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Form validation on submit
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
                e.preventDefault();
                return;
            }
        }

        // Check if WhatsApp number is definitively invalid (not just service down)
        const phoneNumber = whatsappInput.value.trim();
        if (phoneNumber.length >= 9 && !isValidWhatsApp && 
            whatsappInput.classList.contains('border-red-500')) {
            e.preventDefault();
            alert('Nomor WhatsApp tidak valid atau tidak terdaftar. Silakan periksa kembali nomor yang Anda masukkan.');
            if (phoneNumber !== lastValidatedNumber) {
                validateWhatsAppNumber(phoneNumber);
            }
            return;
        }

        if (hasError) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi.');
            return;
        }
        
        e.preventDefault();
        
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        
        // Execute reCAPTCHA v3
        if (typeof grecaptcha !== 'undefined') {
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config("services.recaptcha.site_key") }}', {action: 'register_wakaf'}).then(function(token) {
                    // Add reCAPTCHA token to form
                    let recaptchaInput = document.querySelector('input[name="g-recaptcha-response"]');
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
                    
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-hand-holding-heart mr-2"></i>Daftar';
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                });
            });
        } else {
            // If reCAPTCHA not loaded, submit anyway
            console.warn('reCAPTCHA not loaded, submitting without verification');
            form.submit();
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

    // WhatsApp Validation Setting from Backend
    const whatsappValidationEnabled = {{ config('app.validate_whatsapp', true) ? 'true' : 'false' }};

    let validationTimeout;
    let lastValidatedNumber = '';
    let isValidWhatsApp = !whatsappValidationEnabled; // If validation disabled, default to true

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
        this.classList.remove('border-green-500', 'border-red-500', 'border-yellow-500');
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
    });

    // Auto-validate on blur (when user leaves the field)
    whatsappInput.addEventListener('blur', function() {
        const phoneNumber = this.value.trim();
        if (phoneNumber.length >= 10 && phoneNumber !== lastValidatedNumber) {
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
        // Check if WhatsApp validation is disabled
        if (!whatsappValidationEnabled) {
            isValidWhatsApp = true;
            lastValidatedNumber = phoneNumber;
            whatsappInput.classList.add('border-green-500');
            whatsappInput.classList.remove('border-red-500', 'border-gray-300', 'border-yellow-500');
            showValidationStatus('success', 'Validasi WhatsApp dilewati (disabled)');
            return;
        }
        
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
                whatsappInput.classList.remove('border-red-500', 'border-gray-300', 'border-yellow-500');
                showValidationStatus('success', 'Nomor WhatsApp valid dan terdaftar');
            } else if (data.success && !data.valid) {
                isValidWhatsApp = false;
                whatsappInput.classList.add('border-red-500');
                whatsappInput.classList.remove('border-green-500', 'border-gray-300', 'border-yellow-500');
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

    // Load reCAPTCHA v3 script
    const script = document.createElement('script');
    script.src = 'https://www.google.com/recaptcha/api.js?render={{ config("services.recaptcha.site_key") }}';
    document.head.appendChild(script);

    // Load QRious library (more reliable)
    const qrScript = document.createElement('script');
    qrScript.src = 'https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js';
    qrScript.onload = function() {
        console.log('QRious library loaded successfully');
        window.qrLibraryLoaded = true;
    };
    qrScript.onerror = function() {
        console.error('Failed to load QRious library');
        window.qrLibraryLoaded = false;
    };
    document.head.appendChild(qrScript);
});
</script>

<!-- Preload QRious library -->
<script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>

<!-- Location Autocomplete CSS -->
<link rel="stylesheet" href="{{ asset('css/location-autocomplete.css') }}">

<!-- Location Autocomplete JS -->
<script src="{{ asset('js/location-autocomplete.js') }}"></script>

<script>
// Initialize location autocomplete after page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize autocomplete for regency search
    const regencySearchInput = document.getElementById('regency_search');
    
    if (regencySearchInput) {
        const autocomplete = new LocationAutocomplete('#regency_search');
        
        // Listen for location selection
        regencySearchInput.addEventListener('locationSelected', function(e) {
            const selection = e.detail;
            
            // Update hidden fields
            document.getElementById('regency_id').value = selection.id;
            document.getElementById('regency_name').value = selection.name;
            document.getElementById('province_name').value = selection.province_name;
            
            console.log('Location selected:', selection);
        });
    }
});
</script>
@endsection