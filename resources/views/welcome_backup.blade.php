@extends('layouts.evente')

@section('title', 'BIK SULTRA 2025 | Bulan Inklusi Keuangan Sulawesi Tenggara - Event Lomba & Talkshow OJK')

@section('content')

<!-- td-hero-area-start -->
<div id="home" class="td-hero-area grey-bg p-relative z-index-1 fix">
    <div class="td-hero-4-blur"></div>
    <img class="td-hero-5-shape rotate-infinite-2 d-none d-xxl-block" src="{{ asset('evente-assets/img/hero/hero-5/shape.png') }}" alt="">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-xl-7">
                <div class="td-hero-5-content p-relative">
                    <span class="td-hero-5-shape-2 d-none d-sm-block">
                        <svg width="102" height="92" viewBox="0 0 102 92" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M33.8464 55.1662C38.6797 64.1882 40.9494 73.2707 40.504 79.91C34.7299 76.6027 28.426 69.6815 23.5926 60.6595C18.7593 51.6375 16.4896 42.555 16.935 35.9157C22.709 39.223 29.013 46.1442 33.8464 55.1662ZM93.5632 23.1741C101.645 38.2591 102.56 53.5093 95.6071 57.2341C93.1959 58.5258 90.1523 58.2615 86.8688 56.7345C87.6341 49.5994 85.411 39.8728 80.278 30.2914C75.145 20.7101 68.2818 13.4692 61.9148 10.1551C62.4626 6.57553 63.9257 3.89686 66.3399 2.60351C73.2926 -1.12126 85.4817 8.08914 93.5632 23.1741ZM85.2924 55.9155C79.5183 52.6082 73.2143 45.687 68.381 36.665C63.5476 27.643 61.2779 18.5605 61.7233 11.9213C67.4974 15.2285 73.8013 22.1498 78.6347 31.1718C83.4681 40.1938 85.7378 49.2763 85.2924 55.9155ZM95.2065 22.2938C103.713 38.1729 104.237 54.4608 96.3772 58.6715C93.6385 60.1388 90.2454 59.9461 86.6245 58.4021C85.904 62.272 84.1846 65.2035 81.4459 66.6707C78.7071 68.138 75.314 67.9453 71.6931 66.4013C70.9726 70.2711 69.2533 73.2027 66.5145 74.6699C63.7757 76.1372 60.3827 75.9445 56.7618 74.4005C56.0412 78.2703 54.3219 81.2019 51.5831 82.6691C48.8443 84.1364 45.4513 83.9437 41.8304 82.3997C41.1098 86.2695 39.3905 89.2011 36.6517 90.6683C28.7919 94.8791 15.522 85.4196 7.01509 69.5405C-1.49182 53.6615 -2.00687 37.3689 5.85297 33.1581C8.59175 31.6909 11.9848 31.8836 15.6057 33.4275C16.3263 29.5577 18.0456 26.6262 20.7844 25.1589C23.5231 23.6917 26.9162 23.8844 30.5371 25.4283C31.2577 21.5585 32.977 18.627 35.7157 17.1597C38.4545 15.6925 41.8476 15.8852 45.4685 17.4292C46.1832 13.5624 47.9026 10.6309 50.6413 9.16366C53.801 7.69642 56.7732 7.8891 60.3941 9.43307C61.1117 5.5648 62.831 2.63326 65.5698 1.16602C73.4297 -3.04473 86.6996 6.41469 95.2065 22.2938ZM85.1009 57.6816C78.7367 54.366 71.8707 47.1267 66.7377 37.5453C61.6047 27.964 59.3787 18.2389 60.1469 11.1022C56.8633 9.57523 53.8227 9.30936 51.4085 10.6027C48.9944 11.8961 47.5312 14.5747 46.9834 18.1543C53.3476 21.47 60.2136 28.7092 65.3466 38.2906C70.4796 47.872 72.7056 57.597 71.9374 64.7337C75.221 66.2607 78.2616 66.5266 80.6758 65.2332C83.0899 63.9399 84.5531 61.2612 85.1009 57.6816ZM70.3639 63.9131C64.5898 60.6059 58.2858 53.6846 53.4525 44.6626C48.6191 35.6406 46.3494 26.5581 46.7948 19.9189C52.5689 23.2262 58.8729 30.1474 63.7062 39.1694C68.5396 48.1914 70.8093 57.2739 70.3639 63.9131ZM70.1724 65.6793C63.8083 62.3636 56.9422 55.1243 51.8092 45.543C46.6762 35.9616 44.4502 26.2366 45.2184 19.0999C41.9349 17.5729 38.8942 17.307 36.4801 18.6003C34.0659 19.8937 32.6027 22.5724 32.0549 26.1519C38.4191 29.4676 45.2851 36.7069 50.4181 46.2882C55.5511 55.8696 57.7772 65.5947 57.0089 72.7314C60.2925 74.2584 63.3331 74.5242 65.7473 73.2309C68.1615 71.9375 69.6246 69.2589 70.1724 65.6793ZM55.4354 71.9108C49.6613 68.6035 43.3574 61.6823 38.524 52.6603C33.6906 43.6383 31.4209 34.5558 31.8664 27.9165C37.6404 31.2238 43.9444 38.145 48.7777 47.167C53.6111 56.189 55.8808 65.2715 55.4354 71.9108ZM55.2439 73.6769C48.8798 70.3613 42.0138 63.122 36.8807 53.5406C31.7477 43.9593 29.5217 34.2342 30.2899 27.0975C27.0064 25.5705 23.9657 25.3046 21.5516 26.598C19.1374 27.8913 17.6743 30.57 17.1264 34.1496C23.4906 37.4652 30.3566 44.7045 35.4896 54.2859C40.6226 63.8672 42.8487 73.5923 42.0804 80.729C45.364 82.256 48.4046 82.5219 50.8188 81.2285C53.233 79.9352 54.6961 77.2565 55.2439 73.6769ZM40.3154 81.6746C39.7676 85.2541 38.3045 87.9328 35.8903 89.2262C28.9376 92.9509 16.7485 83.7405 8.66704 68.6555C0.585556 53.5706 -0.329631 38.3204 6.62308 34.5956C9.03436 33.3038 12.0779 33.5681 15.3615 35.0951C14.5961 42.2303 16.8192 51.9569 21.9523 61.5383C27.0853 71.1196 33.9484 78.3604 40.3154 81.6746Z" fill="#5033FF" />
                        </svg>
                    </span>
                    <h2 class="td-hero-2-title td-hero-5-tittle wow td-animetion-left" data-wow-duration="1.5s" data-wow-delay="0.1s">Bulan Inklusi<br> Keuangan  <br> Sulawesi Tenggara <br> 2<img src="{{ asset('evente-assets/img/hero/hero-4/zero.png') }}" alt="">25</h2>
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="td-hero-5-small-thumb wow td-animetion-top" data-wow-duration="1.5s" data-wow-delay="0.1s">
                                <img src="{{ asset('evente-assets/img/hero/hero-5/thumb.png') }}" alt="">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="td-hero-5-content-bottom ml-20 mb-30 wow td-animetion-right" data-wow-duration="1.5s" data-wow-delay="0.2s">
                                <p class="mb-20">program tahunan yang diselenggarakan oleh Otoritas Jasa Keuangan (OJK) <br>
                                    bersama industri jasa keuangan di Indonesia. <br>
                                </p>
                                <div class="td-hero-4-border mb-30"></div>
                                <span class="td-hero-4-dates mb-10 d-inline-block">October 2025 </span>
                                <p class="td-hero-4-para mb-20">Kator OJK Provinsi Sulawesi Tenggara<br>  Kendari, Sulawesi Tenggara</p>
                                <a class="td-btn td-btn-3-squre td-left-right text3 mr-25" href="events.html">
                                    <span class="mr10 td-text d-inline-block mr-5">Chek Event</span>
                                    <span class="td-arrow-angle">
                                        <svg class="td-arrow-svg-top-right" width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.943836 13.5C0.685616 13.5 0.45411 13.4021 0.276027 13.224C0.0979452 13.0459 0 12.8055 0 12.5562C0 12.3068 0.0979452 12.0664 0.276027 11.8884L9.76781 2.38767H2.02123C1.49589 2.38767 1.0774 1.96027 1.0774 1.44384C1.0774 0.927397 1.50479 0.5 2.03014 0.5H12.0562C12.1274 0.5 12.1986 0.508904 12.2788 0.526712L12.4034 0.562329L12.537 0.633562C12.5637 0.65137 12.5993 0.678082 12.626 0.69589C12.6973 0.749315 12.7507 0.80274 12.7952 0.856164C12.8219 0.891781 12.8575 0.927397 12.8842 0.989726L12.9555 1.1411L12.9822 1.22123C13 1.29247 13.0089 1.3726 13.0089 1.44384V11.4699C13.0089 11.9952 12.5815 12.4137 12.0651 12.4137C11.5486 12.4137 11.1212 11.9863 11.1212 11.4699V3.72329L1.62055 13.224C1.44247 13.4021 1.20205 13.5 0.943836 13.5Z" fill="white" />
                                            <path d="M0.943836 13.5C0.685616 13.5 0.45411 13.4021 0.276027 13.224C0.0979452 13.0459 0 12.8055 0 12.5562C0 12.3068 0.0979452 12.0664 0.276027 11.8884L9.76781 2.38767H2.02123C1.49589 2.38767 1.0774 1.96027 1.0774 1.44384C1.0774 0.927397 1.50479 0.5 2.03014 0.5H12.0562C12.1274 0.5 12.1986 0.508904 12.2788 0.526712L12.4034 0.562329L12.537 0.633562C12.5637 0.65137 12.5993 0.678082 12.626 0.69589C12.6973 0.749315 12.7507 0.80274 12.7952 0.856164C12.8219 0.891781 12.8575 0.927397 12.8842 0.989726L12.9555 1.1411L12.9822 1.22123C13 1.29247 13.0089 1.3726 13.0089 1.44384V11.4699C13.0089 11.9952 12.5815 12.4137 12.0651 12.4137C11.5486 12.4137 11.1212 11.9863 11.1212 11.4699V3.72329L1.62055 13.224C1.44247 13.4021 1.20205 13.5 0.943836 13.5Z" fill="white" />
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-9">
                <div class="td-hero-5-thumb-slider">
                    <div class="swiper-container td-hero-5-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="td-hero-5-thumb">
                                    <img src="{{ asset('evente-assets/img/hero/hero-5/thumb-3.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="td-hero-5-thumb">
                                    <img src="{{ asset('evente-assets/img/hero/hero-5/thumb-3.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="td-hero-5-thumb">
                                    <img src="{{ asset('evente-assets/img/hero/hero-5/thumb-3.jpg') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-hero-area-end -->

