<!-- td-banner-area-start -->
<div class="td-countdown-area td-countdown-6-wrap p-relative z-index-1">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="td-banner-title-wrap td-banner-5-wrap">
                    <span class="td-section-subtitle mb-20  wow fadeInLeft" data-wow-duration=".9s" data-wow-delay=".2s">Daftar Sekarang</span>
                    <h2 class="td-section-title mb-20  wow fadeInLeft" data-wow-duration=".9s" data-wow-delay=".4s">{{ config('event.short_name') }} {{ config('event.year') }}</h2>
                    <div class="td-hero-countdown mb-20 wow fadeInLeft" data-wow-duration="1.5s" data-wow-delay="0.5s">
                        <ul class="deal-counter ml0-md" id="timer">
                            <li class="list-inline-item days mb-20">00</li>
                            <li class="list-inline-item hours mb-20">00</li>
                            <li class="list-inline-item minutes mb-20">00</li>
                            <li class="list-inline-item seconds mb-20">00</li>
                        </ul>
                    </div>
                    <a class="td-btn td-btn-3-squre mb-15 wow td-left-right text3 mr-25wow fadeInLeft" data-wow-duration="1.5s" data-wow-delay="0.6s" href="nightrun">
                        <span class="mr10 td-text d-inline-block mr-5">Daftar</span>
                        <span class="td-arrow-angle">
                            <svg class="td-arrow-svg-top-right" width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.943836 13.5C0.685616 13.5 0.45411 13.4021 0.276027 13.224C0.0979452 13.0459 0 12.8055 0 12.5562C0 12.3068 0.0979452 12.0664 0.276027 11.8884L9.76781 2.38767H2.02123C1.49589 2.38767 1.0774 1.96027 1.0774 1.44384C1.0774 0.927397 1.50479 0.5 2.03014 0.5H12.0562C12.1274 0.5 12.1986 0.508904 12.2788 0.526712L12.4034 0.562329L12.537 0.633562C12.5637 0.65137 12.5993 0.678082 12.626 0.69589C12.6973 0.749315 12.7507 0.80274 12.7952 0.856164C12.8219 0.891781 12.8575 0.927397 12.8842 0.989726L12.9555 1.1411L12.9822 1.22123C13 1.29247 13.0089 1.3726 13.0089 1.44384V11.4699C13.0089 11.9952 12.5815 12.4137 12.0651 12.4137C11.5486 12.4137 11.1212 11.9863 11.1212 11.4699V3.72329L1.62055 13.224C1.44247 13.4021 1.20205 13.5 0.943836 13.5Z" fill="white" />
                                <path d="M0.943836 13.5C0.685616 13.5 0.45411 13.4021 0.276027 13.224C0.0979452 13.0459 0 12.8055 0 12.5562C0 12.3068 0.0979452 12.0664 0.276027 11.8884L9.76781 2.38767H2.02123C1.49589 2.38767 1.0774 1.96027 1.0774 1.44384C1.0774 0.927397 1.50479 0.5 2.03014 0.5H12.0562C12.1274 0.5 12.1986 0.508904 12.2788 0.526712L12.4034 0.562329L12.537 0.633562C12.5637 0.65137 12.5993 0.678082 12.626 0.69589C12.6973 0.749315 12.7507 0.80274 12.7952 0.856164C12.8219 0.891781 12.8575 0.927397 12.8842 0.989726L12.9555 1.1411L12.9822 1.22123C13 1.29247 13.0089 1.3726 13.0089 1.44384V11.4699C13.0089 11.9952 12.5815 12.4137 12.0651 12.4137C11.5486 12.4137 11.1212 11.9863 11.1212 11.4699V3.72329L1.62055 13.224C1.44247 13.4021 1.20205 13.5 0.943836 13.5Z" fill="white" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="td-banner-image-wrap text-center wow fadeInRight" data-wow-duration="1.5s" data-wow-delay="0.4s">
                    <!-- Konten untuk kolom kedua - bisa berupa gambar, video, atau konten lainnya -->
                    <div class="td-banner-content-right">
                        <img src="{{ asset('evente-assets/img/banner/ctd.png') }}" alt="{{ config('event.short_name') }} {{ config('event.year') }}" class="img-fluid">
                        <!-- Atau jika ingin menambahkan konten lain -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-banner-area-end -->