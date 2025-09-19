<!-- td-text-slider-area-start -->
<div class="td-text-slider-area td-text-slider-spacing">
    <div class="container-fluid">
        <div class="row">
           <div class="swiper-container td-text-slider-active">
                  <div class="swiper-wrapper slide-transtion">
                     <div class="swiper-slide">
                        <div class="td-text-slider-item">
                           <h3 class="text">{{ config('event.full_name') }} {{ config('event.year') }}</h3>
                           <img src="{{ asset('evente-assets/img/text/round.png') }}" alt="round">
                        </div>
                     </div>
                     <div class="swiper-slide">
                        <div class="td-text-slider-item">
                           <h3 class="text">{{ config('event.organizer.region') }}</h3>
                           <img src="{{ asset('evente-assets/img/text/round.png') }}" alt="round">
                        </div>
                     </div>
                     <div class="swiper-slide">
                        <div class="td-text-slider-item">
                           <h3 class="text">{{ config('event.short_name') }} {{ config('event.year') }}</h3>
                           <img src="{{ asset('evente-assets/img/text/round.png') }}" alt="round">
                        </div>
                     </div>
                  </div>
           </div>     
           
        </div>
     </div>
</div>
<!-- td-text-slider-area-end -->