<!-- td-text-slider-area-start -->
<div class="td-text-slider-area td-text-slider-spacing">
    <div class="container-fluid">
        <div class="row">
           <div class="swiper-container td-text-slider-active">
                  <div class="swiper-wrapper slide-transtion">
                     <div class="swiper-slide">
                        <div class="td-text-slider-item">
                           <h3 class="text">Bulan Inklusi Keuangan 2025</h3>
                           <img src="{{ asset('evente-assets/img/text/round.png') }}" alt="round">
                        </div>
                     </div>
                     <div class="swiper-slide">
                        <div class="td-text-slider-item">
                           <h3 class="text">OJK SULTRA</h3>
                           <img src="{{ asset('evente-assets/img/text/round.png') }}" alt="round">
                        </div>
                     </div>
                     <div class="swiper-slide">
                        <div class="td-text-slider-item">
                           <h3 class="text">BIK SULTRA RUN 2025</h3>
                           <img src="{{ asset('evente-assets/img/text/round.png') }}" alt="round">
                        </div>
                     </div>
                  </div>
               </div>     
        </div>
    </div>
</div>
<!-- td-text-slider-area-end -->

<!-- td-about-area-start -->
<div id="about" class="td-about-area pt-140">
    <div class="container-fluid container-1700">
        <div class="row gx-20 align-items-center">
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="td-about-6-thumb mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.3s">
                    <img src="{{ asset('evente-assets/img/about/about-5/thumb.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="td-about-6-thumb mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.4s">
                    <img src="{{ asset('evente-assets/img/about/about-5/thumb-2.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="td-about-6-content text-center mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.5s">
                    <span class="td-section-subtitle td-section-subtitle-2 td-section-subtitle-3 mb-15">Acara Yang akan berlangsung</span>
                    <h2 class="td-about-6-tittle">Berbagai Lomba dan<br> Talkshow</h2>
                    <p class="para">Dalam memperingati bulan inklusi keuangan 2025<br>
                    OJK Sulawesi Tenggara Mengadakan beberapa rangkaian <br> acara yang akan berlangsung.</p>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="td-about-6-thumb mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.6s">
                    <img src="{{ asset('evente-assets/img/about/about-5/thumb-3.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="td-about-6-thumb mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.7s">
                    <img src="{{ asset('evente-assets/img/about/about-5/thumb-4.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-about-area-end -->

<!-- td-counter-area-start -->
<div id="stats" class="td-counter-area pt-70 pb-115 overlay-container">
    <!-- Glass Overlay with Coming Soon -->
    <div class="glass-overlay">
        <div class="coming-soon-content">
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="td-counter-2-wrap td-counter-5-wrap mb-30">
                    <span class="td-counter-2-icon mr-20">
                        <i class="flaticon-speaker"></i>
                    </span>
                    <div class="td-counter-2-content">
                        <h2 class="td-counter-2-title count"><span class="odometer" data-count="65"></span>+</h2>
                        <span class="td-counter-2-subtitle">Skilled Speakers</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="td-counter-2-wrap td-counter-5-wrap mb-30">
                    <span class="td-counter-2-icon mr-20">
                        <i class="flaticon-teamwork"></i>
                    </span>
                    <div class="td-counter-2-content">
                        <h2 class="td-counter-2-title count">
                            <span class="odometer" data-count="20"></span>
                            +
                        </h2>
                        <span class="td-counter-2-subtitle">Live Workshop</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="td-counter-2-wrap td-counter-5-wrap mb-30">
                    <span class="td-counter-2-icon mr-20">
                        <i class="flaticon-fastfood"></i>
                    </span>
                    <div class="td-counter-2-content">
                        <h2 class="td-counter-2-title count"><span class="odometer" data-count="26"></span>+</h2>
                        <span class="td-counter-2-subtitle">Snacks & Facilities</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="td-counter-2-wrap td-counter-5-wrap mb-30">
                    <span class="td-counter-2-icon mr-20">
                        <i class="flaticon-deal"></i>
                    </span>
                    <div class="td-counter-2-content">
                        <h2 class="td-counter-2-title count"><span class="odometer" data-count="25"></span>+</h2>
                        <span class="td-counter-2-subtitle">Popular Snacks</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-counter-area-end -->

