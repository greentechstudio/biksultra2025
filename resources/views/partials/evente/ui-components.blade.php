<!-- Preloader Start -->
<div class="preloader">
    <div class="loader"></div>
</div>
<!-- Preloader End -->

<!-- Scroll-top -->
<button class="scroll__top scroll-to-target" data-target="html">
    <i class="fa-sharp fa-regular fa-arrow-up"></i>
</button>
<!-- Scroll-top-end-->

<!-- header-search -->
<div class="search__popup">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="search__wrapper">
                    <div class="search__close">
                        <button type="button" class="search-close-btn">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 1L1 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M1 1L17 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="search__form">
                        <form action="#">
                            <div class="search__input">
                                <input class="search-input-field" type="text" placeholder="Type keywords here">
                                <span class="search-focus-border"></span>
                                <button type="submit">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.55 18.1C14.272 18.1 18.1 14.272 18.1 9.55C18.1 4.82797 14.272 1 9.55 1C4.82797 1 1 4.82797 1 9.55C1 14.272 4.82797 18.1 9.55 18.1Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M19.0002 19.0002L17.2002 17.2002" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="search-popup-overlay"></div>
<!-- header-search-end -->

<!-- cart mini area start -->
<div class="cartmini__area">
    <div class="cartmini__wrapper d-flex justify-content-between flex-column">
        <div class="cartmini__top-wrapper ">
            <div class="cartmini__top p-relative">
                <div class="cartmini__title">
                    <h4>Your cart</h4>
                </div>
                <div class="cartmini__close">
                    <button type="button" class="cartmini__close-btn cartmini-close-btn"><i class="fal fa-times"></i></button>
                </div>
            </div>
            <div class="cartmini__widget">
                <div class="cartmini__widget-item">
                    <div class="cartmini__thumb">
                        <a href="#">
                            <img src="{{ asset('evente-assets/img/shop/sm-product-1.jpg') }}" alt="">
                        </a>
                    </div>
                    <div class="cartmini__content">
                        <h5><a href="#">Colmi Smart Wathc</a></h5>
                        <div class="cartmini__price-wrapper">
                            <span class="cartmini__price">$36.00</span>
                        </div>
                    </div>
                    <button class="cartmini__del"><i class="fal fa-times"></i></button>
                </div>
                <div class="cartmini__widget-item">
                    <div class="cartmini__thumb">
                        <a href="#">
                            <img src="{{ asset('evente-assets/img/shop/sm-product-2.jpg') }}" alt="">
                        </a>
                    </div>
                    <div class="cartmini__content">
                        <h5><a href="#">Apple Air Pods</a></h5>
                        <div class="cartmini__price-wrapper">
                            <span class="cartmini__price">$28.00</span>
                        </div>
                    </div>
                    <button class="cartmini__del"><i class="fal fa-times"></i></button>
                </div>
            </div>
            <!-- for wp -->
        </div>
        <div class="cartmini__checkout">
            <div class="cartmini__checkout-title mb-30">
                <h4>Subtotal:</h4>
                <span>$113.00</span>
            </div>
            <div class="cartmini__checkout-btn">
                <a href="#" class="cartmini-btn mb-10 w-100"> <span></span> view cart</a>
                <a href="#" class="cartmini-btn-border w-100"> <span></span> checkout</a>
            </div>
        </div>
    </div>
</div>
<div class="body-overlay"></div>
<!-- cart mini area end -->