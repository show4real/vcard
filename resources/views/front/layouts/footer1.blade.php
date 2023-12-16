    <!-- start footer section -->
    <div class="curve-shape">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 4000 275">
            <path fill="#f3f3ff" d="M4000,125.3V275H0V109.9C1907.2,615.4,2670.5-323.1,4000,125.3z"></path>
        </svg>
    </div>
    <footer class="bg-light ">
        <div class="container">
            <div class="row align-items-center flex-lg-row flex-column-reverse pt-50 pb-40">
                <div class="col-lg-6">
                    <div class="text-lg-start text-center pe-xxl-5 me-xxl-5">
                        <h3 class="fs-30 mb-20">{{ __('messages.Subscribe_Our_Newsletter') }}</h3>
                        <p class="text-gray-100 fs-18 mb-40 pb-lg-3 pe-xl-5 me-xl-5">
                            {{ __('messages.Receive_latest_news_update_and_many_other_things_every_week') }}</p>
                    </div>
                    <form action="{{ route('email.sub') }}" method="post" id="addEmail">
                        @csrf()
                        <div class="email">
                            <input type="email" name="email" class="form-control"
                                placeholder="{{ __('messages.front.enter_your_email') }}" required>
                            <div class=" subscribe-btn text-sm-end text-center mt-sm-0 mt-4">
                                <button type="submit"
                                    class="btn btn-primary h-100 subscribeBtn">{{ __('messages.subscribe') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 text-lg-end text-center mb-lg-0 mb-40">
                    <div class="footer-img ">
                        <img src="{{ asset('assets/img/new_home_page/footer-img.png') }}"
                            class="zoom-in-zoom-out img-fluid w-auto h-100" alt="img">
                    </div>

                </div>
            </div>
            <div class="row align-items-center pb-md-4 pb-3">
                <div class="col-md-7 text-md-start text-center mb-md-0 mb-2">
                    <p class="text-black fw-light mb-0">
                        © {{ \Carbon\Carbon::now()->year }} {{ __('auth.copyright_by') . ' ' }}<span
                            class="fw-6">{{ $setting['app_name'] }}</span>
                    </p>
                </div>
                <div class="col-md-5 text-md-end">
                    <div class="d-flex justify-content-md-end justify-content-center">
                        <a href="{{ route('terms.conditions') }}"
                            class="text-black text-decoration-none me-4">{!! __('messages.vcard.term_condition') !!}</a>
                        <a href="{{ route('privacy.policy') }}"
                            class="text-black text-decoration-none">{{ __('messages.vcard.privacy_policy') }}</a>
                    </div>
                </div>
            </div>
        </div>


        </div>
    </footer>
    <!-- end footer section -->