<!-- td-schedule-area-start -->
<div id="schedule" class="td-schedule-area grey-bg-4 pt-130 p-relative z-index-1 fix pb-130">
    <div class="td-hero-4-blur td-team-5-blur"></div>
    <div class="td-hero-4-blur td-hero-4-blur-2 td-team-5-blur-2"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-7 col-xl-9 col-lg-10">
                <div class="td-schedule-4-title-wrap text-center mb-30  wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.3s">
                    <span class="td-section-subtitle td-section-subtitle-2 mb-20">Jadwal Acara</span>
                    <h2 class="td-section-title mb-10">Rencana Jadwal Acara Kami</h2>
                    <p class="td-section-text">Seperti tahun sebelumnya, tahun ini kami menyelenggarakan <br>
                        beberapa acara dalam rangka bulan inklusi keuangan 2025.</p>
                </div>
                <div class="nav td-schedule-5-tab-btn justify-content-center mb-30  wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.5s" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active p-relative mb-10" id="v-pills-home1-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home1" type="button" role="tab" aria-controls="v-pills-home1" aria-selected="false">
                        <span class="td-counts">Day-01</span>
                        <span class="d-block"> Oct, 2025</span>
                    </button>
                    <button class="nav-link p-relative mb-10 disabled coming-soon-tab" disabled style="background-color: #888; color: #ddd; cursor: not-allowed; opacity: 0.6;">
                        <span class="td-counts">Day-02</span>
                        <span class="d-block">Coming Soon</span>
                    </button>
                    <button class="nav-link p-relative mb-10 disabled coming-soon-tab" disabled style="background-color: #888; color: #ddd; cursor: not-allowed; opacity: 0.6;">
                        <span class="td-counts">Day-03</span>
                        <span class="d-block">Coming Soon</span>
                    </button>
                 </div>
            </div>
        </div>
        <div class="row">
            <div class="tp-contact-form-wrapper tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade active show" id="v-pills-home1" role="tabpanel" aria-labelledby="v-pills-home1-tab">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="td-schedule-5-wrap">
                                <div class="row gx-30 align-items-center">
                                    <div class="col-xl-4 col-lg-5">
                                        <div class="td-schedule-5-left-content d-flex align-items-center">
                                            <div class="td-schedule-5-thumb mb-20 mr-30">
                                                <a href="#"><img src="{{ asset('evente-assets/img/schedule/schedule-6/e-1.png') }}" alt=""></a>
                                            </div>
                                            <div class="td-schedule-5-date-wrap mb-20 w-100">
                                                <div class="td-schedule-5-date d-flex mb-15">
                                                    <h4 class="mb-0 mr-10">25</h4>
                                                    <span>Oktober,<br> 2025</span>
                                                </div>
                                                <div class="td-schedule-5-left-border mb-15"></div>
                                                <div class="td-schedule-5-name">
                                                    <h4 class="mb-0">Event Lari</h4>
                                                    <span>BIK Night Run</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-5 col-lg-7">
                                        <div class="td-schedule-4-content td-schedule-5-content mb-30">
                                            <h2 class="td-schedule-4-title"><a href="#">BIK NIGHT RUN 2025</a></h2>
                                            <p>Dalam rangka memperingati Bulan Inklusi Keuangan (BIK) 2025, Otoritas Jasa Keuangan (OJK) bersama Industri Jasa Keuangan menyelenggarakan BIK Night Run 2025 .</p>
                                            <div class="td-schedule-4-destination mb-10">
                                                <span>
                                                    <i class="flaticon-gps"></i>
                                                    Pelataran Parkir MTQ
                                                </span>
                                                <span>
                                                    <i class="flaticon-time"></i>
                                                    19:00 - Selesai
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-12">
                                        <div class="td-schedule-5-btn text-xl-end">
                                            <a class="td-btn td-btn-green td-btn-3-squre td-left-right text" href="nighrun">
                                                <span class="td-text d-inline-block mr-5">Daftar Sekarang</span>
                                                <span class="td-arrow-angle">
                                                    <svg class="td-arrow-svg-top-right" width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M0.943836 13.5C0.685616 13.5 0.45411 13.4021 0.276027 13.224C0.0979452 13.0459 0 12.8055 0 12.5562C0 12.3068 0.0979452 12.0664 0.276027 11.8884L9.76781 2.38767H2.02123C1.49589 2.38767 1.0774 1.96027 1.0774 1.44384C1.0774 0.927397 1.50479 0.5 2.03014 0.5H12.0562C12.1274 0.5 12.1986 0.508904 12.2788 0.526712L12.4034 0.562329L12.537 0.633562C12.5637 0.65137 12.5993 0.678082 12.626 0.69589C12.6973 0.749315 12.7507 0.80274 12.7952 0.856164C12.8219 0.891781 12.8575 0.927397 12.8842 0.989726L12.9555 1.1411L12.9822 1.22123C13 1.29247 13.0089 1.3726 13.0089 1.44384V11.4699C13.0089 11.9952 12.5815 12.4137 12.0651 12.4137C11.5486 12.4137 11.1212 11.9863 11.1212 11.4699V3.72329L1.62055 13.224C1.44247 13.4021 1.20205 13.5 0.943836 13.5Z" fill="currentColor" />
                                                        <path d="M0.943836 13.5C0.685616 13.5 0.45411 13.4021 0.276027 13.224C0.0979452 13.0459 0 12.8055 0 12.5562C0 12.3068 0.0979452 12.0664 0.276027 11.8884L9.76781 2.38767H2.02123C1.49589 2.38767 1.0774 1.96027 1.0774 1.44384C1.0774 0.927397 1.50479 0.5 2.03014 0.5H12.0562C12.1274 0.5 12.1986 0.508904 12.2788 0.526712L12.4034 0.562329L12.537 0.633562C12.5637 0.65137 12.5993 0.678082 12.626 0.69589C12.6973 0.749315 12.7507 0.80274 12.7952 0.856164C12.8219 0.891781 12.8575 0.927397 12.8842 0.989726L12.9555 1.1411L12.9822 1.22123C13 1.29247 13.0089 1.3726 13.0089 1.44384V11.4699C13.0089 11.9952 12.5815 12.4137 12.0651 12.4137C11.5486 12.4137 11.1212 11.9863 11.1212 11.4699V3.72329L1.62055 13.224C1.44247 13.4021 1.20205 13.5 0.943836 13.5Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-schedule-area-end -->

