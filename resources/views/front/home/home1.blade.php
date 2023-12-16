@extends('front.layouts.app1')
@section('title')
    {{ getAppName() }}
@endsection
@section('content')
    <!-- start hero section -->
    <section class="hero-section position-relative pt-60 pb-60">
        <div class="container"> @include('flash::message') </div>
        <div class="hero-bg-img text-end">
            <img src="{{ asset('assets/img/new_home_page/hero-bg.png') }}" class="w-100 h-100" alt="hero-img" />
        </div>
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6 text-lg-start text-center mb-lg-0 mb-md-5 mb-4">
                    <div class="hero-content">
                        <h1 class="text-black mb-2">{{ $setting['home_page_title'] }}</h1>
                        <p class="text-gray-100 fs-18 mb-40 ">
                            {{ $setting['sub_text'] ?? '' }}
                        </p>
                        <a href="{{ route('register') }}" class="btn btn-primary" role="button">
                            {{ __('auth.get_started') }}</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-lg-0 mt-4">
                    <div class="hero-img mx-auto">
                        <img src="{{ isset($setting['home_page_banner']) ? $setting['home_page_banner'] : asset('assets/img/new_front/hero-img.png') }}"
                            alt="Vcard" class="zoom-in-zoom-out w-100 h-auto" />
                    </div>

                </div>
            </div>
        </div>
        <div class="main-banner banner-img-1">
            <img src="{{ asset('assets/img/new_home_page/shape-1.png') }}" class="w-100 h-auto" alt="image">
        </div>
        <div class="main-banner banner-img-2">
            <img src="{{ asset('assets/img/new_home_page/shape-2.png') }}" class="w-100 h-auto" alt="image">
        </div>
        <div class="main-banner banner-img-3">
            <img src="{{ asset('assets/img/new_home_page/shape-3.png') }}" class="w-100 h-auto" alt="image">
        </div>
        <div class="main-banner banner-img-4">
            <img src="{{ asset('assets/img/new_home_page/shape-4.png') }}" class="w-100 h-auto" alt="image">
        </div>
        <div class="main-banner banner-img-5">
            <img src="{{ asset('assets/img/new_home_page/shape-5.png') }}" class="w-100 h-auto" alt="image">
        </div>
        <div class="main-banner banner-img-6">
            <img src="{{ asset('assets/img/new_home_page/shape-6.png') }}" class="w-100 h-auto" alt="image">
        </div>
        <div class="main-banner banner-img-7">
            <img src="{{ asset('assets/img/new_home_page/shape-7.png') }}" class="w-100 h-auto" alt="image">
        </div>
        <div class="main-banner banner-img-8">
            <img src="{{ asset('assets/img/new_home_page/shape-8.png') }}" class="w-100 h-auto" alt="image">
        </div>
    </section>
    <!-- end hero section -->
    <div class="vcard-template-section pt-60 pb-100 position-relative">
        <div class="vcard-bg position-absolute">
            <img src="{{ asset('assets/img/new_home_page/vcard-template-bg.png') }}" alt="vcard-bg" class="w-100 h-auto">
        </div>
        <div class="plus-vector1 position-absolute">
            <img src="{{ asset('assets/img/new_home_page/plus-vector.png') }}" alt="vector" class="w-100 h-auto">
        </div>
        <div class="plus-vector2 position-absolute">
            <img src="{{ asset('assets/img/new_home_page/plus-vector.png') }}" alt="vector" class="w-100 h-auto">
        </div>
        <div class="plus-vector3 position-absolute">
            <img src="{{ asset('assets/img/new_home_page/plus-vector2.png') }}" alt="vector" class="w-100 h-auto">
        </div>
        <div class="container">
            <div class="section-heading text-center mb-60">
                <h2 class="d-inline-block"> {{ __('messages.vcards_templates') }} </h2>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-6 mb-60">
                    <div class="template-card h-100">
                        <div class="card-img">
                            <img src="{{ asset('assets/img/templates/vcard12.png') }}" class="w-100 img-fluid "
                                alt="vcard-img">
                        </div>
                        <div class="card-body p-0 pt-4 mt-1">
                            <h3 class="fs-20 text-center">{{ __('messages.Gym') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 mb-60">
                    <div class="template-card h-100">
                        <div class="card-img">
                            <img src="{{ asset('assets/img/templates/vcard13.png') }}" class="w-100  img-fluid"
                                alt="vcard-img">
                        </div>
                        <div class="card-body p-0 pt-4 mt-1">
                            <h3 class="fs-20 text-center">{{ __('messages.Hospital') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 mb-60">
                    <div class="template-card h-100">
                        <div class="card-img">
                            <img src="{{ asset('assets/img/templates/vcard14.png') }}" class="w-100 img-fluid"
                                alt="vcard-img">
                        </div>
                        <div class="card-body p-0 pt-4 mt-1">
                            <h3 class="fs-20 text-center">{{ __('messages.Event_Management') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 mb-60">
                    <div class="template-card h-100">
                        <div class="card-img">
                            <img src="{{ asset('assets/img/templates/vcard15.png') }}" class="w-100 img-fluid"
                                alt="vcard-img">
                        </div>
                        <div class="card-body p-0 pt-4 mt-1">
                            <h3 class="fs-20 text-center">{{ __('messages.Salon') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 mb-60">
                    <div class="template-card h-100">
                        <div class="card-img">
                            <img src="{{ asset('assets/img/templates/vcard16.png') }}" class="w-100 img-fluid"
                                alt="vcard-img">
                        </div>
                        <div class="card-body p-0 pt-4 mt-1">
                            <h3 class="fs-20 text-center">{{ __('messages.Lawyer') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 mb-60">
                    <div class="template-card h-100">
                        <div class="card-img">
                            <img src="{{ asset('assets/img/templates/vcard17.png') }}" class="w-100 img-fluid"
                                alt="vcard-img">
                        </div>
                        <div class="card-body p-0 pt-4 mt-1">
                            <h3 class="fs-20 text-center">{{ __('messages.Programmer') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <a href="{{ route('vcard-templates') }}" class="btn btn-primary-light" role="button"
                        data-turbo="false">{{ __('messages.analytics.view_more') }}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- start features section -->
    <section class="features-section overflow-hidden" id="frontFeaturesTab">
        <div class="container">
            <div class="section-heading mb-60">
                <h2 class="d-inline-block">{{ __('messages.plan.features') }}</h2>
            </div>
            <div class="feature-slider">
                @foreach ($features as $feature)
                    <div class="">
                        <div class="feature-card">
                            <div class="card-img overflow-hidden">
                                <img src="{{ $feature->profile_image }}" class="w-100 h-100 object-fit-cover"
                                    alt="feature-img">
                            </div>
                            <div class="card-body p-0">
                                <h3 class="fs-18 mb-3">{{ $feature->name }}</h3>
                                <p class="text-gray-100 mb-0">{!! $feature->description !!}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- end features section -->

    <!-- start modern & powerful-interface section -->
    <section class="modern-interface-section overflow-hidden pb-100" id="frontAboutTabUsTab">
        <div class="container">
            <div class="section-heading text-center mb-60">
                <h2 class="d-inline-block">{{ __('auth.modern_&_powerful_interface') }}</h2>
            </div>
            <div class="interface-card mb-40">
                <div class="row m-0 pb-2 justify-content-between align-items-center">
                    <div class="col-lg-5 col-md-6 mb-md-0 mb-40">
                        <div class="interface-img">
                            <img class="h-auto w-100"
                                src="{{ isset($aboutUS[0]['about_url']) ? $aboutUS[0]['about_url'] : asset('front/images/about-1.png') }}"
                                alt="interface-img">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card-desc ps-lg-0 ps-md-4">
                            <h3 class="card-title fs-20 fw-6 mb-3">
                                {{ $aboutUS[0]['title'] }}
                            </h3>
                            <p class="card-text text-gray-100">
                                {!! nl2br(e($aboutUS[0]['description'])) !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="interface-card mb-40">
                <div class="row pb-2 m-0 flex-md-row flex-column-reverse justify-content-between align-items-center">
                    <div class="col-lg-6 col-md-6 pe-lg-0 pe-md-4">
                        <div class="card-desc">
                            <h3 class="card-title fs-20 fw-6 mb-3">
                                {{ $aboutUS[1]['title'] }}
                            </h3>
                            <p class="card-text text-gray-100">
                                {!! nl2br(e($aboutUS[1]['description'])) !!}
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 mb-md-0 mb-40">
                        <div class="interface-img">
                            <img class="h-auto w-100"
                                src="{{ isset($aboutUS[1]['about_url']) ? $aboutUS[1]['about_url'] : asset('front/images/about-2.png') }}"
                                alt="interface img" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="interface-card">
                <div class="row m-0 pb-2 justify-content-between align-items-center">
                    <div class="col-lg-5 col-md-6 mb-md-0 mb-40">
                        <div class="interface-img">
                            <img class="h-auto w-100"
                                src="{{ isset($aboutUS[2]['about_url']) ? $aboutUS[2]['about_url'] : asset('front/images/about-3.png') }}"
                                alt="interface img" />
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card-desc ps-lg-0 ps-md-4">
                            <h3 class="card-title fs-20 fw-6 mb-3">
                                {{ $aboutUS[2]['title'] }}
                            </h3>
                            <p class="card-text text-gray-100">
                                {!! nl2br(e($aboutUS[2]['description'])) !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end modern & powerful-interface section  -->

    <!-- start pricing section -->
    <section class="pricing-plan-section pb-100" id="frontPricingTab">
        <div class="container">
            <div class="section-heading text-center mb-60">
                <h2 class="d-inline-block"> {{ __("auth.choose_a_plan_that's_right_for_you") }}</h2>
            </div>
            <div class="pricing-slider">
                @foreach ($plans as $plan)
                    <div class="">
                        <div class="pricing-card h-100">
                            <div class="text-center">
                                <h3 class="card-title">{!! $plan->name !!}</h3>
                                <label class="fs-18 my-3">{{ __('messages.plan.no_of_vcards') }}
                                    : {{ $plan->no_of_vcards }}</label>
                            </div>
                            <h2 class="price text-center fw-5 mb-30">
                                {{ $plan->currency->currency_icon }}{{ $plan->price }}
                                @if ($plan->frequency == 1)
                                    <span class="fs-18">/ {{ __('messages.plan.monthly') }}</span>
                                @elseif($plan->frequency == 2)
                                    <span class="fs-18">/ {{ __('messages.plan.yearly') }}</span>
                                @endif
                            </h2>
                            <ul class="pricing-plan-list ps-xl-4 ps-lg-3 ps-md-4 ps-3 mb-60">
                                @foreach (getPlanFeature($plan) as $feature => $value)
                                    <li class="{{ $value == 1 ? 'active-check' : 'unactive-check' }}">
                                        <span class="check-box">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                        {{ __('messages.feature.' . $feature) }}
                                    </li>
                                @endforeach
                            </ul>
                            <div class="text-center">
                                @if (getLoggedInUserRoleId() != getSuperAdminRoleId())
                                    @if (getLogInUser() && getLoggedInUserRoleId() != getSuperAdminRoleId())
                                        <div class="mx-auto">

                                            @if (
                                                !empty(getCurrentSubscription()) &&
                                                    $plan->id == getCurrentSubscription()->plan_id &&
                                                    !getCurrentSubscription()->isExpired())
                                                @if ($plan->price != 0)
                                                    <button type="button"
                                                        class="btn btn-success rounded-3  mx-auto d-block cursor-remove-plan pricing-plan-button-active"
                                                        data-id="{{ $plan->id }}" data-turbo="false">
                                                        {{ __('messages.subscription.currently_active') }}</button>
                                                @else
                                                    <button type="button"
                                                        class="btn btn-info rounded-3  mx-auto d-block cursor-remove-plan"
                                                        data-turbo="false">
                                                        {{ __('messages.subscription.renew_free_plan') }}
                                                    </button>
                                                @endif
                                            @else
                                                @if (
                                                    !empty(getCurrentSubscription()) &&
                                                        !getCurrentSubscription()->isExpired() &&
                                                        ($plan->price == 0 || $plan->price != 0))
                                                    @if ($plan->hasZeroPlan->count() == 0)
                                                        <a href="{{ $plan->price != 0 ? route('choose.payment.type', $plan->id) : 'javascript:void(0)' }}"
                                                            class="btn btn-primary rounded-3 mx-auto {{ $plan->price == 0 ? 'freePayment' : '' }}"
                                                            data-id="{{ $plan->id }}"
                                                            data-plan-price="{{ $plan->price }}" data-turbo="false">
                                                            {{ __('messages.subscription.switch_plan') }}</a>
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-info rounded-3 mx-auto d-block cursor-remove-plan"
                                                            data-turbo="false">
                                                            {{ __('messages.subscription.renew_free_plan') }}
                                                        </button>
                                                    @endif
                                                @else
                                                    @if ($plan->hasZeroPlan->count() == 0)
                                                        <a href="{{ $plan->price != 0 ? route('choose.payment.type', $plan->id) : 'javascript:void(0)' }}"
                                                            class="btn btn-primary rounded-3 mx-auto  {{ $plan->price == 0 ? 'freePayment' : '' }}"
                                                            data-id="{{ $plan->id }}"
                                                            data-plan-price="{{ $plan->price }}" data-turbo="false">
                                                            {{ __('messages.subscription.choose_plan') }}</a>
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-info rounded-3 mx-auto d-block cursor-remove-plan"
                                                            data-turbo="false">
                                                            {{ __('messages.subscription.renew_free_plan') }}
                                                        </button>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <div class="mx-auto">
                                            @if ($plan->hasZeroPlan->count() == 0)
                                                <a href="{{ $plan->price != 0 ? route('choose.payment.type', $plan->id) : 'javascript:void(0)' }}"
                                                    class="btn btn-primary rounded-3 mx-auto  {{ $plan->price == 0 ? 'freePayment' : '' }}"
                                                    data-id="{{ $plan->id }}" data-plan-price="{{ $plan->price }}"
                                                    data-turbo="false">
                                                    {{ __('messages.subscription.choose_plan') }}</a>
                                            @else
                                                <button type="button"
                                                    class="btn btn-info rounded-3 mx-auto d-block cursor-remove-plan"
                                                    data-turbo="false">
                                                    {{ __('messages.subscription.renew_free_plan') }}
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- end pricing section -->

    <!-- start testimonial section -->
    @if (!$testimonials->isEmpty())
        <section class="testimonial-section">
            <div class="section-heading text-center mb-60">
                <h2 class="d-inline-block">{{ __('auth.stories_from_our_customers') }}</h2>
            </div>
            <div class="testimonial bg-light pt-50 pb-50">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="testimonial-slider">
                                @foreach ($testimonials as $testimonial)
                                    <div class="">
                                        <div class="testimonial-card mb-60">
                                            <div class="quote-img">
                                                <img src="{{ asset('assets/img/new_home_page/quote-img.png') }}"
                                                    alt="quotation" class="w-sm-100 w-50 h-auto">
                                            </div>
                                            <div class="profile-img">
                                                <img src="{{ $testimonial->testimonial_url }}" alt="profile-img"
                                                    class="w-100 h-100 object-fit-cover">
                                            </div>
                                            <div class="profile-desc ps-3">
                                                <p class="fs-20 mb-0 fw-6">{{ $testimonial->name }}</p>
                                            </div>
                                            <p class="mt-4 mb-0 profile-text text-gray-100">{!! $testimonial->description !!}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>
    @endif
    <!-- end testimonial section -->

    <!-- start contact section -->
    <section class="contact-section pt-100 pb-100" id="frontContactUsTab">
        <div class="section-heading text-center mb-60">
            <h2 class="d-inline-block">{{ __('messages.vcard_11.get_in_touch') }}</h2>
        </div>

        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-5 mb-lg-0 mb-60">
                    <div class="contact-img ">
                        {{-- <picture>
                            <source type="image/webp" srcset="{{ asset('assets/img/new_home_page/contact-img.webp') }}"
                                alt="contact" class="h-auto w-100">
                            <img src="{{ asset('assets/img/new_home_page/contact-img.png') }}" alt="contact"
                                class="h-auto w-100">
                        </picture> --}}
                        <img src="{{ asset('assets/img/new_home_page/contact-img.png') }}" alt="contact"
                            class="h-auto w-100">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-7">
                    <form class="contact-form" id="myForm">
                        @csrf
                        <div id="contactError" class="alert alert-danger d-none"></div>

                        <p class="text-center mb-40 fs-4 fw-6">{{ __('messages.contact_us.send_message') }}</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input name="name" id="name" type="text" class="form-control"
                                        placeholder="{{ __('messages.front.enter_your_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input name="email" id="email" type="email" class="form-control"
                                        placeholder="{{ __('messages.front.enter_your_email') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <input name="subject" id="subject" type="text" class="form-control"
                                        placeholder="{{ __('messages.common.subject') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <textarea name="message" id="message" rows="4" class="form-control h-100 form-textarea"
                                        placeholder="{{ __('messages.front.enter_your_message') }}" required></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 text-center">
                                <input type="submit" id="submit" name="send" class="btn btn-primary w-50"
                                    value="{{ __('messages.contact_us.send_message') }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- end contact section -->
@endsection
