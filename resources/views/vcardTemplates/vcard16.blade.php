<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    @if (checkFeature('seo'))
        @if ($vcard->meta_description)
            <meta name="description" content="{{ $vcard->meta_description }}">
        @endif
        @if ($vcard->meta_keyword)
            <meta name="keywords" content="{{ $vcard->meta_keyword }}">
        @endif
    @else
        <meta name="description" content="{{ $vcard->description }}">
        <meta name="keywords" content="">
    @endif
    <meta property="og:image" content="{{ $vcard->cover_url }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (checkFeature('seo') && $vcard->site_title && $vcard->home_title)
        <title>{{ $vcard->home_title }} | {{ $vcard->site_title }}</title>
    @else
        <title>{{ $vcard->name }} | {{ getAppName() }}</title>
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ getFaviconUrl() }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vcard16.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/custom.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/custom-vcard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lightbox.css') }}">
    @if ($vcard->font_family || $vcard->font_size || $vcard->custom_css)
        <style>
            @if (checkFeature('custom-fonts'))
                @if ($vcard->font_family)
                    body {
                        font-family: {{ $vcard->font_family }};
                    }
                @endif
                @if ($vcard->font_size)
                    div>h4 {
                        font-size: {{ $vcard->font_size }}px !important;
                    }
                @endif
            </style>
            @endif
            @if (isset(checkFeature('advanced')->custom_css))
                {!! $vcard->custom_css !!}
            @endif

    @endif
</head>

