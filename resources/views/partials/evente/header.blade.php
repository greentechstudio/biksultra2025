<!-- header-area -->
<header>
    <div id="header-sticky" class="td-header__area z-index-999 td-transparent  header-mobile-spacing">
        <div class="container-fluid container-1680">
            <div class="row align-items-center">
                <div class="col-xxl-9 col-xl-10 col-lg-10 col-6">
                    <div class="tdmenu__wrap tdmenu-2 d-flex align-items-center">
                        <div class="logo">
                            <a class="logo-1" href="{{ url('/') }}"><img src="{{ asset('evente-assets/img/logo/logo-black.png') }}" alt="Logo"></a>
                        </div>
                        <nav class="tdmenu__nav ml-90 mr-50 d-none d-xl-flex">
                            <div class="tdmenu__navbar-wrap tdmenu__main-menu">
                                <ul class="navigation">
                                    <li class="active"><a href="#home" class="section-scroll">Beranda</a></li>
                                    <li><a href="#about" class="section-scroll">Tentang</a></li>
                                    <li><a href="#schedule" class="section-scroll">Jadwal</a></li>
                                    <li><a href="#partners" class="section-scroll">Partner</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Mobile Menu  -->
    <div class="tdmobile__menu">
        <nav class="tdmobile__menu-box">
            <div class="close-btn"><i class="fa-solid fa-xmark"></i></div>
            <div class="nav-logo">
                <a href="{{ url('/') }}"><img src="{{ asset('evente-assets/img/logo/logo-black.png') }}" alt="logo"></a>
            </div>
            <div class="tdmobile__search">
                <form action="#">
                    <input type="text" placeholder="Search here...">
                    <button><i class="fas fa-search"></i></button>
                </form>
            </div>
            <!-- Mobile Section Navigation -->
            <div class="mobile-section-nav mt-20">
                <ul class="mobile-section-navigation">
                    <li><a href="#home" class="section-scroll mobile-section-link">🏠 Home</a></li>
                    <li><a href="#about" class="section-scroll mobile-section-link">ℹ️ About</a></li>
                    <li><a href="#stats" class="section-scroll mobile-section-link">📊 Stats</a></li>
                    <li><a href="#schedule" class="section-scroll mobile-section-link">📅 Schedule</a></li>
                    <li><a href="#categories" class="section-scroll mobile-section-link">📂 Categories</a></li>
                    <li><a href="#partners" class="section-scroll mobile-section-link">🤝 Partners</a></li>
                </ul>
            </div>
            <div class="tdmobile__menu-outer">
                <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
            </div>
            <div class="mt-30 ml-25 mr-25">
                <a class="td-btn td-left-right w-100 text-center" href="#schedule">
                    <span class="mr10 td-text d-inline-block mr-5">Lihat Jadwal</span>
                    <span class="td-arrow-angle">
                        <svg class="td-arrow-svg-top-right" width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.943836 13.5C0.685616 13.5 0.45411 13.4021 0.276027 13.224C0.0979452 13.0459 0 12.8055 0 12.5562C0 12.3068 0.0979452 12.0664 0.276027 11.8884L9.76781 2.38767H2.02123C1.49589 2.38767 1.0774 1.96027 1.0774 1.44384C1.0774 0.927397 1.50479 0.5 2.03014 0.5H12.0562C12.1274 0.5 12.1986 0.508904 12.2788 0.526712L12.4034 0.562329L12.537 0.633562C12.5637 0.65137 12.5993 0.678082 12.626 0.69589C12.6973 0.749315 12.7507 0.80274 12.7952 0.856164C12.8219 0.891781 12.8575 0.927397 12.8842 0.989726L12.9555 1.1411L12.9822 1.22123C13 1.29247 13.0089 1.3726 13.0089 1.44384V11.4699C13.0089 11.9952 12.5815 12.4137 12.0651 12.4137C11.5486 12.4137 11.1212 11.9863 11.1212 11.4699V3.72329L1.62055 13.224C1.44247 13.4021 1.20205 13.5 0.943836 13.5Z" fill="white" />
                            <path d="M0.943836 13.5C0.685616 13.5 0.45411 13.4021 0.276027 13.224C0.0979452 13.0459 0 12.8055 0 12.5562C0 12.3068 0.0979452 12.0664 0.276027 11.8884L9.76781 2.38767H2.02123C1.49589 2.38767 1.0774 1.96027 1.0774 1.44384C1.0774 0.927397 1.50479 0.5 2.03014 0.5H12.0562C12.1274 0.5 12.1986 0.508904 12.2788 0.526712L12.4034 0.562329L12.537 0.633562C12.5637 0.65137 12.5993 0.678082 12.626 0.69589C12.6973 0.749315 12.7507 0.80274 12.7952 0.856164C12.8219 0.891781 12.8575 0.927397 12.8842 0.989726L12.9555 1.1411L12.9822 1.22123C13 1.29247 13.0089 1.3726 13.0089 1.44384V11.4699C13.0089 11.9952 12.5815 12.4137 12.0651 12.4137C11.5486 12.4137 11.1212 11.9863 11.1212 11.4699V3.72329L1.62055 13.224C1.44247 13.4021 1.20205 13.5 0.943836 13.5Z" fill="white" />
                        </svg>
                    </span>
                </a>
            </div>
            <div class="social-links">
                <ul class="list-wrap">
                    <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                    <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="tdmobile__menu-backdrop"></div>
    <!-- End Mobile Menu -->

</header>
<!-- header-area-end -->