<!-- td-brands-area-start -->
<div id="partners" class="td-brands-area td-brands-5-wrap grey-bg-4 pt-140 pb-115">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="td-schedule-2-title-wrap text-center mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.3s">
                    <span class="td-section-subtitle td-section-subtitle-2 mb-20">Partner</span>
                    <h2 class="td-section-title mb-10">Offcial Partners</h2>
                    <p class="td-section-text">Partner Bulan Inklusi Keuangan 2025<br></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-6 wow fadeInLeft" data-wow-duration=".9s" data-wow-delay=".2s">
                <div class="td-brands-item mb-25">
                    <a href="#"><img src="{{ asset('evente-assets/img/brands/l1.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 wow fadeInLeft" data-wow-duration=".9s" data-wow-delay=".3s">
                <div class="td-brands-item mb-25">
                    <a href="#"><img src="{{ asset('evente-assets/img/brands/l2.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 wow fadeInLeft" data-wow-duration=".9s" data-wow-delay=".4s">
                <div class="td-brands-item mb-25">
                    <a href="#"><img src="{{ asset('evente-assets/img/brands/l3.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 wow fadeInLeft" data-wow-duration=".9s" data-wow-delay=".5s">
                <div class="td-brands-item mb-25">
                    <a href="#"><img src="{{ asset('evente-assets/img/brands/l4.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 wow fadeInLeft" data-wow-duration=".9s" data-wow-delay=".6s">
                <div class="td-brands-item mb-25">
                    <a href="#"><img src="{{ asset('evente-assets/img/brands/l5.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 wow fadeInLeft" data-wow-duration=".9s" data-wow-delay=".7s">
                <div class="td-brands-item mb-25">
                    <a href="#"><img src="{{ asset('evente-assets/img/brands/l1.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 wow fadeInLeft" data-wow-duration=".9s" data-wow-delay=".8s">
                <div class="td-brands-item mb-25">
                    <a href="#"><img src="{{ asset('evente-assets/img/brands/l2.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 wow fadeInLeft" data-wow-duration=".9s" data-wow-delay=".9s">
                <div class="td-brands-item mb-25">
                    <a href="#"><img src="{{ asset('evente-assets/img/brands/l3.png') }}" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-brands-area-end -->

<!-- td-hero-area -->
<div class="td-hero-area td-hero-5-bg" id="home-one">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="td-hero-wrapper-5 wow td-animetion-left" data-wow-duration="1.5s" data-wow-delay="0.3s">
            </div>
            <div class="col-lg-3 col-md-6 col-6 wow fadeInLeft" data-wow-duration=".9s" data-wow-delay=".8s">
                <div class="td-brands-item mb-25">
                    <a href="#"><img src="{{ asset('evente-assets/img/brands/l2.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 wow fadeInLeft" data-wow-duration=".9s" data-wow-delay=".9s">
                <div class="td-brands-item mb-25">
                    <a href="#"><img src="{{ asset('evente-assets/img/brands/l3.png') }}" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-brands-area-end -->

