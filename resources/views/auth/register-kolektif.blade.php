@extends('layouts.guest')

@section('title', 'Registrasi Kolektif - Amazing Sultra Run')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/location-autocomplete.css') }}">
<!-- Tailwind CSS CDN for development -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'asr-red': {
                        50: '#fef2f2',
                        500: '#dc2626',
                                      <!-- reCAPTCHA -->
                    <div class="text-center">
                        <!-- reCAPTCHA v3 will be handled automatically in JavaScript -->
                    </div>  600: '#dc2626',
                        700: '#b91c1c',
                    },
                    'asr-emerald': {
                        50: '#f0fdf4',
                        500: '#10b981',
                        600: '#059669',
                    }
                },
                animation: {
                    'fade-in-up': 'fadeInUp 0.6s ease-out',
                    'slide-in-right': 'slideInRight 0.4s ease-out',
                    'pulse-subtle': 'pulse-subtle 2s infinite',
                }
            }
        }
    }
</script>
<style>
    /* Clean animation styles only */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }

    .slide-in-right {
        animation: slideInRight 0.5s ease-out;
    }

    .slide-out-right {
        animation: slideOutRight 0.5s ease-in;
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Floating Button Styles */
    #floatingAddBtn {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    #floatingAddBtn button {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(255, 255, 255, 0.05);
    }
    
    #floatingAddBtn button:hover {
        box-shadow: 0 25px 50px -12px rgba(16, 185, 129, 0.25), 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Ensure floating button doesn't interfere with mobile forms */
    @media (max-width: 768px) {
        #floatingAddBtn {
            bottom: 1rem;
            right: 1rem;
        }
        
        /* Add padding to bottom of page content to prevent overlap */
        .form-container {
            padding-bottom: 5rem;
        }
    }
    
    @media (min-width: 769px) {
        #floatingAddBtn {
            bottom: 1.5rem;
            right: 1.5rem;
        }
        
        .form-container {
            padding-bottom: 3rem;
        }
    }

    /* Override any conflicting styles */
    .form-container {
        display: flex !important;
        flex-direction: column !important;
        gap: 1.5rem !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    /* Ensure proper stacking */
    .form-container > div {
        position: relative !important;
        z-index: 1 !important;
        width: 100% !important;
        max-width: none !important;
    }

    @media (min-width: 1024px) {
        .form-container {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 1.5rem !important;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header with modern design -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl mb-6 shadow-lg">
                <i class="fas fa-users text-3xl text-white"></i>
            </div>
            <h1 class="text-5xl font-bold bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text text-transparent mb-4">
                Registrasi Kolektif
            </h1>
            <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                Daftarkan beberapa peserta sekaligus untuk <span class="font-semibold text-red-600">Amazing Sultra Run</span>. 
                Minimal 1 peserta, maksimal 50 peserta dalam satu kali pendaftaran.
            </p>
        </div>

        <!-- Terms and Conditions Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-8" id="termsSection">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-2xl mr-4"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Syarat dan Ketentuan Amazing Sultra Run 2025</h2>
                        <p class="text-blue-100">Harap baca hingga akhir sebelum melakukan registrasi</p>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-6">
                    <div class="flex justify-between text-sm text-blue-100 mb-2">
                        <span>Progress Membaca</span>
                        <span id="readProgress">0%</span>
                    </div>
                    <div class="w-full bg-blue-500 rounded-full h-2">
                        <div id="progressBar" class="bg-white h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-blue-100 mt-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Anda harus membaca hingga akhir untuk melanjutkan
                    </p>
                </div>
            </div>
            
            <!-- Scrollable Content -->
            <div id="termsContent" class="max-h-96 overflow-y-auto border-gray-200 p-6 bg-gray-50">
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
                    
                    <!-- Continue with all other terms... -->
                    <div>
                        <p class="mb-3 text-justify">
                            <strong>3)</strong> Sehubungan dengan risiko-risiko yang ditanggung oleh PESERTA, maka PESERTA melepaskan, mengabaikan, dan setuju untuk tidak menuntut Penyelenggara/promotor, AMAZING SULTRA RUN, para peserta, Sponsor Acara and agen PR Acara, rekanan yang berpartisipasi, organisasi yang menjamin, (atau afiliasi lainnya), pejabat resmi, pemilik kendaraan, pengemudi, para sponsor, pemasang iklan, para pemilik, para penyewa, pemberi sewa dari lokasi lomba,yang menyelenggarakan Acara dan para petugas, para agen, dan para karyawan (untuk keperluan yang disebutkan ini akan disebut sebagai Pers) dari segala kewajiban kepada diri anda, perwakilan pribadi anda, pihak yang ditunjuk, dan para pelaksanan, dari segala dan seluruh klaim, tuntutan, kerugian atau kerusakan dari PESERTA atau kerusakan properti, terlepas hal tersebut terjadi atau disebabkan atau diduga sebagai akibat baik keseluruhan maupun sebagian karena kelalaian Pers atau lainnya.
                        </p>
                    </div>
                    
                    <!-- Add all remaining terms 4-27 here for brevity, I'll show just a few more key ones -->
                    
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
                    
                    <!-- Add remaining terms 7-27 (abbreviated for space) -->
                    
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
            
            <!-- Accept Terms Button -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-center">
                    <button type="button" 
                            id="acceptTermsBtn" 
                            class="px-8 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed transition-all duration-300"
                            disabled>
                        <i class="fas fa-check mr-2"></i>
                        Saya Setuju dengan Syarat dan Ketentuan
                    </button>
                </div>
                <p class="text-center text-xs text-gray-500 mt-3">
                    <i class="fas fa-scroll mr-1"></i>
                    Harap baca seluruh dokumen terlebih dahulu
                </p>
            </div>
        </div>

        <!-- Form Section (Hidden initially) -->
        <div id="registrationFormSection" style="display: none;">

        <!-- Error Messages with modern styling -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-6 mb-8 shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-red-800 mb-2">Terdapat kesalahan yang perlu diperbaiki:</h3>
                        <ul class="text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-center">
                                    <i class="fas fa-chevron-right text-xs mr-2"></i>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Success Messages with modern styling -->
        @if (session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-400 rounded-lg p-6 mb-8 shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-emerald-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-emerald-800 mb-2">{{ session('success') }}</h3>
                        @if (session('registration_numbers'))
                            <div class="mt-4">
                                <h4 class="font-semibold text-emerald-800 mb-3">Nomor Registrasi Peserta:</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach (session('registration_numbers') as $regNumber)
                                        <div class="bg-emerald-100 border border-emerald-200 text-emerald-800 px-4 py-2 rounded-lg text-center font-mono text-sm font-semibold shadow-sm">
                                            <i class="fas fa-ticket-alt mr-2"></i>
                                            {{ $regNumber }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register.kolektif.post') }}" id="collectiveRegistrationForm" class="space-y-8">
            @csrf

            <!-- Modern Progress Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                    <div class="flex items-center space-x-8">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div id="participantsStatusContainer">
                                <p class="text-sm font-medium text-gray-600">Total Peserta</p>
                                <p id="participantCount" class="text-2xl font-bold text-blue-600">10</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Form Terisi</p>
                                <p id="completedCount" class="text-2xl font-bold text-emerald-600">0</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Progress</p>
                                <p id="completionPercentage" class="text-2xl font-bold text-purple-600">0%</p>
                            </div>
                        </div>
                    </div>
                    <button type="button" 
                            id="addParticipantBtn"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 transform hover:-translate-y-0.5" 
                            onclick="addParticipantForm()">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Peserta Baru
                    </button>
                </div>
                
                <!-- Floating Add Button -->
                <div id="floatingAddBtn" class="fixed bottom-6 right-6 z-50 opacity-0 pointer-events-none transition-all duration-300 transform translate-y-16">
                    <button type="button" 
                            class="group bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-full shadow-2xl hover:shadow-emerald-500/25 transition-all duration-300 transform hover:scale-110 active:scale-95 focus:outline-none focus:ring-4 focus:ring-emerald-500/50" 
                            onclick="addParticipantForm()"
                            title="Tambah Peserta Baru">
                        <!-- Mobile size (smaller) -->
                        <div class="flex md:hidden items-center justify-center w-14 h-14">
                            <i class="fas fa-plus text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                        </div>
                        <!-- Desktop size (larger with text) -->
                        <div class="hidden md:flex items-center px-6 py-4">
                            <i class="fas fa-plus mr-3 group-hover:rotate-90 transition-transform duration-300"></i>
                            <span class="font-semibold">Tambah Peserta</span>
                        </div>
                    </button>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600">Progress Pengisian Form</span>
                        <span class="text-sm font-medium text-gray-600" id="progressText">0 dari 10 selesai</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div id="completionProgressBar" class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2.5 rounded-full transition-all duration-500 ease-out" style="width: 0%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>

            <!-- Participants Container with modern grid -->
            <div class="form-container" id="participantsContainer">
                <!-- Initial 10 forms will be generated by JavaScript -->
            </div>

            <!-- Modern Submit Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-8 py-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Pendaftaran</h3>
                    <p class="text-gray-600">
                        Dengan mendaftarkan peserta, Anda menyetujui bahwa semua data yang dimasukkan adalah benar dan akurat.
                    </p>
                </div>
                
                <div class="px-8 py-8 text-center space-y-6">
                    <!-- reCAPTCHA v3 will be handled automatically in JavaScript -->
                    
                    <button type="submit" 
                            class="inline-flex items-center px-12 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white text-xl font-bold rounded-xl shadow-lg hover:shadow-2xl hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:-translate-y-1 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none" 
                            id="submitBtn">
                        <i class="fas fa-paper-plane mr-3"></i>
                        Daftarkan Semua Peserta
                        <i class="fas fa-arrow-right ml-3"></i>
                    </button>
                    
                    <p class="text-sm text-gray-500 max-w-md mx-auto">
                        <i class="fas fa-clock mr-1"></i>
                        Proses pendaftaran mungkin membutuhkan waktu beberapa saat. Mohon tunggu dan jangan refresh halaman.
                    </p>
                </div>
            </div>

        </form>
        
        </div> <!-- End of registrationFormSection -->
    </div>
</div>
@endsection

@push('styles')
<!-- Location Autocomplete CSS -->
<link rel="stylesheet" href="{{ asset('css/location-autocomplete.css') }}">
@endpush

@push('scripts')
<!-- Location Autocomplete JS -->
<script src="{{ asset('js/location-autocomplete.js') }}"></script>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Location Autocomplete -->
<script src="{{ asset('js/location-autocomplete.js') }}"></script>

<!-- Laravel Data Injection -->
<script>
console.log('=== DEBUGGING LARAVEL DATA INJECTION ===');

// Check what Laravel passes
const jerseySizesRaw = @json($jerseySizes);
const raceCategoriesRaw = @json($raceCategories);
const bloodTypesRaw = @json($bloodTypes);
const eventSourcesRaw = @json($eventSources);

console.log('RAW DATA FROM LARAVEL:');
console.log('Jersey Sizes Raw:', jerseySizesRaw);
console.log('Race Categories Raw:', raceCategoriesRaw);
console.log('Blood Types Raw:', bloodTypesRaw);
console.log('Event Sources Raw:', eventSourcesRaw);

// Check types
console.log('DATA TYPES:');
console.log('Jersey Sizes type:', typeof jerseySizesRaw, 'is array:', Array.isArray(jerseySizesRaw));
console.log('Race Categories type:', typeof raceCategoriesRaw, 'is array:', Array.isArray(raceCategoriesRaw));
console.log('Blood Types type:', typeof bloodTypesRaw, 'is array:', Array.isArray(bloodTypesRaw));
console.log('Event Sources type:', typeof eventSourcesRaw, 'is array:', Array.isArray(eventSourcesRaw));

window.laravelData = {
    jerseySizes: jerseySizesRaw,
    raceCategories: raceCategoriesRaw,
    bloodTypes: bloodTypesRaw,
    eventSources: eventSourcesRaw
};

console.log('FINAL WINDOW DATA:', window.laravelData);

// reCAPTCHA v3 Configuration
window.recaptchaSiteKey = '{{ config("services.recaptcha.site_key") }}';
console.log('reCAPTCHA Site Key:', window.recaptchaSiteKey);
console.log('=== END DEBUGGING ===');
</script>

<!-- Collective Registration -->
<script src="{{ asset('js/collective-registration.js') }}"></script>

<script>
// Terms and Conditions handling - Same as register page
document.addEventListener('DOMContentLoaded', function() {
    const termsContent = document.getElementById('termsContent');
    const progressBar = document.getElementById('progressBar');
    const readProgress = document.getElementById('readProgress');
    const acceptBtn = document.getElementById('acceptTermsBtn');
    const registrationSection = document.getElementById('registrationFormSection');
    
    let hasReachedEnd = false;
    let isTermsAccepted = false;
    let readPercentage = 0;
    
    // Make isTermsAccepted available globally for collective-registration.js
    window.isTermsAccepted = isTermsAccepted;
    
    // Track scrolling in terms content - exact same logic as register page
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
        
        // Update button state
        if (readPercentage < 100) {
            acceptBtn.disabled = true;
            acceptBtn.className = 'px-8 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed transition-all duration-300';
            acceptBtn.innerHTML = `<i class="fas fa-clock mr-2"></i>Membaca... (${readPercentage}%)`;
        } else {
            // User has read to the end
            if (!hasReachedEnd) {
                hasReachedEnd = true;
                acceptBtn.disabled = false;
                acceptBtn.className = 'px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg cursor-pointer transition-all duration-300';
                acceptBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Saya Setuju dengan Syarat dan Ketentuan';
                
                // Show success message
                showReadCompleteMessage();
            }
        }
    });
    
    function showReadCompleteMessage() {
        // Create a temporary success message
        const successMsg = document.createElement('div');
        successMsg.className = 'bg-green-50 border border-green-200 rounded-lg p-3 mb-4 slide-in-right';
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
                successMsg.classList.add('slide-out-right');
                setTimeout(() => {
                    if (successMsg.parentNode) {
                        successMsg.parentNode.removeChild(successMsg);
                    }
                }, 500);
            }
        }, 5000);
    }
    
    // Handle accept button click
    acceptBtn.addEventListener('click', function() {
        if (hasReachedEnd && !isTermsAccepted) {
            isTermsAccepted = true;
            
            // Update global variable for collective-registration.js
            window.isTermsAccepted = isTermsAccepted;
            
            // Hide terms section
            document.getElementById('termsSection').style.display = 'none';
            
            // Show registration form section
            registrationSection.style.display = 'block';
            
            // Scroll to registration form
            registrationSection.scrollIntoView({ behavior: 'smooth' });
            
            // Show success notification
            showAcceptanceNotification();
            
            // Trigger submit button state update if updateSubmitButtonState function exists
            if (typeof window.debugSubmitButton === 'function') {
                setTimeout(() => {
                    window.debugSubmitButton();
                }, 500);
            }
        }
    });
    
    function showAcceptanceNotification() {
        const successMsg = document.createElement('div');
        successMsg.className = 'bg-emerald-50 border-l-4 border-emerald-400 rounded-lg p-4 mb-6 shadow-sm slide-in-right';
        successMsg.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-emerald-400 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-emerald-800">
                        <i class="fas fa-scroll mr-1"></i>
                        Syarat dan ketentuan telah diterima. Silakan lanjutkan dengan mengisi form registrasi kolektif.
                    </p>
                </div>
            </div>
        `;
        
        registrationSection.insertBefore(successMsg, registrationSection.firstChild);
        
        // Remove success message after 7 seconds
        setTimeout(() => {
            if (successMsg.parentNode) {
                successMsg.classList.add('slide-out-right');
                setTimeout(() => {
                    if (successMsg.parentNode) {
                        successMsg.remove();
                    }
                }, 500);
            }
        }, 7000);
    }
});
</script>

<!-- Floating Add Button Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const floatingBtn = document.getElementById('floatingAddBtn');
    const originalBtn = document.getElementById('addParticipantBtn');
    let isFloatingVisible = false;
    
    function toggleFloatingButton() {
        const scrollY = window.scrollY;
        const windowHeight = window.innerHeight;
        const shouldShow = scrollY > windowHeight * 0.3; // Show after scrolling 30% of viewport
        
        if (shouldShow && !isFloatingVisible) {
            // Show floating button
            floatingBtn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-16');
            floatingBtn.classList.add('opacity-100', 'pointer-events-auto', 'translate-y-0');
            isFloatingVisible = true;
        } else if (!shouldShow && isFloatingVisible) {
            // Hide floating button
            floatingBtn.classList.remove('opacity-100', 'pointer-events-auto', 'translate-y-0');
            floatingBtn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-16');
            isFloatingVisible = false;
        }
    }
    
    // Add scroll listener with throttling for better performance
    let ticking = false;
    function handleScroll() {
        if (!ticking) {
            requestAnimationFrame(function() {
                toggleFloatingButton();
                ticking = false;
            });
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', handleScroll);
    
    // Initial check
    toggleFloatingButton();
});
</script>

<!-- Initialize Location Autocomplete for all forms -->
<script>
// Set the correct API URL for location search
window.locationSearchUrl = '{{ url("api/location/search") }}';

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking for LocationAutocomplete...');
    console.log('Location search URL:', window.locationSearchUrl);
    
    // Wait a bit to ensure all scripts are loaded
    setTimeout(() => {
        if (typeof LocationAutocomplete !== 'undefined') {
            console.log('LocationAutocomplete is available, initializing for existing forms...');
            
            // Initialize autocomplete for all existing regency search inputs
            const regencyInputs = document.querySelectorAll('input[name*="regency_search"]');
            console.log(`Found ${regencyInputs.length} regency search inputs`);
            
            regencyInputs.forEach((input, index) => {
                const participantAttr = input.getAttribute('data-participant');
                if (participantAttr) {
                    console.log(`Initializing autocomplete for participant ${participantAttr}`);
                    
                    const selector = `[data-participant="${participantAttr}"] input[name*="regency_search"]`;
                    try {
                        const autocomplete = new LocationAutocomplete(selector);
                        
                        // Add event listener for location selection
                        input.addEventListener('locationSelected', function(e) {
                            const selection = e.detail;
                            console.log(`Location selected for participant ${participantAttr}:`, selection);
                            
                            // Update hidden fields
                            const regencyIdField = document.querySelector(`#regency_id_${participantAttr}`);
                            const regencyNameField = document.querySelector(`[data-participant="${participantAttr}"] input[name*="regency_name"]`);
                            const provinceNameField = document.querySelector(`[data-participant="${participantAttr}"] input[name*="province_name"]`);
                            
                            if (regencyIdField) regencyIdField.value = selection.id;
                            if (regencyNameField) regencyNameField.value = selection.name;
                            if (provinceNameField) provinceNameField.value = selection.province_name;
                        });
                        
                        console.log(`Autocomplete initialized for participant ${participantAttr}`);
                    } catch (error) {
                        console.error(`Error initializing autocomplete for participant ${participantAttr}:`, error);
                    }
                }
            });
        } else {
            console.error('LocationAutocomplete class not found!');
        }
    }, 500);
});
</script>

<!-- Load reCAPTCHA v3 Script -->
<script>
    // Load reCAPTCHA script
    const script = document.createElement('script');
    script.src = 'https://www.google.com/recaptcha/api.js?render={{ config("services.recaptcha.site_key") }}';
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
    
    script.onload = function() {
        console.log('reCAPTCHA v3 script loaded successfully');
        if (typeof grecaptcha !== 'undefined') {
            grecaptcha.ready(function() {
                console.log('reCAPTCHA v3 is ready');
            });
        }
    };
    
    script.onerror = function() {
        console.error('Failed to load reCAPTCHA v3 script');
    };
</script>
@endpush
