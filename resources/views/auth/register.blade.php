@extends('layouts.guest')

@section('title', 'Register - Event Lari')

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
    <div class="w-full max-w-4xl mx-auto">
        <div class="glass-effect rounded-2xl overflow-hidden form-card">
            <!-- Header -->
            <div class="custom-gradient-header text-white p-6 sm:p-6 p-4">
                <!-- Mobile Layout (320px and up) -->
                <div class="block sm:hidden">
                    <!-- Mobile Header - Stacked Layout -->
                    <div class="text-center mb-4">
                        <div class="flex justify-center items-center space-x-4 mb-3">
                            <img src="{{ asset('images/logoprov.png') }}" alt="Logo Provinsi" class="h-10 w-auto object-contain">
                            <img src="{{ asset('images/pesonaindonesia.png') }}" alt="Pesona Indonesia" class="h-10 w-auto object-contain">
                        </div>
                        <h1 class="text-xl font-bold mb-1">
                            <i class="fas fa-running mr-2"></i>Registrasi Event Lari
                        </h1>
                        <p class="text-gray-200 text-sm">Daftar untuk mengikuti event lari</p>
                    </div>
                </div>
                
                <!-- Desktop Layout (sm and up) -->
                <div class="hidden sm:block">
                    <div class="flex items-center justify-between">
                        <!-- Logo Kiri -->
                        <div class="flex-shrink-0">
                            <img src="{{ asset('images/logoprov.png') }}" alt="Logo Provinsi" class="h-16 w-auto object-contain">
                        </div>
                        
                        <!-- Konten Tengah -->
                        <div class="text-center flex-1 mx-6">
                            <h1 class="text-3xl font-bold mb-2">
                                <i class="fas fa-running mr-3"></i>Registrasi Event Lari
                            </h1>
                            <p class="text-gray-200">Daftar untuk mengikuti event lari</p>
                        </div>
                        
                        <!-- Logo Kanan -->
                        <div class="flex-shrink-0">
                            <img src="{{ asset('images/pesonaindonesia.png') }}" alt="Pesona Indonesia" class="h-16 w-auto object-contain">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Content -->
            <div class="p-8 sm:p-8 p-4">
                <form id="registrationForm" method="POST" action="{{ route('register') }}" class="space-y-8 sm:space-y-8 space-y-6">
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
                                    <div class="ticket-quota hidden">
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
                    </div
                    
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
    
    // Show terms modal on page load
    showTermsModal();
    
    // Hide form until terms are accepted
    if (formContainer) {
        formContainer.style.display = 'none';
    }
    
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
                        notification.parentNode.removeChild(notification);
                    }
                }, 500);
            }
        }, 5000);
    }
    
    // Accept terms button click
    acceptTermsBtn.addEventListener('click', function() {
        if (hasReachedEnd) {
            acceptTerms();
        }
    });
    
    // Close terms button (cancel registration)
    closeTermsBtn.addEventListener('click', function() {
        if (confirm('Anda yakin ingin membatalkan registrasi? Anda perlu menyetujui syarat dan ketentuan untuk melanjutkan.')) {
            window.location.href = '/'; // Redirect to home page
        }
    });
    
    // Prevent closing modal by clicking backdrop or ESC
    termsModal.addEventListener('click', function(e) {
        if (e.target === this) {
            // Shake modal to indicate it can't be closed
            const modalContent = this.querySelector('.relative');
            modalContent.classList.add('animate-pulse');
            setTimeout(() => {
                modalContent.classList.remove('animate-pulse');
            }, 1000);
        }
    });
    
    // Disable ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !isTermsAccepted) {
            e.preventDefault();
            // Shake modal
            const modalContent = termsModal.querySelector('.relative');
            modalContent.classList.add('animate-pulse');
            setTimeout(() => {
                modalContent.classList.remove('animate-pulse');
            }, 1000);
        }
    });
    
    // Check if terms were previously accepted (optional - for development)
    // Comment out these lines for production to always show terms
    /*
    const previouslyAccepted = localStorage.getItem('termsAccepted');
    const acceptedTime = localStorage.getItem('termsAcceptedTime');
    
    if (previouslyAccepted === 'true' && acceptedTime) {
        const acceptedDate = new Date(acceptedTime);
        const now = new Date();
        const hoursDiff = (now - acceptedDate) / (1000 * 60 * 60);
        
        // Terms acceptance valid for 24 hours
        if (hoursDiff < 24) {
            isTermsAccepted = true;
            hideTermsModal();
        }
    }
    */

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
            
            // Location data - send regency_search as city for auto-resolution
            city: formData.get('regency_search'), // Send search input as city for auto-resolution
            regency_id: formData.get('regency_id'),
            regency_name: formData.get('regency_name'),
            province_name: formData.get('province_name'),
            
            // Additional fields (may not be processed by basic API but keeping for completeness)
            bib_name: formData.get('bib_name'),
            gender: formData.get('gender'),
            birth_place: formData.get('birth_place'),
            birth_date: formData.get('birth_date'),
            address: formData.get('address'),
            jersey_size: formData.get('jersey_size'),
            whatsapp_number: formData.get('whatsapp_number'),
            emergency_contact_name: formData.get('emergency_contact_name'),
            emergency_contact_phone: formData.get('emergency_contact_phone'),
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
                            <div class="ticket-quota hidden ${quotaColor}">
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

    // Emergency contact phone validation
    const emergencyPhoneInput = document.getElementById('emergency_contact_phone');
    if (emergencyPhoneInput) {
        emergencyPhoneInput.addEventListener('input', function() {
            // Remove non-numeric characters except + - and space
            let value = this.value.replace(/[^0-9+\-\s]/g, '');
            
            // Limit to 20 characters to accommodate international formats
            if (value.length > 20) {
                value = value.substring(0, 20);
            }
            
            this.value = value;
        });
        
        emergencyPhoneInput.addEventListener('keypress', function(e) {
            // Allow only numbers, +, -, space, and control keys
            const allowedChars = /[0-9+\-\s]/;
            const controlKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'Home', 'End', 'ArrowLeft', 'ArrowRight'];
            
            if (!allowedChars.test(e.key) && !controlKeys.includes(e.key)) {
                e.preventDefault();
            }
        });
    }
});
</script>

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
    </div>
</div>
@endsection