<!-- td-hero-area -->
<div class="td-hero-area td-hero-5-bg" id="home-one">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="td-hero-wrapper-5 wow td-animetion-left" data-wow-duration="1.5s" data-wow-delay="0.3s">
                    <span class="td-hero-5-shape-2 d-none d-sm-block">
                        <svg width="102" height="92" viewBox="0 0 102 92" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M33.8464 55.1662C38.6797 64.1882 40.9494 73.2707 40.504 79.91C34.7299 76.6027 28.426 69.6815 23.5926 60.6595C18.7593 51.6375 16.4896 42.555 16.935 35.9157C22.709 39.223 29.013 46.1442 33.8464 55.1662ZM93.5632 23.1741C101.645 38.2591 102.56 53.5093 95.6071 57.2341C93.1959 58.5258 90.1523 58.2615 86.8688 56.7345C87.6341 49.5994 85.411 39.8728 80.278 30.2914C75.145 20.7101 68.2818 13.4692 61.9148 10.1551C62.4626 6.57553 63.9257 3.89686 66.3399 2.60351C73.2926 -1.12126 85.4817 8.08914 93.5632 23.1741ZM85.2924 55.9155C79.5183 52.6082 73.2143 45.687 68.381 36.665C63.5476 27.643 61.2779 18.5605 61.7233 11.9213C67.4974 15.2285 73.8013 22.1498 78.6347 31.1718C83.4681 40.1938 85.7378 49.2763 85.2924 55.9155ZM95.2065 22.2938C103.713 38.1729 104.237 54.4608 96.3772 58.6715C93.6385 60.1388 90.2454 59.9461 86.6245 58.4021C85.904 62.272 84.1846 65.2035 81.4459 66.6707C78.7071 68.138 75.314 67.9453 71.6931 66.4013C70.9726 70.2711 69.2533 73.2027 66.5145 74.6699C63.7757 76.1372 60.3827 75.9445 56.7618 74.4005C56.0412 78.2703 54.3219 81.2019 51.5831 82.6691C48.8443 84.1364 45.4513 83.9437 41.8304 82.3997C41.1098 86.2695 39.3905 89.2011 36.6517 90.6683C28.7919 94.8791 15.522 85.4196 7.01509 69.5405C-1.49182 53.6615 -2.00687 37.3689 5.85297 33.1581C8.59175 31.6909 11.9848 31.8836 15.6057 33.4275C16.3263 29.5577 18.0456 26.6262 20.7844 25.1589C23.5231 23.6917 26.9162 23.8844 30.5371 25.4283C31.2577 21.5585 32.977 18.627 35.7157 17.1597C38.4545 15.6925 41.8476 15.8852 45.4685 17.4292C46.1832 13.5624 47.9026 10.6309 50.6413 9.16366C53.801 7.69642 56.7732 7.8891 60.3941 9.43307C61.1117 5.5648 62.831 2.63326 65.5698 1.16602C73.4297 -3.04473 86.6996 6.41469 95.2065 22.2938ZM85.1009 57.6816C78.7367 54.366 71.8707 47.1267 66.7377 37.5453C61.6047 27.964 59.3787 18.2389 60.1469 11.1022C56.8633 9.57523 53.8227 9.30936 51.4085 10.6027C48.9944 11.8961 47.5312 14.5747 46.9834 18.1543C53.3476 21.47 60.2136 28.7092 65.3466 38.2906C70.4796 47.872 72.7056 57.597 71.9374 64.7337C75.221 66.2607 78.2616 66.5266 80.6758 65.2332C83.0899 63.9399 84.5531 61.2612 85.1009 57.6816ZM70.3639 63.9131C64.5898 60.6059 58.2858 53.6846 53.4525 44.6626C48.6191 35.6406 46.3494 26.5581 46.7948 19.9189C52.5689 23.2262 58.8729 30.1474 63.7062 39.1694C68.5396 48.1914 70.8093 57.2739 70.3639 63.9131ZM70.1724 65.6793C63.8083 62.3636 56.9422 55.1243 51.8092 45.543C46.6762 35.9616 44.4502 26.2366 45.2184 19.0999C41.9349 17.5729 38.8942 17.307 36.4801 18.6003C34.0659 19.8937 32.6027 22.5724 32.0549 26.1519C38.4191 29.4676 45.2851 36.7069 50.4181 46.2882C55.5511 55.8696 57.7772 65.5947 57.0089 72.7314C60.2925 74.2584 63.3331 74.5242 65.7473 73.2309C68.1615 71.9375 69.6246 69.2589 70.1724 65.6793ZM55.4354 71.9108C49.6613 68.6035 43.3574 61.6823 38.524 52.6603C33.6906 43.6383 31.4209 34.5558 31.8664 27.9165C37.6404 31.2238 43.9444 38.145 48.7777 47.167C53.6111 56.189 55.8808 65.2715 55.4354 71.9108ZM55.2439 73.6769C48.8798 70.3613 42.0138 63.122 36.8807 53.5406C31.7477 43.9593 29.5217 34.2342 30.2899 27.0975C27.0064 25.5705 23.9657 25.3046 21.5516 26.598C19.1374 27.8913 17.6743 30.57 17.1264 34.1496C23.4906 37.4652 30.3566 44.7045 35.4896 54.2859C40.6226 63.8672 42.8487 73.5923 42.0804 80.729C45.364 82.256 48.4046 82.5219 50.8188 81.2285C53.233 79.9352 54.6961 77.2565 55.2439 73.6769ZM40.3154 81.6746C39.7676 85.2541 38.3045 87.9328 35.8903 89.2262C28.9376 92.9509 16.7485 83.7405 8.66704 68.6555C0.585556 53.5706 -0.329631 38.3204 6.62308 34.5956C9.03436 33.3038 12.0779 33.5681 15.3615 35.0951C14.5961 42.2303 16.8192 51.9569 21.9523 61.5383C27.0853 71.1196 33.9484 78.3604 40.3154 81.6746Z" fill="#5033FF" />
                        </svg>
                    </span>
                    <h2 class="td-hero-2-title td-hero-5-tittle wow td-animetion-left" data-wow-duration="1.5s" data-wow-delay="0.1s">Bulan Inklusi<br> Keuangan  <br> Sulawesi Tenggara <br> 2<img src="{{ asset('evente-assets/img/hero/hero-4/zero.png') }}" alt="">25</h2>
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="td-hero-5-small-thumb wow td-animetion-top" data-wow-duration="1.5s" data-wow-delay="0.1s">
                                <img src="{{ asset('evente-assets/img/hero/hero-5/thumb.png') }}" alt="">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="td-hero-5-content-bottom ml-20 mb-30 wow td-animetion-right" data-wow-duration="1.5s" data-wow-delay="0.2s">
                                <p class="mb-20">program tahunan yang diselenggarakan oleh Otoritas Jasa Keuangan (OJK) <br>
                                    bersama industri jasa keuangan di Indonesia. <br>
                                </p>
                                <div class="td-hero-4-border mb-30"></div>
                                <span class="td-hero-4-dates mb-10 d-inline-block">October 2025 </span>
                                <p class="td-hero-4-para mb-20">Kator OJK Provinsi Sulawesi Tenggara<br>  Kendari, Sulawesi Tenggara</p>
                                <a class="td-btn td-btn-3-squre td-left-right text3 mr-25" href="events.html">
                                    <span class="mr10 td-text d-inline-block mr-5">Chek Event</span>
                                    <span class="td-arrow-angle">
                                        <svg class="td-arrow-svg-top-right" width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.943836 13.5C0.685616 13.5 0.45411 13.4021 0.276027 13.224C0.0979452 13.0459 0 12.8055 0 12.5562C0 12.3068 0.0979452 12.0664 0.276027 11.8884L9.76781 2.38767H2.02123C1.49589 2.38767 1.0774 1.96027 1.0774 1.44384C1.0774 0.927397 1.50479 0.5 2.03014 0.5H12.0562C12.1274 0.5 12.1986 0.508904 12.2788 0.526712L12.4034 0.562329L12.537 0.633562C12.5637 0.65137 12.5993 0.678082 12.626 0.69589C12.6973 0.749315 12.7507 0.80274 12.7952 0.856164C12.8219 0.891781 12.8575 0.927397 12.8842 0.989726L12.9555 1.1411L12.9822 1.22123C13 1.29247 13.0089 1.3726 13.0089 1.44384V11.4699C13.0089 11.9952 12.5815 12.4137 12.0651 12.4137C11.5486 12.4137 11.1212 11.9863 11.1212 11.4699V3.72329L1.62055 13.224C1.44247 13.4021 1.20205 13.5 0.943836 13.5Z" fill="white" />
                                            <path d="M0.943836 13.5C0.685616 13.5 0.45411 13.4021 0.276027 13.224C0.0979452 13.0459 0 12.8055 0 12.5562C0 12.3068 0.0979452 12.0664 0.276027 11.8884L9.76781 2.38767H2.02123C1.49589 2.38767 1.0774 1.96027 1.0774 1.44384C1.0774 0.927397 1.50479 0.5 2.03014 0.5H12.0562C12.1274 0.5 12.1986 0.508904 12.2788 0.526712L12.4034 0.562329L12.537 0.633562C12.5637 0.65137 12.5993 0.678082 12.626 0.69589C12.6973 0.749315 12.7507 0.80274 12.7952 0.856164C12.8219 0.891781 12.8575 0.927397 12.8842 0.989726L12.9555 1.1411L12.9822 1.22123C13 1.29247 13.0089 1.3726 13.0089 1.44384V11.4699C13.0089 11.9952 12.5815 12.4137 12.0651 12.4137C11.5486 12.4137 11.1212 11.9863 11.1212 11.4699V3.72329L1.62055 13.224C1.44247 13.4021 1.20205 13.5 0.943836 13.5Z" fill="white" />
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-9">
                <div class="td-hero-5-thumb-slider">
                    <div class="swiper-container td-hero-5-slider">
                        <div class="swiper-wrapper">
                                <div class="td-hero-5-thumb">
                                    <img src="{{ asset('evente-assets/img/hero/hero-5/thumb-3.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="td-hero-5-thumb">
                                    <img src="{{ asset('evente-assets/img/hero/hero-5/thumb-3.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="td-hero-5-thumb">
                                    <img src="{{ asset('evente-assets/img/hero/hero-5/thumb-3.jpg') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-hero-area-end -->

<!-- td-text-slider-area-start -->
<div class="td-text-slider-area td-text-slider-spacing">
    <div class="container-fluid">
        <div class="row">
           <div class="swiper-container td-text-slider-active">
                  <div class="swiper-wrapper slide-transtion">
                     <div class="swiper-slide">
                        <div class="td-text-slider-item">
                           <h3 class="text">Bulan Inklusi Keuangan 2025</h3>
                           <img src="{{ asset('evente-assets/img/text/round.png') }}" alt="round">
                        </div>
                     </div>
                     <div class="swiper-slide">
                        <div class="td-text-slider-item">
                           <h3 class="text">OJK SULTRA</h3>
                           <img src="{{ asset('evente-assets/img/text/round.png') }}" alt="round">
                        </div>
                     </div>
                     <div class="swiper-slide">
                        <div class="td-text-slider-item">
                           <h3 class="text">BIK SULTRA RUN 2025</h3>
                           <img src="{{ asset('evente-assets/img/text/round.png') }}" alt="round">
                        </div>
                     </div>
                  </div>
               </div>     
           
            </div>
         </div>