<body>
    <div class="container p-0">
        @include('vcards.password')
        <div class="main-content mx-auto w-100 overflow-hidden">
            <div class="banner-section w-100">
                <div class="banner-img">
                    <img src="{{ $vcard->cover_url }}" class="object-fit-cover w-100 h-100" />
                    <div class="d-flex justify-content-end position-absolute top-0 end-0 me-3 z-index-9">
                        @if ($vcard->language_enable == \App\Models\Vcard::LANGUAGE_ENABLE)
                            <div class="language pt-4 me-2">
                                <ul class="text-decoration-none">
                                    <li class="dropdown1 dropdown lang-list">
                                        <a class="dropdown-toggle lang-head text-decoration-none" data-toggle="dropdown"
                                            role="button" aria-haspopup="true" aria-expanded="false">
                                            <i
                                                class="fa-solid fa-language me-2"></i>{{ getLanguage($vcard->default_language) }}
                                        </a>
                                        <ul class="dropdown-menu start-0 top-dropdown lang-hover-list top-100 mt-0">
                                            @foreach (getAllLanguageWithFullData() as $language)
                                                <li
                                                    class="{{ getLanguageIsoCode($vcard->default_language) == $language->iso_code ? 'active' : '' }}">
                                                    <a href="javascript:void(0)" id="languageName"
                                                        data-name="{{ $language->iso_code }}">
                                                        @if (array_key_exists($language->iso_code, \App\Models\User::FLAG))
                                                            @foreach (\App\Models\User::FLAG as $imageKey => $imageValue)
                                                                @if ($imageKey == $language->iso_code)
                                                                    <img src="{{ asset($imageValue) }}"
                                                                        class="me-1" />
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            @if (count($language->media) != 0)
                                                                <img src="{{ $language->image_url }}" class="me-1" />
                                                            @else
                                                                <i class="fa fa-flag fa-xl me-3 text-danger"
                                                                    aria-hidden="true"></i>
                                                            @endif
                                                        @endif
                                                        {{ $language->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="profile-section pb-40  bg-primary">
                <div class="row justify-content-end ">
                    <div class="col-sm-8">
                        <div class="card flex-sm-row">
                            <div class="card-img d-flex justify-content-center align-items-center">
                                <img src="{{ $vcard->profile_url }}"
                                    class="rounded-circle h-100 w-100 object-fit-cover" />
                            </div>
                            <div class="card-body p-0 text-center">
                                <div class="profile-name">
                                    <h2 class=" text-primary fs-28 fw-bold mb-1">
                                        {{ ucwords($vcard->first_name . ' ' . $vcard->last_name) }}
                                    </h2>
                                    <p class="fs-18 text-primary mb-0">{{ ucwords($vcard->occupation) }}</p>
                                    <p class="fs-18 text-primary mb-0">{{ ucwords($vcard->job_title) }}</p>
                                    <p class="fs-18 text-primary mb-0">{{ ucwords($vcard->company) }}</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="social-media mt-sm-0 mt-0 pt-0 mb-40 d-flex justify-content-center">
                    @if (checkFeature('social_links') && getSocialLink($vcard))
                        <div class="social-icons d-flex justify-content-center pt-3 flex-wrap w-100">
                            @foreach (getSocialLink($vcard) as $value)
                                {!! $value !!}
                            @endforeach
                        </div>
                    @endif
                    {{-- <a href="" class="social-icon d-flex justify-content-center align-items-center">
                        <i class="fa-brands fa-facebook "></i>
                    </a>
                    <a href="" class="social-icon d-flex justify-content-center align-items-center">
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                    <a href="" class="social-icon d-flex justify-content-center align-items-center">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>

                    <a href="" class="social-icon d-flex justify-content-center align-items-center">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0.166748 21.8325C0.200617 21.7072 0.219971 21.6253 0.244164 21.5434C0.708657 19.8615 1.17315 18.1844 1.62797 16.5026C1.66184 16.3725 1.64248 16.1942 1.57958 16.0785C-0.525153 12.1606 -0.147752 7.54869 2.70211 4.13195C5.29069 1.01399 8.66794 -0.263067 12.6935 0.291129C15.2966 0.652562 17.4449 1.89589 19.1819 3.85244C22.5737 7.68363 22.714 13.4232 19.5642 17.4953C16.4676 21.5 10.8162 22.806 6.28257 20.5362C5.99227 20.3916 5.7455 20.3675 5.43584 20.4542C3.77625 20.9024 2.11665 21.3265 0.452218 21.7602C0.374802 21.7795 0.292548 21.7988 0.166748 21.8325ZM5.52777 8.49323C5.60519 8.85949 5.65841 9.23537 5.76002 9.59681C5.96807 10.3197 6.40353 10.9221 6.83416 11.5244C8.32441 13.6063 10.2066 15.158 12.7129 15.8809C13.758 16.1797 14.7402 16.1411 15.6644 15.4906C16.3079 15.0376 16.5159 14.4063 16.5063 13.6738C16.5063 13.5533 16.4095 13.3798 16.3079 13.3268C15.5821 12.9605 14.8467 12.6087 14.1064 12.2714C13.8161 12.1365 13.6806 12.1943 13.4822 12.4497C13.2258 12.7726 12.9693 13.0907 12.7081 13.4039C12.5629 13.5822 12.3839 13.6448 12.1516 13.5533C10.5404 12.9172 9.30662 11.8473 8.43085 10.363C8.31473 10.1703 8.32441 10.0112 8.47924 9.84258C8.66794 9.63536 8.84696 9.4185 9.02115 9.19682C9.24372 8.90768 9.29694 8.60889 9.13243 8.2571C8.89535 7.75591 8.70181 7.23545 8.48408 6.72463C8.15506 5.93911 8.15506 5.91984 7.29381 5.94875C7.06157 5.95839 6.78577 6.04513 6.61643 6.19452C5.91969 6.79209 5.56648 7.56315 5.52777 8.49323Z" />
                        </svg>

                    </a>
                    <a href="" class="social-icon d-flex justify-content-center align-items-center">
                        <i class="fa-brands fa-twitter"></i>
                    </a> --}}
                </div>
                <div class="desc px-30 text-gray-100">
                    <p class="text-center mb-0">
                        {!! $vcard->description !!}
                    </p>
                </div>
            </div>

            <div class="contact-section  pt-40">
                <div class="section-heading left-heading">
                    <h2 class="">{{ __('messages.contact_us.contact') }}</h2>
                </div>
                <div class="row align-items-end px-sm-0 px-30">
                    <div class="col-sm-6">
                        <div class="contact-img text-sm-end text-center">
                            <img src="{{ asset('assets/img/vcard16/contact-lawyer-img.png') }}" class="h-100">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="row mb-sm-0 mb-4">
                            <div class="col-12 mb-10">
                                {{-- email --}}
                                @if ($vcard->email)
                                    <div class="contact-box d-flex align-items-center">
                                        <div class="contact-icon d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('assets/img/vcard16/email.png') }}" />
                                        </div>
                                        <div class="contact-desc">
                                            <p class="text-primary fw-5 mb-0 fs-12">
                                                {{ __('messages.vcard.email_address') }}</p>
                                            <a href="mailto:{{ $vcard->email }}"
                                                class="event-name text-center pt-3 mb-0  fw-6  text-decoration-none text-dark">{{ $vcard->email }}</a>
                                        </div>
                                    </div>
                                @endif
                                {{-- alternative email --}}
                                @if ($vcard->alternative_email)
                                    <div class="contact-box d-flex align-items-center">
                                        <div class="contact-icon d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('assets/img/vcard16/email.png') }}" />
                                        </div>
                                        <div class="contact-desc">
                                            <p class="text-primary fw-5 mb-0 fs-12">
                                                {{ __('messages.vcard.alter_email_address') }}</p>
                                            <a href="mailto:{{ $vcard->alternative_email }}"
                                                class="event-name text-center pt-3 mb-0  fw-6 text-decoration-none text-dark">{{ $vcard->alternative_email }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            {{-- phone  no --}}
                            @if ($vcard->phone)
                                <div class="col-12 mb-10">
                                    <div class="contact-box d-flex align-items-center">
                                        <div class="contact-icon d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('assets/img/vcard16/phone.png') }}" />
                                        </div>
                                        <div class="contact-desc">
                                            <p class="text-primary fw-5 mb-0 fs-12">
                                                {{ __('messages.vcard.mobile_number') }}</p>
                                            <a href="tel:+ {{ $vcard->region_code }}
                                            {{ $vcard->phone }}"
                                                class="text-primary fw-6 mb-0 fs-14">+{{ $vcard->region_code }}
                                                {{ $vcard->phone }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- alternative phone --}}
                            @if ($vcard->alternative_phone)
                                <div class="col-12 mb-10">
                                    <div class="contact-box d-flex align-items-center">
                                        <div class="contact-icon d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('assets/img/vcard16/phone.png') }}" />
                                        </div>
                                        <div class="contact-desc">
                                            <p class="text-primary fw-5 mb-0 fs-12">
                                                {{ __('messages.vcard.alter_mobile_number') }}</p>
                                            <a href="tel:+{{ $vcard->alternative_region_code }} {{ $vcard->alternative_phone }}"
                                                class="text-primary fw-6 mb-0 fs-14">+{{ $vcard->alternative_region_code }}
                                                {{ $vcard->alternative_phone }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- dob --}}
                            @if($vcard->dob)
                                <div class="col-12 mb-10">
                                    <div class="contact-box d-flex align-items-center">
                                        <div class="contact-icon d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('assets/img/vcard16/dob-icon.png') }}" />
                                        </div>
                                        <div class="contact-desc">
                                            <p class="text-primary fw-5 mb-0 fs-12">{{ __('messages.vcard.dob') }}</p>
                                            <p class="text-primary fw-6 fs-14 mb-0">{{ $vcard->dob }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- location --}}
                            @if ($vcard->location)
                                <div class="col-12 mb-10">
                                    <div class="contact-box d-flex align-items-center">
                                        <div class="contact-icon d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('assets/img/vcard16/location.png') }}" />
                                        </div>
                                        <div class="contact-desc">
                                            <p class="text-primary fw-5 mb-0 fs-12">
                                                {{ __('messages.setting.address') }}</p>
                                            <p class="text-primary fw-6 fs-14 mb-0">{!! ucwords($vcard->location) !!}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{-- make appointment --}}
            @if (checkFeature('appointments') && $vcard->appointmentHours->count())
                <div class="appointment-section pt-50  pb-50 bg-primary">
                    <div class="section-heading right-heading mb-40">
                        <h2 class="">{{ __('messages.make_appointments') }}</h2>
                    </div>
                    <div class="appointment  px-30">

                        <div class="row">
                            <div class="col-12 mb-20">
                                <label class="text-white fw-5 mb-2">{{ __('messages.date') }} :</label>
                                <div class="position-relative">
                                    {{ Form::text('date', null, ['class' => ' date  form-control appointment-input', 'placeholder' => __('messages.form.pick_date'), 'id' => 'pickUpDate']) }}
                                    <span class="calendar-icon">
                                        <img src="{{ asset('assets/img/vcard16/Vector.png') }}" />
                                    </span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="text-white mb-2">{{ __('messages.hour') }} :</label>
                                <div id="slotData" class="row">
                                </div>
                            </div>
                            <div class="col-12 text-center mt-2">
                                <button
                                    class="btn btn-white appoint-btn appointmentAdd ">{{ __('messages.make_appointments') }}</button>
                            </div>
                        </div>

                    </div>
                </div>
                @include('vcardTemplates.appointment')
            @endif
            {{-- qrcode --}}
            @if (isset($vcard['show_qr_code']) && $vcard['show_qr_code'] == 1)
                <div class="qr-code-section pt-40 pb-50 position-relative">
                    <div class="section-heading right-heading pb-40">
                        <h2 class="bg-primary text-white">
                            {{ __('messages.vcard.qr_code') }}
                        </h2>
                    </div>
                    <div class="px-30">
                        <div class="row align-items-center flex-sm-row flex-column-reverse justify-content-center">
                            <div class="col-sm-5">
                                <div
                                    class="qr-code d-flex justify-content-center position-relative ms-sm-auto mx-auto mb-sm-0 mb-4">
                                    <div class="qr-profile-img">
                                        <img src="{{ $vcard->profile_url }}" class="w-100 h-100 object-fit-cover" />
                                    </div>
                                    <div class="qr-code-img" id="qr-code-sixteen">
                                        @if (isset($customQrCode['applySetting']) && $customQrCode['applySetting'] == 1)
                                            {!! QrCode::color(
                                                $qrcodeColor['qrcodeColor']->red(),
                                                $qrcodeColor['qrcodeColor']->green(),
                                                $qrcodeColor['qrcodeColor']->blue(),
                                            )->backgroundColor(
                                                    $qrcodeColor['background_color']->red(),
                                                    $qrcodeColor['background_color']->green(),
                                                    $qrcodeColor['background_color']->blue(),
                                                )->style($customQrCode['style'])->eye($customQrCode['eye_style'])->size(130)->format('svg')->generate(Request::url()) !!}
                                        @else
                                            {!! QrCode::size(130)->format('svg')->generate(Request::url()) !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- service --}}
            @if (checkFeature('services') && $vcard->services->count())
                <div class="our-services-section pt-40 pb-30">
                    <div class="section-heading left-heading mb-40">
                        <h2 class="">
                            {{ __('messages.vcard.our_service') }}
                        </h2>
                    </div>
                    <div class="services-bg">
                        <img src="{{ asset('assets/img/vcard16/services-bg.png') }}" alt="" class="">
                    </div>
                    <div class="services px-30">
                        <div class="row">
                            @foreach ($vcard->services as $service)
                                <div class="col-sm-6 mb-40">
                                    <div class="service-card card h-100 align-items-center">
                                        <div class="card-img d-flex justify-content-center align-items-center mb-4">
                                            <img src="{{ $service->service_icon }}" alt=""
                                                class="object-fit-cover" />
                                            </a>
                                        </div>
                                        <div class="card-body text-center p-0">
                                            <h3 class="card-title fs-18 fw-4 text-primary">{{ $service->name }}</h3>
                                            <p
                                                class="mb-0 text-gray-300 {{ \Illuminate\Support\Str::length($service->description) > 80 ? 'more' : '' }}">
                                                {!! $service->description !!}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            {{-- gallery --}}
            @if (checkFeature('gallery') && $vcard->gallery->count())
                <div class="gallery-section bg-primary position-relative pt-60 pb-60">
                    <div class="gallery-bg text-end position-absolute">
                        <img src="{{ asset('assets/img/vcard16/gallery-bg.png') }}" class="h-100">
                    </div>
                    <div class="row h-100 align-items-center flex-sm-row flex-column-reverse pt-sm-0 pt-60">
                        <div class="col-sm-6 p-0">
                            <div class="gallery-slider">
                                @foreach ($vcard->gallery as $file)
                                    @php
                                        $infoPath = pathinfo(public_path($file->gallery_image));
                                        $extension = $infoPath['extension'];
                                    @endphp
                                    <div class="">
                                        <div class="gallery-images">
                                            @if ($file->type == App\Models\Gallery::TYPE_IMAGE)
                                                <div class="gallery-img img-2 ms-sm-auto me-sm-0 mx-auto">
                                                    <a href="{{ $file->gallery_image }}"
                                                        data-lightbox="gallery-images"><img
                                                            src="{{ $file->gallery_image }}" alt="profile"
                                                            class="img-fluid h-100 object-fit-cover" /></a>
                                                </div>
                                            @elseif($file->type == App\Models\Gallery::TYPE_FILE)
                                                <div class="gallery-img img-2 ms-sm-auto me-sm-0 mx-auto">
                                                    <a id="file_url" href="{{ $file->gallery_image }}"
                                                        class="gallery-link gallery-file-link" target="_blank">
                                                        <div class="gallery-item"
                                                            @if ($extension == 'pdf') style="background-image: url({{ asset('assets/images/pdf-icon.png') }})"> @endif
                                                            @if ($extension == 'xls') style="background-image: url({{ asset('assets/images/xls.png') }})"> @endif
                                                            @if ($extension == 'csv') style="background-image: url({{ asset('assets/images/csv-file.png') }})"> @endif
                                                            @if ($extension == 'xlsx') style="background-image: url({{ asset('assets/images/xlsx.png') }})"> @endif
                                                            </div>
                                                    </a>
                                                </div>
                                            @elseif($file->type == App\Models\Gallery::TYPE_VIDEO)
                                                <video width="100%" height="" controls>
                                                    <source src="{{ $file->gallery_image }}">
                                                </video>
                                            @elseif($file->type == App\Models\Gallery::TYPE_AUDIO)
                                                <div class="audio-container">
                                                    <img src="{{ asset('assets/img/music.jpeg') }}" alt="Album Cover"
                                                        class="audio-image" height="135">
                                                    <audio controls src="{{ $file->gallery_image }}" class="mt-2">
                                                        Your browser does not support the <code>audio</code> element.
                                                    </audio>
                                                </div>
                                            @else
                                                <div class="gallery-img img-2 ms-sm-auto me-sm-0 mx-auto">
                                                    <iframe
                                                        src="https://www.youtube.com/embed/{{ YoutubeID($file->link) }}"
                                                        class="w-100 h-100">
                                                    </iframe>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-sm-6 overflow-hidden">
                            <div class="section-heading mb-sm-0 mb-40 right-heading">
                                <h2 class="">
                                    {{ __('messages.plan.gallery') }}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- product --}}
            @if (checkFeature('products') && $vcard->products->count())
                <div class="product-section pt-40 pb-30">
                    <div class="section-heading left-heading ">
                        <h2 class="">
                            {{ __('messages.plan.products') }}
                        </h2>
                        <div class="text-end my-3 me-3 ">
                            <a class="fs-6 mb-0 text-decoration-underline p-2"
                                href="{{ route('showProducts', ['id' => $vcard->id, 'alias' => $vcard->url_alias]) }}">{{ __('messages.analytics.view_more') }}</a>
                        </div>
                    </div>
                    <div class="product-slider px-4">
                        @foreach ($vcardProducts as $product)
                            <div class="">
                                <div class="product-card card">
                                    <a @if ($product->product_url) href="{{ $product->product_url }}" @endif
                                        target="_blank" class="text-decoration-none fs-6">
                                        <div class="product-img card-img">
                                            <img src="{{ $product->product_icon }}"
                                                class="w-100 h-100 object-fit-cover" />
                                        </div>
                                        <div
                                            class="product-desc card-body d-flex align-items-center justify-content-between">
                                            <div class="product-title">
                                                <h3 class="text-primary fs-18  mb-0">{{ $product->name }}</h3>
                                                <p class="fs-14 text-gray-300 mb-0"> {{ $product->description }}
                                                </p>
                                            </div>
                                            <div class="product-amount text-primary  fs-18">
                                                @if ($product->currency_id && $product->price)
                                                    <span
                                                        class="text-dark">{{ $product->currency->currency_icon }}{{ number_format($product->price, 2) }}</span>
                                                @elseif($product->price)
                                                    <span class="text-dark">{{ getUserCurrencyIcon($vcard->user->id) .' '. $product->price }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            {{-- testimonial --}}
            @if (checkFeature('testimonials') && $vcard->testimonials->count())
                <div class="testimonial-section pt-40 pb-50 bg-primary position-relative">
                    <div class="section-heading left-heading mb-40">
                        <h2 class="bg-white text-primary">
                            {{ __('messages.plan.testimonials') }}
                        </h2>
                    </div>
                    <div class="testimonial-slider px-30 ">
                        @foreach ($vcard->testimonials as $testimonial)
                            <div class="">
                                <div class="testimonial-card">
                                    <div class="card-img">
                                        <img src="{{ $testimonial->image_url }}" alt="testimonial"
                                            class="w-100 h-100 object-fit-cover">
                                    </div>
                                    <div class="card-body p-0  text-center">
                                        <p
                                            class="text-gray-100 mb-20 {{ \Illuminate\Support\Str::length($testimonial->description) > 80 ? 'more' : '' }}">
                                            {!! $testimonial->description !!}
                                        </p>
                                        <h6 class="card-title fs-20  mb-0 text-white">
                                            {{ ucwords($testimonial->name) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="testimonial-bg-img">
                        <img src="{{ asset('assets/img/vcard16/testimonial-bg-img.png') }}" />
                    </div>

                </div>
            @endif
            {{-- blog --}}
            @if (checkFeature('blog') && $vcard->blogs->count())
                <div class="blog-section pt-40 pb-50 bg-gray-200">
                    <div class="section-heading right-heading mb-4">
                        <h2 class="bg-primary text-white">
                            {{ __('messages.feature.blog') }}
                        </h2>
                    </div>
                    <div class="blog-slider px-4">
                        @foreach ($vcard->blogs as $blog)
                            <div class="">
                                <div class="blog-card card flex-sm-row align-items-center">
                                    <div class="card-img">
                                        <a href="{{ route('vcard.show-blog', [$vcard->url_alias, $blog->id]) }}">
                                            <img src="{{ $blog->blog_icon }}" alt="profile"
                                                class="w-100 h-100 blog-img" />
                                        </a>
                                    </div>
                                    <div class="card-body p-0 text-sm-start text-center">
                                        <h6 class="card-title text-primary  fs-18">{{ $blog->title }}</h6>
                                        <p class="mb-0  fs-12 text-gray-100">
                                            {!! $blog->description !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="blog-bg">
                        <img src="{{ asset('assets/img/vcard16/blog-bg-img.png') }}">
                    </div>
                </div>
            @endif


            {{-- buisness hours --}}
            @if ($vcard->businessHours->count())
                @php
                    $todayWeekName = strtolower(\Carbon\Carbon::now()->rawFormat('D'));
                @endphp
                <div class="business-hour-section pt-40 pb-50 bg-primary">
                    <div class="section-heading left-heading mb-40">
                        <h2 class="bg-white text-primary">
                            {{ __('messages.business.business_hours') }}
                        </h2>
                    </div>
                    <div class="px-30">
                        <div class="bussiness-hour-card">
                            @foreach ($businessDaysTime as $key => $dayTime)
                                <div class="mb-10 d-flex align-items-center justify-content-between">
                                    <span
                                        class="me-2">{{ __('messages.business.' . \App\Models\BusinessHour::DAY_OF_WEEK[$key]) }}</span>
                                    <span> {{ $dayTime ?? __('messages.common.closed') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="business-hour-bg">
                        <img src="{{ asset('assets/img/vcard16/hour-bg-img.png') }}">
                    </div>
                </div>
            @endif

            {{-- contact us --}}
            @php
                $currentSubs = $vcard
                    ->subscriptions()
                    ->where('status', \App\Models\Subscription::ACTIVE)
                    ->latest()
                    ->first();
            @endphp
            @if ($currentSubs && $currentSubs->plan->planFeature->enquiry_form && $vcard->enable_enquiry_form)
                <div class="contact-us-section pt-40 pb-50">
                    <div class="section-heading right-heading pb-40">
                        <h2 class="bg-primary text-white">
                            {{ __('messages.contact_us.inquries') }}
                        </h2>
                    </div>
                    <div class="contact-form  px-30 position-relative">
                        <form action="" id="enquiryForm">
                            @csrf
                            <div class="row">
                                <div id="enquiryError" class="alert alert-danger d-none"></div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <input type="text" name="name" class="form-control"
                                            placeholder="{{ __('messages.form.your_name') }}" id="name" />
                                    </div>
                                    <div class="mb-3">
                                        <input type="email" name="email" class="form-control"
                                            placeholder="{{ __('messages.form.your_email') }}" id="email" />
                                    </div>
                                    <div class="mb-3">
                                        <input type="tel" name="phone" class="form-control"
                                            placeholder="{{ __('messages.form.phone') }}" id="phone" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <textarea class="form-control h-100" name="message" placeholder="{{ __('messages.form.type_message') }}"
                                            rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button class="send-btn btn-primary w-50" type="submit">
                                        {{ __('messages.contact_us.send_message') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            {{-- create vcard --}}
            @if (!empty($userSetting['enable_affiliation']))
                <div class="create-vcard-section bg-primary pt-40 pb-40">
                    <div class="section-heading left-heading mb-40">
                        <h2 class="bg-white text-primary">
                            {{ __('messages.create_vcard') }}
                        </h2>
                    </div>
                    <div class="vcard-link-card card mx-sm-5 mx-4">
                        <div class="d-flex justify-content-center align-items-center">
                            <a target="_blank"
                                href="{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}"
                                class="fw-5 text-white link-text">{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}
                                <i class="icon fa-solid fa-arrow-up-right-from-square ms-3 text-white"></i></a>
                        </div>
                    </div>
                </div>
            @endif
            <div class="container">
                <div class="d-flex  flex-column justify-content-center mt-2 mb-sm-5">
                    @if ($vcard->location_url && isset($url[5]))
                        <div class="m-2 mb-10 mt-0">
                            <iframe width="100%" height="300px"
                                src='https://maps.google.de/maps?q={{ $url[5] }}/&output=embed'
                                frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                                style="border-radius: 10px;"></iframe>
                        </div>
                    @endif
                </div>
            </div>

            {{-- add to contact --}}
            @if (!empty($userSetting['enable_contact']))
                <div class="add-to-contact-section py-4 px-30 text-center">
                    <div class="d-inline-block">
                        <a href="{{ route('add-contact', $vcard->id) }}"
                            class="vcard16-sticky-btn add-contact-btn sticky-vcard16-div d-flex justify-content-center ms-0 align-items-center rounded px-5 text-decoration-none py-1 justify-content-center"><i
                                class="fas fa-download fa-address-book"></i>
                            &nbsp;{{ __('messages.setting.add_contact') }}</a>
                    </div>
                </div>
            @endif
            {{-- made_by --}}
            <div class="d-flex justify-content-evenly">
                @if (checkFeature('advanced'))
                    @if (checkFeature('advanced')->hide_branding && $vcard->branding == 0)
                        @if ($vcard->made_by)
                            <a @if (!is_null($vcard->made_by_url)) href="{{ $vcard->made_by_url }}" @endif
                                class="text-center text-decoration-none text-dark" target="_blank">
                                <small>{{ __('messages.made_by') }} {{ $vcard->made_by }}</small>
                            </a>
                        @else
                            <div class="text-center">
                                <small class="text-dark">{{ __('messages.made_by') }}
                                    {{ $setting['app_name'] }}</small>
                            </div>
                        @endif
                    @endif
                @else
                    @if ($vcard->made_by)
                        <a @if (!is_null($vcard->made_by_url)) href="{{ $vcard->made_by_url }}" @endif
                            class="text-center text-decoration-none text-dark" target="_blank">
                            <small>{{ __('messages.made_by') }} {{ $vcard->made_by }}</small>
                        </a>
                    @else
                        <div class="text-center">
                            <small class="text-dark">{{ __('messages.made_by') }}
                                {{ $setting['app_name'] }}</small>
                        </div>
                    @endif
                @endif
                @if (!empty($vcard->privacy_policy) || !empty($vcard->term_condition))
                    <div>
                        <a class="text-decoration-none text-dark cursor-pointer terms-policies-btn"
                            href="{{ route('vcard.show-privacy-policy', [$vcard->url_alias, $vcard->id]) }}"><small>{!! __('messages.vcard.term_policy') !!}</small></a>
                    </div>
                @endif
            </div>
            {{-- mady_by over --}}

            {{-- sticky buttons --}}
            <div class="btn-section cursor-pointer">
                <div class="fixed-btn-section">
                    @if (empty($userSetting['hide_stickybar']))
                        <div class="bars-btn lawyer-bars-btn">
                            <img src="{{ asset('assets/img/vcard16/sticky.png') }}" />
                        </div>
                    @endif
                    <div class="sub-btn d-none">
                        <div class="sub-btn-div">
                            @if (isset($userSetting['whatsapp_share']) && $userSetting['whatsapp_share'] == 1)
                                <div class="icon-search-container mb-3" data-ic-class="search-trigger">
                                    <div class="wp-btn">
                                        <i class="fab text-light  fa-whatsapp fa-2x" id="wpIcon"></i>
                                    </div>
                                    <input type="number" class="search-input" id="wpNumber"
                                        data-ic-class="search-input"
                                        placeholder="{{ __('messages.setting.wp_number') }}" />
                                    <div class="share-wp-btn-div">
                                        <a href="javascript:void(0)"
                                            class="vcard16-sticky-btn vcard16-btn-group d-flex justify-content-center align-items-center text-light rounded-0 text-decoration-none py-1 rounded-pill justify-content share-wp-btn">
                                            <i class="fa-solid fa-paper-plane text-dark"></i> </a>
                                    </div>
                                </div>
                            @endif
                            @if (empty($userSetting['hide_stickybar']))
                                <div
                                    class="{{ isset($userSetting['whatsapp_share']) && $userSetting['whatsapp_share'] == 1 ? 'vcard16-btn-group' : 'stickyIcon' }}">
                                    <button type="button"
                                        class="vcard16-btn-group vcard10-share  vcard16-sticky-btn mb-3  px-2 py-1"><i
                                            class="fas fa-share-alt fs-4"></i></button>
                                    @if(!empty($vcard->enable_download_qr_code))
                                    <a type="button"
                                        class="vcard16-btn-group vcard16-sticky-btn  d-flex justify-content-center text-dark align-items-center  px-2 mb-3 py-2"
                                        id="qr-code-btn" download="qr_code.png"><i
                                            class="fa-solid fa-qrcode fs-4"></i></a>
                                    @endif
                                    {{-- <a type="button"
                                        class="vcard16-btn-group vcard16-sticky-btn  d-flex justify-content-center text-dark align-items-center  px-2 mb-3 py-2 d-none"
                                        id="videobtn"><i class="fa-solid fa-video fs-4"
                                            style="color: #eceeed;"></i></a> --}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- share modal code --}}
    <div id="vcard10-shareModel" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="">
                    <div class="row align-items-center mt-3">
                        <div class="col-10 text-center">
                            <h5 class="modal-title" style="padding-left: 50px;">{{ __('messages.vcard.share_my_vcard') }}</h5>
                        </div>
                        <div class="col-2 p-0">
                            <button type="button" aria-label="Close"
                                class="btn btn-sm btn-icon btn-active-color-danger border-none"
                                data-bs-dismiss="modal">
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                        viewBox="0 0 24 24" version="1.1">
                                        <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                            fill="#000000">
                                            <rect fill="#000000" x="0" y="7" width="16" height="2"
                                                rx="1" />
                                            <rect fill="#000000" opacity="0.5"
                                                transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                                x="0" y="7" width="16" height="2" rx="1" />
                                        </g>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                @php
                    $shareUrl = route('vcard.show', ['alias' => $vcard->url_alias]);
                @endphp
                <div class="modal-body">
                    <a href="http://www.facebook.com/sharer.php?u={{ $shareUrl }}" target="_blank"
                        class="text-decoration-none share" title="Facebook">
                        <div class="row">
                            <div class="col-2">
                                <i class="fab fa-facebook fa-2x" style="color: #1B95E0"></i>

                            </div>
                            <div class="col-9 p-1">
                                <p class="align-items-center text-dark">
                                    {{ __('messages.social.Share_on_facebook') }}</p>
                            </div>
                            <div class="col-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.0" height="16px"
                                    viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                        fill="#000000" stroke="none">
                                        <path
                                            d="M1277 4943 l-177 -178 1102 -1102 1103 -1103 -1103 -1103 -1102 -1102 178 -178 177 -177 1280 1280 1280 1280 -1280 1280 -1280 1280 -178 -177z" />
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>
                    <a href="http://twitter.com/share?url={{ $shareUrl }}&text={{ $vcard->name }}&hashtags=sharebuttons"
                        target="_blank" class="text-decoration-none share" title="Twitter">
                        <div class="row">
                            <div class="col-2">

                                <span><svg xmlns="http://www.w3.org/2000/svg" height="2em"
                                        viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
                                    </svg></span>

                            </div>
                            <div class="col-9 p-1">
                                <p class="align-items-center text-dark">
                                    {{ __('messages.social.Share_on_twitter') }}</p>
                            </div>
                            <div class="col-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.0" height="16px"
                                    viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                        fill="#000000" stroke="none">
                                        <path
                                            d="M1277 4943 l-177 -178 1102 -1102 1103 -1103 -1103 -1103 -1102 -1102 178 -178 177 -177 1280 1280 1280 1280 -1280 1280 -1280 1280 -178 -177z" />
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>
                    <a href="http://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}" target="_blank"
                        class="text-decoration-none share" title="Linkedin">
                        <div class="row">
                            <div class="col-2">
                                <i class="fab fa-linkedin fa-2x" style="color: #1B95E0"></i>
                            </div>
                            <div class="col-9 p-1">
                                <p class="align-items-center text-dark">
                                    {{ __('messages.social.Share_on_linkedin') }}</p>
                            </div>
                            <div class="col-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.0" height="16px"
                                    viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                        fill="#000000" stroke="none">
                                        <path
                                            d="M1277 4943 l-177 -178 1102 -1102 1103 -1103 -1103 -1103 -1102 -1102 178 -178 177 -177 1280 1280 1280 1280 -1280 1280 -1280 1280 -178 -177z" />
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>
                    <a href="mailto:?Subject=&Body={{ $shareUrl }}" target="_blank"
                        class="text-decoration-none share" title="Email">
                        <div class="row">
                            <div class="col-2">
                                <i class="fas fa-envelope fa-2x" style="color: #191a19  "></i>
                            </div>
                            <div class="col-9 p-1">
                                <p class="align-items-center text-dark">
                                    {{ __('messages.social.Share_on_email') }}</p>
                            </div>
                            <div class="col-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.0" height="16px"
                                    viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                        fill="#000000" stroke="none">
                                        <path
                                            d="M1277 4943 l-177 -178 1102 -1102 1103 -1103 -1103 -1103 -1102 -1102 178 -178 177 -177 1280 1280 1280 1280 -1280 1280 -1280 1280 -178 -177z" />
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>
                    <a href="http://pinterest.com/pin/create/link/?url={{ $shareUrl }}" target="_blank"
                        class="text-decoration-none share" title="Pinterest">
                        <div class="row">
                            <div class="col-2">
                                <i class="fab fa-pinterest fa-2x" style="color: #bd081c"></i>
                            </div>
                            <div class="col-9 p-1">
                                <p class="align-items-center text-dark">
                                    {{ __('messages.social.Share_on_pinterest') }}</p>
                            </div>
                            <div class="col-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.0" height="16px"
                                    viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                        fill="#000000" stroke="none">
                                        <path
                                            d="M1277 4943 l-177 -178 1102 -1102 1103 -1103 -1103 -1103 -1102 -1102 178 -178 177 -177 1280 1280 1280 1280 -1280 1280 -1280 1280 -178 -177z" />
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>
                    <a href="http://reddit.com/submit?url={{ $shareUrl }}&title={{ $vcard->name }}"
                        target="_blank" class="text-decoration-none share" title="Reddit">
                        <div class="row">
                            <div class="col-2">
                                <i class="fab fa-reddit fa-2x" style="color: #ff4500"></i>
                            </div>
                            <div class="col-9 p-1">
                                <p class="align-items-center text-dark">
                                    {{ __('messages.social.Share_on_reddit') }}</p>
                            </div>
                            <div class="col-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.0" height="16px"
                                    viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                        fill="#000000" stroke="none">
                                        <path
                                            d="M1277 4943 l-177 -178 1102 -1102 1103 -1103 -1103 -1103 -1102 -1102 178 -178 177 -177 1280 1280 1280 1280 -1280 1280 -1280 1280 -178 -177z" />
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>
                    <a href="https://wa.me/?text={{ $shareUrl }}" target="_blank"
                        class="text-decoration-none share" title="Whatsapp">
                        <div class="row">
                            <div class="col-2">
                                <i class="fab fa-whatsapp fa-2x" style="color: limegreen"></i>
                            </div>
                            <div class="col-9 p-1">
                                <p class="align-items-center text-dark">
                                    {{ __('messages.social.Share_on_whatsapp') }}</p>
                            </div>
                            <div class="col-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.0" height="16px"
                                    viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                        fill="#000000" stroke="none">
                                        <path
                                            d="M1277 4943 l-177 -178 1102 -1102 1103 -1103 -1103 -1103 -1102 -1102 178 -178 177 -177 1280 1280 1280 1280 -1280 1280 -1280 1280 -178 -177z" />
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>
                    <div class="col-12 justify-content-between social-link-modal">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="{{ request()->fullUrl() }}"
                                disabled>
                            <span id="vcardUrlCopy{{ $vcard->id }}" class="d-none" target="_blank">
                                {{ route('vcard.show', ['alias' => $vcard->url_alias]) }} </span>
                            <button class="copy-vcard-clipboard btn btn-dark" title="Copy Link"
                                data-id="{{ $vcard->id }}">
                                <i class="fa-regular fa-copy fa-2x"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text-center">

                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
    </div>
</body>
@include('vcardTemplates.template.templates')
<script src="https://js.stripe.com/v3/"></script>
<script src="../js/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/front-third-party.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/slider/js/slick.min.js') }}" type="text/javascript"></script>
@if (checkFeature('seo') && $vcard->google_analytics)
    {!! $vcard->google_analytics !!}
@endif
@if (isset(checkFeature('advanced')->custom_js) && $vcard->custom_js)
    {!! $vcard->custom_js !!}
@endif
@php
    $setting = \App\Models\UserSetting::where('user_id', $vcard->tenant->user->id)
        ->where('key', 'stripe_key')
        ->first();
@endphp
<script>
     @if (isset(checkFeature('advanced')->custom_js) && $vcard->custom_js)
    {!! $vcard->custom_js !!}
    @endif
</script>
<script>
    let stripe = ''
    @if (!empty($setting) && !empty($setting->value))
        stripe = Stripe('{{ $setting->value }}');
    @endif
    $().ready(function() {
        $(".gallery-slider").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            speed: 300,
            infinite: true,
            autoplaySpeed: 5000,
            autoplay: true,
            responsive: [{
                    breakpoint: 575,
                    settings: {
                        centerPadding: '125px',
                        dots: true,
                        autoplay: true,
                    },
                },
                {
                    breakpoint: 480,
                    settings: {
                        centerPadding: '0',
                        dots: true,
                    },
                },
            ],
        });
        $(".product-slider").slick({
            arrows: false,
            infinite: true,
            dots: false,
            slidesToShow: 2,
            slidesToScroll: 1,
            autoplay: true,
            responsive: [{
                breakpoint: 575,
                settings: {
                    slidesToShow: 1,
                },
            }, ],
        });
        $(".testimonial-slider").slick({
            arrows: true,
            infinite: true,
            dots: true,
            slidesToShow: 1,
            autoplay: true,
            prevArrow: '<button class="slide-arrow prev-arrow"><i class="fa-solid fa-arrow-left"></i></button>',
            nextArrow: '<button class="slide-arrow next-arrow"><i class="fa-solid fa-arrow-right"></i></button>',
            responsive: [{
                breakpoint: 575,
                settings: {
                    arrows: false,
                },
            }, ],
        });
        $(".blog-slider").slick({
            arrows: false,
            infinite: true,
            dots: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            responsive: [{
                breakpoint: 575,
                settings: {
                    centerPadding: '0',
                },
            }, ],
        });
    });
</script>
<script>
    let isEdit = false
    let password = "{{ isset(checkFeature('advanced')->password) && !empty($vcard->password) }}"
    let passwordUrl = "{{ route('vcard.password', $vcard->id) }}";
    let enquiryUrl = "{{ route('enquiry.store', ['vcard' => $vcard->id, 'alias' => $vcard->url_alias]) }}";
    let appointmentUrl = "{{ route('appointment.store', ['vcard' => $vcard->id, 'alias' => $vcard->url_alias]) }}";
    let slotUrl = "{{ route('appointment-session-time', $vcard->url_alias) }}";
    let appUrl = "{{ config('app.url') }}";
    let vcardId = {{ $vcard->id }};
    let vcardAlias = "{{ $vcard->url_alias }}";
    let languageChange = "{{ url('language') }}";
    let paypalUrl = "{{ route('paypal.init') }}"
    let lang = "{{ checkLanguageSession($vcard->url_alias) }}";
</script>
<script>
    const qrCodeSixteen = document.getElementById("qr-code-sixteen");
    const svg = qrCodeSixteen.querySelector("svg");
    const blob = new Blob([svg.outerHTML], {
        type: 'image/svg+xml'
    });
    const url = URL.createObjectURL(blob);
    const image = document.createElement('img');
    image.src = url;
    image.addEventListener('load', () => {
        const canvas = document.createElement('canvas');
        canvas.width = canvas.height = {{ $vcard->qr_code_download_size }};
        const context = canvas.getContext('2d');
        context.drawImage(image, 0, 0, canvas.width, canvas.height);
        const link = document.getElementById('qr-code-btn');
        link.href = canvas.toDataURL();
        URL.revokeObjectURL(url);
    });
</script>
@routes
<script src="{{ asset('messages.js') }}"></script>
<script src="{{ mix('assets/js/custom/helpers.js') }}"></script>
<script src="{{ mix('assets/js/custom/custom.js') }}"></script>
<script src="{{ mix('assets/js/vcards/vcard-view.js') }}"></script>
<script src="{{ mix('assets/js/lightbox.js') }}"></script>

</html>
