<footer>
    <div class="td-footer-area bg-position pt-110" data-background="{{ asset('evente-assets/img/footer/bg.jpg') }}">
        <div class="td-footer-main pt-65 pb-80">
            <div class="container">
                <div class="row mb-30">
                    <div class="col-xl-5 col-lg-6 col-md-6">
                        <div class="td-footer-5-widget mb-40">
                            <div class="logo mb-30">
                                <a href="{{ url('/') }}">
                                    <img data-width="265" src="{{ asset('evente-assets/img/hero/hero-5/logo-footer.png') }}" alt="Logo BIK SULTRA 2025">
                                </a>
                            </div>
                            <h4>Bulan Inklusi Keuangan 2025 <br> Otoritas Jasa Keuangan <br>Provinsi Sulawesi Tenggara</h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-5">
                        <div class="td-footer-widget td-footer-5-widget ml-60 mb-40">
                            <h3 class="td-footer-title mb-20">Alamat</h3>
                            <p class="text mb-10">Jl. H. Abdul Silondae No.95A Kota Kendari, Sulawesi Tenggara.</p>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6">
                        <div class="td-footer-widget td-footer-5-widget ml-50 mb-40">
                            <h3 class="td-footer-title mb-20">Link Cepat</h3>
                            <div class="td-footer-links">
                                <ul>
                                    <li><a href="#home" class="section-scroll">Beranda</a></li>
                                    <li><a href="#about" class="section-scroll">Tentang Acara</a></li>
                                    <li><a href="#schedule" class="section-scroll">Jadwal</a></li>
                                    <li><a href="#partners" class="section-scroll">Partner</a></li>
                                    <li><a href="{{ route('register') }}">Daftar Event</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6">
                        <div class="td-footer-widget td-footer-5-widget ml-50 mb-40">
                            <h3 class="td-footer-title mb-20">Official Media Sosial</h3>
                            <div class="td-footer-links">
                                <ul>
                                    <li><a href="https://www.facebook.com/official.ojk" target="_blank">Facebook</a></li>
                                    <li><a href="https://x.com/ojkindonesia" target="_blank">Twitter</a></li>
                                    <li><a href="https://www.instagram.com/ojk_sultra/" target="_blank">Instagram</a></li>
                                    <li><a href="https://www.youtube.com/@OtoritasJasaKeuangan" target="_blank">YouTube</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="td-footer-bottom-copyright td-footer-4-copyright text-center">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <p>&copy; {{ date('Y') }} BIK SULTRA 2025. Dikembangkan oleh <a href="#" target="_blank">OJK Sulawesi Tenggara</a>. Semua hak cipta dilindungi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>