</div>
<!-- td-text-slider-area-end -->

<!-- td-about-area-start -->
<div id="about" class="td-about-area pt-140">
    <div class="container-fluid container-1700">
        <div class="row gx-20 align-items-center">
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="td-about-6-thumb mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.3s">
                    <img src="{{ asset('evente-assets/img/about/about-5/thumb.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="td-about-6-thumb mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.4s">
                    <img src="{{ asset('evente-assets/img/about/about-5/thumb-2.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="td-about-6-content text-center mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.5s">
                    <span class="td-section-subtitle td-section-subtitle-2 td-section-subtitle-3 mb-15">Acara Yang akan berlangsung</span>
                    <h2 class="td-about-6-tittle">Berbagai Lomba dan<br> Talkshow</h2>
                    <p class="para">Dalam memperingati bulan inklusi keuangan 2025<br>
                    OJK Sulawesi Tenggara Mengadakan beberapa rangkaian <br> acara yang akan berlangsung.</p>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="td-about-6-thumb mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.6s">
                    <img src="{{ asset('evente-assets/img/about/about-5/thumb-3.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="td-about-6-thumb mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.7s">
                    <img src="{{ asset('evente-assets/img/about/about-5/thumb-4.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-about-area-end -->

<!-- td-counter-area-start -->
<div id="stats" class="td-counter-area pt-70 pb-115 overlay-container">
    <!-- Glass Overlay with Coming Soon -->
    <div class="glass-overlay">
        <div class="coming-soon-content">
          
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="td-counter-2-wrap td-counter-5-wrap mb-30">
                    <span class="td-counter-2-icon mr-20">
                        <i class="flaticon-speaker"></i>
                    </span>
                    <div class="td-counter-2-content">
                        <h2 class="td-counter-2-title count"><span class="odometer" data-count="65"></span>+</h2>
                        <span class="td-counter-2-subtitle">Skilled Speakers</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="td-counter-2-wrap td-counter-5-wrap mb-30">
                    <span class="td-counter-2-icon mr-20">
                        <i class="flaticon-teamwork"></i>
                    </span>
                    <div class="td-counter-2-content">
                        <h2 class="td-counter-2-title count">
                            <span class="odometer" data-count="20"></span>
                            +
                        </h2>
                        <span class="td-counter-2-subtitle">Live Workshop</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="td-counter-2-wrap td-counter-5-wrap mb-30">
                    <span class="td-counter-2-icon mr-20">
                        <i class="flaticon-fastfood"></i>
                    </span>
                    <div class="td-counter-2-content">
                        <h2 class="td-counter-2-title count"><span class="odometer" data-count="26"></span>+</h2>
                        <span class="td-counter-2-subtitle">Snacks & Facilities</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="td-counter-2-wrap td-counter-5-wrap mb-30">
                    <span class="td-counter-2-icon mr-20">
                        <i class="flaticon-deal"></i>
                    </span>
                    <div class="td-counter-2-content">
                        <h2 class="td-counter-2-title count"><span class="odometer" data-count="25"></span>+</h2>
                        <span class="td-counter-2-subtitle">Popular Snacks</span>
                    </div>
                </div>
            </div>

<!-- td-text-slider-area-start -->
<div class="td-text-slider-area td-text-slider-spacing">
    <div class="container-fluid">
        <div class="row">
            <div class="swiper-container td-text-slider-active">
                <div class="swiper-wrapper slide-transtion">
                    <div class="swiper-slide">
                        <div class="td-text-slider-item">
                            <h3 class="text">Bulan Inklusi Keuangan 2025</h3>
                            <img src="{{ asset('evente-assets/img/text/round.png') }}" alt="round">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="td-text-slider-item">
                            <h3 class="text">OJK SULTRA</h3>
                            <img src="{{ asset('evente-assets/img/text/round.png') }}" alt="round">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="td-text-slider-item">
                            <h3 class="text">BIK SULTRA RUN 2025</h3>
                            <img src="{{ asset('evente-assets/img/text/round.png') }}" alt="round">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-text-slider-area-end -->

<!-- td-about-area-start -->
<div id="about" class="td-about-area pt-140">
    <div class="container-fluid container-1700">
        <div class="row gx-20 align-items-center">
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="td-about-6-thumb mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.3s">
                    <img src="{{ asset('evente-assets/img/about/about-5/thumb.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="td-about-6-thumb mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.4s">
                    <img src="{{ asset('evente-assets/img/about/about-5/thumb-2.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="td-about-6-content text-center mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.5s">
                    <span class="td-section-subtitle td-section-subtitle-2 td-section-subtitle-3 mb-15">Acara Yang akan berlangsung</span>
                    <h2 class="td-about-6-tittle">Berbagai Lomba dan<br> Talkshow</h2>
                    <p class="para">
                        Dalam memperingati bulan inklusi keuangan 2025<br>
                        OJK Sulawesi Tenggara mengadakan beberapa rangkaian <br> acara yang akan berlangsung.
                    </p>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="td-about-6-thumb mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.6s">
                    <img src="{{ asset('evente-assets/img/about/about-5/thumb-3.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="td-about-6-thumb mb-30 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.7s">
                    <img src="{{ asset('evente-assets/img/about/about-5/thumb-4.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-about-area-end -->

<!-- td-counter-area-start -->
<div id="stats" class="td-counter-area pt-70 pb-115">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="td-counter-2-wrap td-counter-5-wrap mb-30">
                    <span class="td-counter-2-icon mr-20">
                        <i class="flaticon-speaker"></i>
                    </span>
                    <div class="td-counter-2-content">
                        <h2 class="td-counter-2-title count"><span class="odometer" data-count="15"></span>+</h2>
                        <span class="td-counter-2-subtitle">Narasumber Ahli</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="td-counter-2-wrap td-counter-5-wrap mb-30">
                    <span class="td-counter-2-icon mr-20">
                        <i class="flaticon-teamwork"></i>
                    </span>
                    <div class="td-counter-2-content">
                        <h2 class="td-counter-2-title count">
                            <span class="odometer" data-count="5"></span>
                            +
                        </h2>
                        <span class="td-counter-2-subtitle">Kategori Lomba</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="td-counter-2-wrap td-counter-5-wrap mb-30">
                    <span class="td-counter-2-icon mr-20">
                        <i class="flaticon-fastfood"></i>
                    </span>
                    <div class="td-counter-2-content">
                        <h2 class="td-counter-2-title count"><span class="odometer" data-count="1000"></span>+</h2>
                        <span class="td-counter-2-subtitle">Peserta Diharapkan</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="td-counter-2-wrap td-counter-5-wrap mb-30">
                    <span class="td-counter-2-icon mr-20">
                        <i class="flaticon-deal"></i>
                    </span>
                    <div class="td-counter-2-content">
                        <h2 class="td-counter-2-title count"><span class="odometer" data-count="50"></span>+</h2>
                        <span class="td-counter-2-subtitle">Hadiah Menarik</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-counter-area-end -->

