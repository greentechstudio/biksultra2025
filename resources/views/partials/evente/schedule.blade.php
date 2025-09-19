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
                        {{ config('event.schedule.description') }}</p>
                </div>
                <div class="nav td-schedule-5-tab-btn justify-content-center mb-30  wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.5s" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active p-relative mb-10" id="v-pills-home1-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home1" type="button" role="tab" aria-controls="v-pills-home1" aria-selected="false">
                        <span class="td-counts">Day-01</span>
                        <span class="d-block"> {{ substr(config('event.date.month'), 0, 3) }}, {{ config('event.year') }}</span>
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
                                            <h2 class="td-schedule-4-title"><a href="#">{{ strtoupper(config('event.short_name')) }} {{ config('event.year') }}</a></h2>
                                            <p>{{ config('event.schedule.main_event_description') }}</p>
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
                                            <a class="td-btn td-btn-green td-btn-3-squre td-left-right text" href="nightrun">
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
                <div class="tab-pane fade" id="v-pills-health" role="tabpanel" aria-labelledby="v-pills-health-tab">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="td-schedule-5-wrap">
                                <div class="row gx-30 align-items-center">
                                    <div class="col-xl-4 col-lg-5">
                                        <div class="td-schedule-5-left-content d-flex align-items-center">
                                            <div class="td-schedule-5-thumb mb-20 mr-30">
                                                <a href="events-details.html"><img src="{{ asset('evente-assets/img/schedule/schedule-6/bg.png') }}" alt=""></a>
                                            </div>
                                            <div class="td-schedule-5-date-wrap mb-20 w-100">
                                                <div class="td-schedule-5-date d-flex mb-15">
                                                    <h4 class="mb-0 mr-10">19</h4>
                                                    <span>October,<br> 2025</span>
                                                </div>
                                                <div class="td-schedule-5-left-border mb-15"></div>
                                                <div class="td-schedule-5-name">
                                                    <h4 class="mb-0">Jerome Bell</h4>
                                                    <span>Sr. Designer</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-5 col-lg-7">
                                        <div class="td-schedule-4-content td-schedule-5-content mb-30">
                                            <h2 class="td-schedule-4-title"><a href="#">Fashion forum: trends in 2025</a></h2>
                                            <p>Like previous year this year we are arranging world marketing
                                                summit 2024. Its the gathering of all the big</p>
                                            <div class="td-schedule-4-destination mb-10">
                                                <span>
                                                    <i class="flaticon-gps"></i>
                                                    54 Street, Newyork City
                                                </span>
                                                <span>
                                                    <i class="flaticon-time"></i>
                                                    9.00am - 10.00 am
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-12">
                                        <div class="td-schedule-5-btn text-xl-end">
                                            <a class="td-btn td-btn-green td-btn-3-squre td-left-right text" href="events-details.html">
                                                <span class="td-text d-inline-block mr-5">Buy Ticket</span>
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
                <div class="tab-pane fade" id="v-pills-life" role="tabpanel" aria-labelledby="v-pills-life-tab">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="td-schedule-5-wrap">
                                <div class="row gx-30 align-items-center">
                                    <div class="col-xl-4 col-lg-5">
                                        <div class="td-schedule-5-left-content d-flex align-items-center">
                                            <div class="td-schedule-5-thumb mb-20 mr-30">
                                                <a href="events-details.html"><img src="{{ asset('evente-assets/img/schedule/schedule-6/bg.png') }}" alt=""></a>
                                            </div>
                                            <div class="td-schedule-5-date-wrap mb-20 w-100">
                                                <div class="td-schedule-5-date d-flex mb-15">
                                                    <h4 class="mb-0 mr-10">19</h4>
                                                    <span>October,<br> 2025</span>
                                                </div>
                                                <div class="td-schedule-5-left-border mb-15"></div>
                                                <div class="td-schedule-5-name">
                                                    <h4 class="mb-0">Jerome Bell</h4>
                                                    <span>Sr. Designer</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-5 col-lg-7">
                                        <div class="td-schedule-4-content td-schedule-5-content mb-30">
                                            <h2 class="td-schedule-4-title"><a href="events-details.html">Fashion forum: trends in 2025</a></h2>
                                            <p>Like previous year this year we are arranging world marketing
                                                summit 2024. Its the gathering of all the big</p>
                                            <div class="td-schedule-4-destination mb-10">
                                                <span>
                                                    <i class="flaticon-gps"></i>
                                                    54 Street, Newyork City
                                                </span>
                                                <span>
                                                    <i class="flaticon-time"></i>
                                                    9.00am - 10.00 am
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-12">
                                        <div class="td-schedule-5-btn text-xl-end">
                                            <a class="td-btn td-btn-green td-btn-3-squre td-left-right text" href="events-details.html">
                                                <span class="td-text d-inline-block mr-5">Buy Ticket</span>
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