<!-- td-schedule-area-start -->
<div id="schedule" class="td-schedule-area grey-bg-4 pt-130 p-relative z-index-1 fix pb-130">
    <div class="td-hero-4-blur td-team-5-blur"></div>
    <div class="td-hero-4-blur td-hero-4-blur-2 td-team-5-blur-2"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-7 col-xl-9 col-lg-10">
                <div class="td-schedule-4-title-wrap text-center mb-30  wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.3s">
                    <span class="td-section-subtitle td-section-subtitle-2 mb-20">Jadwal Acara</span>
                    <h2 class="td-section-title mb-10">Rencana Jadwal Acara Kami</h2>
                    <p class="td-section-text">Seperti tahun sebelumnya, tahun ini kami menyelenggarakan <br>
                        beberapa acara dalam rangka bulan inklusi keuangan 2025.</p>
                </div>
                <div class="nav td-schedule-5-tab-btn justify-content-center mb-30  wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.5s" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active p-relative mb-10" id="v-pills-home1-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home1" type="button" role="tab" aria-controls="v-pills-home1" aria-selected="false">
                        <span class="td-counts">Day-01</span>
                        <span class="d-block"> Oct, 2025</span>
                    </button>
                    <button class="nav-link p-relative mb-10 disabled coming-soon-tab" disabled style="background-color: #888; color: #ddd; cursor: not-allowed; opacity: 0.6;">
                        <span class="td-counts">Day-02</span>
                        <span class="d-block">Coming Soon</span>
                    </button>
                    <button class="nav-link p-relative mb-10 disabled coming-soon-tab" disabled style="background-color: #888; color: #ddd; cursor: not-allowed; opacity: 0.6;">
                        <span class="td-counts">Day-03</span>
                        <span class="d-block">Coming Soon</span>
                    </button>
                 </div>
            </div>
        </div>
        <div class="row">
            <div class="tp-contact-form-wrapper tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade active show" id="v-pills-home1" role="tabpanel" aria-labelledby="v-pills-home1-tab">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="td-schedule-5-wrap">
                                <div class="row gx-30 align-items-center">
                                    <div class="col-xl-4 col-lg-5">
                                        <div class="td-schedule-5-left-content d-flex align-items-center">
                                            <div class="td-schedule-5-thumb mb-20 mr-30">
                                                <a href="#"><img src="{{ asset('evente-assets/img/schedule/schedule-6/e-1.png') }}" alt=""></a>
                                            </div>
                                            <div class="td-schedule-5-date-wrap mb-20 w-100">
                                                <div class="td-schedule-5-date d-flex mb-15">
                                                    <h4 class="mb-0 mr-10">25</h4>
                                                    <span>Oktober,<br> 2025</span>
                                                </div>
                                                <div class="td-schedule-5-left-border mb-15"></div>
                                                <div class="td-schedule-5-name">
                                                    <h4 class="mb-0">Event Lari</h4>
                                                    <span>BIK Night Run</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-5 col-lg-7">
                                        <div class="td-schedule-4-content td-schedule-5-content mb-30">
                                            <h2 class="td-schedule-4-title"><a href="#">BIK NIGHT RUN 2025</a></h2>
                                            <p>Dalam rangka memperingati Bulan Inklusi Keuangan (BIK) 2025, Otoritas Jasa Keuangan (OJK) bersama Industri Jasa Keuangan menyelenggarakan BIK Night Run 2025 .</p>
                                            <div class="td-schedule-4-destination mb-10">
                                                <span>
                                                    <i class="flaticon-gps"></i>
                                                    Pelataran Parkir MTQ
                                                </span>
                                                <span>
                                                    <i class="flaticon-time"></i>
                                                    19:00 - Selesai
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-12">
                                        <div class="td-schedule-5-btn text-xl-end">
                                            <a class="td-btn td-btn-green td-btn-3-squre td-left-right text" href="nighrun">
                                                <span class="td-text d-inline-block mr-5">Daftar Sekarang</span>
                                                <span class="td-arrow-angle">
                                                    <svg class="td-arrow-svg-top-right" width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M0.943836 13.5C0.685616 13.5 0.45411 13.4021 0.276027 13.224C0.0979452 13.0459 0 12.8055 0 12.5562C0 12.3068 0.0979452 12.0664 0.276027 11.8884L9.76781 2.38767H2.02123C1.49589 2.38767 1.0774 1.96027 1.0774 1.44384C1.0774 0.927397 1.50479 0.5 2.03014 0.5H12.0562C12.1274 0.5 12.1986 0.508904 12.2788 0.526712L12.4034 0.562329L12.537 0.633562C12.5637 0.65137 12.5993 0.678082 12.626 0.69589C12.6973 0.749315 12.7507 0.80274 12.7952 0.856164C12.8219 0.891781 12.8575 0.927397 12.8842 0.989726L12.9555 1.1411L12.9822 1.22123C13 1.29247 13.0089 1.3726 13.0089 1.44384V11.4699C13.0089 11.9952 12.5815 12.4137 12.0651 12.4137C11.5486 12.4137 11.1212 11.9863 11.1212 11.4699V3.72329L1.62055 13.224C1.44247 13.4021 1.20205 13.5 0.943836 13.5Z" fill="currentColor" />
                                                        <path d="M0.943836 13.5C0.685616 13.5 0.45411 13.4021 0.276027 13.224C0.0979452 13.0459 0 12.8055 0 12.5562C0 12.3068 0.0979452 12.0664 0.276027 11.8884L9.76781 2.38767H2.02123C1.49589 2.38767 1.0774 1.96027 1.0774 1.44384C1.0774 0.927397 1.50479 0.5 2.03014 0.5H12.0562C12.1274 0.5 12.1986 0.508904 12.2788 0.526712L12.4034 0.562329L12.537 0.633562C12.5637 0.65137 12.5993 0.678082 12.626 0.69589C12.6973 0.749315 12.7507 0.80274 12.7952 0.856164C12.8219 0.891781 12.8575 0.927397 12.8842 0.989726L12.9555 1.1411L12.9822 1.22123C13 1.29247 13.0089 1.3726 13.0089 1.44384V11.4699C13.0089 11.9952 12.5815 12.4137 12.0651 12.4137C11.5486 12.4137 11.1212 11.9863 11.1212 11.4699V3.72329L1.62055 13.224C1.44247 13.4021 1.20205 13.5 0.943836 13.5Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- batas -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-schedule-area-end -->

<!-- td-schedule-area-start -->
<div id="schedule" class="td-schedule-area pt-135 pb-110">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="td-section-wrapper-2 text-center mb-70">
                    <span class="td-section-subtitle td-section-subtitle-2 mb-15 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.3s">
                        Agenda Event
                    </span>
                    <h2 class="td-section-title wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.5s">
                        Jadwal Kegiatan<br> BIK SULTRA 2025
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="td-schedule-tab-wrapper">
                    <nav>
                        <div class="nav nav-tabs td-schedule-nav-tab justify-content-center" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-day-1-tab" data-bs-toggle="tab" data-bs-target="#nav-day-1" type="button" role="tab" aria-controls="nav-day-1" aria-selected="true">
                                <span class="td-counts">01</span>
                                <span class="d-block">Oktober</span>
                            </button>
                            <button class="nav-link" id="nav-day-2-tab" data-bs-toggle="tab" data-bs-target="#nav-day-2" type="button" role="tab" aria-controls="nav-day-2" aria-selected="false">
                                <span class="td-counts">02</span>
                                <span class="d-block">Oktober</span>
                            </button>
                            <button class="nav-link" id="nav-day-3-tab" data-bs-toggle="tab" data-bs-target="#nav-day-3" type="button" role="tab" aria-controls="nav-day-3" aria-selected="false">
                                <span class="td-counts">03</span>
                                <span class="d-block">Oktober</span>
                            </button>
                        </div>
                    </nav>
                    <div class="tab-content td-schedule-tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-day-1" role="tabpanel" aria-labelledby="nav-day-1-tab">
                            <div class="td-schedule-item-wrapper">
                                <div class="td-schedule-item">
                                    <div class="td-schedule-meta">
                                        <span class="td-schedule-time">09:00 - 12:00</span>
                                    </div>
                                    <div class="td-schedule-content">
                                        <h4 class="td-schedule-title">
                                            <a href="#">Pembukaan & Talkshow Literasi Keuangan</a>
                                        </h4>
                                        <p>Acara pembukaan BIK SULTRA 2025 dan talkshow tentang literasi keuangan untuk masyarakat Sulawesi Tenggara.</p>
                                        <div class="td-schedule-speaker">
                                            <span class="td-schedule-speaker-title">Narasumber:</span>
                                            <span>Tim OJK Sulawesi Tenggara</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="td-schedule-item">
                                    <div class="td-schedule-meta">
                                        <span class="td-schedule-time">13:00 - 17:00</span>
                                    </div>
                                    <div class="td-schedule-content">
                                        <h4 class="td-schedule-title">
                                            <a href="#">Lomba Literasi Keuangan</a>
                                        </h4>
                                        <p>Kompetisi pengetahuan keuangan untuk meningkatkan pemahaman masyarakat tentang produk dan layanan jasa keuangan.</p>
                                        <div class="td-schedule-speaker">
                                            <span class="td-schedule-speaker-title">Kategori:</span>
                                            <span>Umum, Pelajar, Mahasiswa</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-day-2" role="tabpanel" aria-labelledby="nav-day-2-tab">
                            <div class="td-schedule-item-wrapper">
                                <div class="td-schedule-item">
                                    <div class="td-schedule-meta">
                                        <span class="td-schedule-time">08:00 - 12:00</span>
                                    </div>
                                    <div class="td-schedule-content">
                                        <h4 class="td-schedule-title">
                                            <a href="#">Workshop Investasi & Asuransi</a>
                                        </h4>
                                        <p>Workshop praktis tentang cara berinvestasi yang aman dan memahami produk asuransi.</p>
                                        <div class="td-schedule-speaker">
                                            <span class="td-schedule-speaker-title">Narasumber:</span>
                                            <span>Praktisi Keuangan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="td-schedule-item">
                                    <div class="td-schedule-meta">
                                        <span class="td-schedule-time">14:00 - 17:00</span>
                                    </div>
                                    <div class="td-schedule-content">
                                        <h4 class="td-schedule-title">
                                            <a href="#">BIK SULTRA RUN 2025</a>
                                        </h4>
                                        <p>Event lari yang menggabungkan olahraga dengan edukasi keuangan. Tersedia kategori 5K untuk semua peserta.</p>
                                        <div class="td-schedule-speaker">
                                            <span class="td-schedule-speaker-title">Kategori:</span>
                                            <span>5K Fun Run</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-day-3" role="tabpanel" aria-labelledby="nav-day-3-tab">
                            <div class="td-schedule-item-wrapper">
                                <div class="td-schedule-item">
                                    <div class="td-schedule-meta">
                                        <span class="td-schedule-time">09:00 - 12:00</span>
                                    </div>
                                    <div class="td-schedule-content">
                                        <h4 class="td-schedule-title">
                                            <a href="#">Pameran Produk Jasa Keuangan</a>
                                        </h4>
                                        <p>Pameran berbagai produk dan layanan jasa keuangan dari bank, asuransi, dan lembaga keuangan lainnya.</p>
                                        <div class="td-schedule-speaker">
                                            <span class="td-schedule-speaker-title">Peserta:</span>
                                            <span>Lembaga Jasa Keuangan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="td-schedule-item">
                                    <div class="td-schedule-meta">
                                        <span class="td-schedule-time">13:00 - 16:00</span>
                                    </div>
                                    <div class="td-schedule-content">
                                        <h4 class="td-schedule-title">
                                            <a href="#">Penutupan & Pengumuman Pemenang</a>
                                        </h4>
                                        <p>Acara penutupan BIK SULTRA 2025 dan pengumuman pemenang berbagai kategori lomba.</p>
                                        <div class="td-schedule-speaker">
                                            <span class="td-schedule-speaker-title">Penyelenggara:</span>
                                            <span>OJK Sulawesi Tenggara</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-schedule-area-end -->

<!-- td-brands-area-start -->
<div id="partners" class="td-brands-area pt-80 pb-85">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="td-section-wrapper-2 text-center mb-70">
                    <span class="td-section-subtitle td-section-subtitle-2 mb-15 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.3s">
                        Partner Resmi
                    </span>
                    <h2 class="td-section-title wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.5s">
                        Didukung Oleh<br> Lembaga Terpercaya
                    </h2>
                </div>
            </div>
        </div>
        <div class="td-brands-wrapper">
            <div class="swiper-container td-brands-active">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="td-brands-item">
                            <img src="{{ asset('evente-assets/img/brands/brands.png') }}" alt="OJK">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="td-brands-item">
                            <img src="{{ asset('evente-assets/img/brands/brands-2.png') }}" alt="Bank Indonesia">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="td-brands-item">
                            <img src="{{ asset('evente-assets/img/brands/brands-3.png') }}" alt="Partner 3">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="td-brands-item">
                            <img src="{{ asset('evente-assets/img/brands/brands-4.png') }}" alt="Partner 4">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="td-brands-item">
                            <img src="{{ asset('evente-assets/img/brands/brands-5.png') }}" alt="Partner 5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- td-brands-area-end -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper for hero slider
    var heroSlider = new Swiper('.td-hero-5-slider', {
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        }
    });

    // Initialize Swiper for text slider
    var textSlider = new Swiper('.td-text-slider-active', {
        loop: true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        speed: 1000,
        spaceBetween: 0,
        slidesPerView: 'auto',
    });

    // Initialize Swiper for brands
    var brandsSlider = new Swiper('.td-brands-active', {
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        spaceBetween: 30,
        slidesPerView: 5,
        breakpoints: {
            320: {
                slidesPerView: 2,
            },
            576: {
                slidesPerView: 3,
            },
            768: {
                slidesPerView: 4,
            },
            992: {
                slidesPerView: 5,
            }
        }
    });
});
</script>
@endpush
