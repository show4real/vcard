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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @if (checkFeature('seo') && $vcard->site_title && $vcard->home_title)
        <title>{{ $vcard->home_title }} | {{ $vcard->site_title }}</title>
    @else
        <title>{{ $vcard->name }} | {{ getAppName() }}</title>
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ getFaviconUrl() }}" type="image/png">

    {{-- css link --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vcard17.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slider/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slider/css/slick-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-vcard.css') }}">
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
        <div class="main-content mx-auto w-100 overflow-hidden border">
            <div class="banner-section position-relative">
                <div class="banner-img">
                    <img src="{{ $vcard->cover_url }}" class="w-100 h-100 object-fit-cover" />
                    <div class="d-flex justify-content-end position-absolute top-0 end-0 me-3">
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
                    <div class="overlay"></div>
                </div>
            </div>
            <div class="profile-section px-30">
                <div class="profile-bg">
                    <img src="{{ asset('assets/img/vcard17/profile-bg.png') }}" />
                </div>
                <div class="profile-bg-vector">
                    <img src="{{ asset('assets/img/vcard17/profile-bg-vector.png') }}" />
                </div>
                <div class="tag-img">
                    <img src="{{ asset('assets/img/vcard17/tag.png') }}" />
                </div>
                <div class="card align-items-center mb-3 pb-3">
                    <div class="card-img d-flex justify-content-center align-items-center">
                        <img src="{{ $vcard->profile_url }}" class="w-100 h-100 object-fit-cover" />
                    </div>
                    <div class="card-body pt-30 pb-0 px-0 text-center">
                        <div class="profile-name">
                            <h4 class="text-primary mb-0 fw-bold">
                                {{ ucwords($vcard->first_name . ' ' . $vcard->last_name) }}</h4>
                            <p class="fs-18 text-gray-300 mb-0 fw-5">{{ ucwords($vcard->occupation) }}</p>
                            <p class="fs-18 text-gray-300 mb-0 fw-5">{{ ucwords($vcard->job_title) }}</p>
                            <p class="fs-18 text-gray-300 mb-0 fw-5">{{ ucwords($vcard->company) }}</p>
                        </div>
                    </div>
                </div>
                {{-- social media icon --}}
                <div class="vcard-seventeen__social pt-0 py-3 px-sm-3 px-2 position-relative mt-0">
                    @if (checkFeature('social_links') && getSocialLink($vcard))
                        <div
                            class="social-icons d-flex justify-content-center text-decoration-none flex-wrap text-primary bg-gray-100 rounded-pill">
                            @foreach (getSocialLink($vcard) as $value)
                                <span
                                    class="social-back d-flex text-decoration-none bg-gray-100 justify-content-center align-items-center m-sm-2 m-1 text-primary rounded-circle">
                                    {!! $value !!}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
                {{-- profile --}}
                <p class="text-gray-300 mb-0 text-center profile-desc">
                    {!! $vcard->description !!}
                </p>
            </div>
            <div class="contact-section">
                <div class="px-30">
                    <div class="row">
                        @if ($vcard->email)
                            <div class="col-sm-6 mb-sm-5 mb-40 px-3">
                                <div class="contact-box mb-sm-2">
                                    <div class="contact-icon d-flex justify-content-center align-items-center bg-pink">
                                        <img src="{{ asset('assets/img/vcard17/email.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-gray-300 mb-0 fs-12 fw-6">
                                            {{ __('messages.vcard.email_address') }}</p>
                                        <a href="mailto:{{ $vcard->email }}"
                                            class="text-primary fs-14 fw-5">{{ $vcard->email }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($vcard->alternative_email)
                            <div class="col-sm-6 mb-sm-5 mb-40 px-3">
                                <div class="contact-box mb-sm-2">
                                    <div class="contact-icon d-flex justify-content-center align-items-center bg-pink">
                                        <img src="{{ asset('assets/img/vcard17/email.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-gray-300 mb-0 fs-12 fw-6">
                                            {{ __('messages.vcard.alter_email_address') }}</p>
                                        <a href="mailto:{{ $vcard->alternative_email }}"
                                            class="text-primary fs-14 fw-5">{{ $vcard->alternative_email }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($vcard->phone)
                            <div class="col-sm-6 mb-sm-5 mb-40 px-3">
                                <div class="contact-box mb-sm-2">
                                    <div
                                        class="contact-icon d-flex justify-content-center align-items-center bg-orange">
                                        <img src="{{ asset('assets/img/vcard17/phone.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-gray-300 mb-0 fs-12 fw-6">
                                            {{ __('messages.vcard.mobile_number') }} </p>
                                        <a href="tel:+{{ $vcard->region_code }}{{ $vcard->phone }}"
                                            class="text-primary fs-14 fw-5">+{{ $vcard->region_code }}
                                            {{ $vcard->phone }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($vcard->alternative_phone)
                            <div class="col-sm-6 mb-sm-5 mb-40 px-3">
                                <div class="contact-box mb-sm-2">
                                    <div
                                        class="contact-icon d-flex justify-content-center align-items-center bg-orange">
                                        <img src="{{ asset('assets/img/vcard17/phone.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-gray-300 mb-0 fs-12 fw-6">
                                            {{ __('messages.vcard.alter_mobile_number') }}</p>
                                        <a href="tel:+{{ $vcard->alternative_region_code }}{{ $vcard->alternative_phone }}"
                                            class="text-primary fs-14 fw-5">+{{ $vcard->alternative_region_code }}
                                            {{ $vcard->alternative_phone }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($vcard->dob)
                        <div class="col-sm-6 mb-sm-0 mb-40 px-3">
                            <div class="contact-box">
                                <div class="contact-icon d-flex justify-content-center align-items-center bg-blue">
                                    <img src="{{ asset('assets/img/vcard17/dob-icon.png') }}" />
                                </div>
                                <div class="contact-desc">
                                    <p class="text-gray-300 mb-0 fs-12 fw-6">{{ __('messages.vcard.dob') }}</p>
                                    <p class="text-primary fs-14 fw-5 mb-0">
                                        {{ $vcard->dob }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($vcard->location)
                            <div class="col-sm-6 px-3">
                                <div class="contact-box">
                                    <div
                                        class="contact-icon d-flex justify-content-center align-items-center bg-purple">
                                        <img src="{{ asset('assets/img/vcard17/location.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-gray-300 mb-0 fs-12 fw-6">{{ __('messages.setting.address') }}
                                        </p>
                                        <p class="text-primary fs-14 fw-5 mb-0">{!! ucwords($vcard->location) !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- qr code --}}
            @if (isset($vcard['show_qr_code']) && $vcard['show_qr_code'] == 1)
                <div class="qr-code-section pt-60">
                    <div class="qr-bg-img">
                        <img src="{{ asset('assets/img/vcard17/qr-bg-img.png') }}" />
                    </div>
                    <div class="code-img">
                        <img src="{{ asset('assets/img/vcard17/code.png') }}" />
                    </div>
                    <div class="section-heading text-center pb-40 mb-40">
                        <h2 class="text-primary">{{ __('messages.vcard.qr_code') }}</h2>
                    </div>
                    <div class="px-30">
                        <div class="qr-code mt-3 mx-auto position-relative">
                            <div class="qr-profile-img">
                                <img src="{{ $vcard->profile_url }}" class="w-100 h-100 object-fit-cover" />
                            </div>
                            <div class="qr-code-img mx-auto" id="qr-code-seventeen">
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
            @endif

            {{-- our service --}}
            @if (checkFeature('services') && $vcard->services->count())
                <div class="our-services-section px-30 pt-60">
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-primary">{{ __('messages.vcard.our_service') }}</h2>
                    </div>
                    <div class="services">
                        <div class="row">
                            @foreach ($vcard->services as $service)
                                <div class="col-12 mb-40">
                                    <div class="service-card d-flex flex-row">
                                        <div
                                            class="card-img d-flex justify-content-center align-items-center me-sm-4 me-3">
                                            <a href="{{ $service->service_url ?? 'javascript:void(0)' }}"
                                                class="w-100 h-100 text-decoration-none {{ $service->service_url ? 'pe-auto' : 'pe-none' }}"
                                                target="{{ $service->service_url ? '_blank' : '' }}">
                                                <img src="{{ $service->service_icon }}"
                                                    class="card-img-top service-new-image w-100 h-100 object-fit-cover"
                                                    alt="{{ $service->name }}">
                                            </a>
                                        </div>
                                        <div class="card-body p-0">
                                            <h3 class="card-title fs-18 text-primary">{{ ucwords($service->name) }}
                                            </h3>
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
                <div class="gallery-section pt-60 px-sm-0">
                    <div class="phyton-img">
                        <img src="{{ asset('assets/img/vcard17/phyton.png') }}" />
                    </div>
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-primary">{{ __('messages.plan.gallery') }}</h2>
                    </div>
                    <div class="">
                        <div class="gallery-slider">
                            @foreach ($vcard->gallery as $file)
                                @php
                                    $infoPath = pathinfo(public_path($file->gallery_image));
                                    $extension = $infoPath['extension'];
                                @endphp
                                <div class="slide">
                                    <div class="gallery-img">
                                        @if ($file->type == App\Models\Gallery::TYPE_IMAGE)
                                            <a href="{{ $file->gallery_image }}" data-lightbox="gallery-images"><img
                                                    src="{{ $file->gallery_image }}" alt="profile"
                                                    class="w-100" /></a>
                                        @elseif($file->type == App\Models\Gallery::TYPE_FILE)
                                            <a id="file_url" href="{{ $file->gallery_image }}"
                                                class="gallery-link gallery-file-link" target="_blank">
                                                <div class="gallery-item gallery-file-item"
                                                    @if ($extension == 'pdf') style="background-image: url({{ asset('assets/images/pdf-icon.png') }})"> @endif
                                                    @if ($extension == 'xls') style="background-image: url({{ asset('assets/images/xls.png') }})"> @endif
                                                    @if ($extension == 'csv') style="background-image: url({{ asset('assets/images/csv-file.png') }})"> @endif
                                                    @if ($extension == 'xlsx') style="background-image: url({{ asset('assets/images/xlsx.png') }})"> @endif
                                                    </div>
                                            </a>
                                        @elseif($file->type == App\Models\Gallery::TYPE_VIDEO)
                                            <div class="d-flex align-items-center video-container">
                                                <video width="100%" height="100%" controls>
                                                    <source src="{{ $file->gallery_image }}">
                                                </video>
                                            </div>
                                        @elseif($file->type == App\Models\Gallery::TYPE_AUDIO)
                                            <div class="audio-container mt-2">
                                                <img src="{{ asset('assets/img/music.jpeg') }}" alt="Album Cover"
                                                    class="audio-image">
                                                <audio controls src="{{ $file->gallery_image }}" class="mt-2">
                                                    Your browser does not support the <code>audio</code> element.
                                                </audio>
                                            </div>
                                        @else
                                            <iframe src="https://www.youtube.com/embed/{{ YoutubeID($file->link) }}"
                                                class="w-100" height="315">
                                            </iframe>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            {{-- product --}}
            @if (checkFeature('products') && $vcard->products->count())
                <div class="product-section pt-60">
                    <div class="java-img">
                        <img src="{{ asset('assets/img/vcard17/java.png') }}" />
                    </div>
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-primary fw-bold">{{ __('messages.plan.products') }}</h2>
                        <div class="text-end my-3 me-3">
                            <a class="fs-6 mb-0 text-decoration-underline p-2"
                                href="{{ route('showProducts', ['id' => $vcard->id, 'alias' => $vcard->url_alias]) }}">{{ __('messages.analytics.view_more') }}</a>
                        </div>
                    </div>
                    <div class="product-slider pt-2">
                        @foreach ($vcardProducts as $product)
                            <div>
                                <a @if ($product->product_url) href="{{ $product->product_url }}" @endif
                                    target="_blank" class="text-decoration-none fs-6">
                                    <div class="card product-card">
                                        <div class="card-img">
                                            <img src="{{ $product->product_icon }}"
                                                class="h-100 w-100 object-fit-cover" />
                                        </div>
                                        <div class="bg-primary-light card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="product-title">
                                                    <h3 class="text-primary fs-18 fw-5">{{ $product->name }}</h3>
                                                </div>
                                                @if ($product->currency_id && $product->price)
                                                    <span
                                                        class="fs-18 fw-6 text-primary">{{ $product->currency->currency_icon }}{{ number_format($product->price, 2) }}</span>
                                                @elseif($product->price)
                                                    <span class="fs-18 fw-6 text-primary">{{ getUserCurrencyIcon($vcard->user->id) .' '. $product->price }}</span>
                                                @endif
                                            </div>
                                            <p class="text-gray-300 mb-0">
                                                {{ $product->description }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            {{-- testimonial --}}
            @if (checkFeature('testimonials') && $vcard->testimonials->count())
                <div class="testimonial-section">
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-primary">{{ __('messages.plan.testimonials') }}</h2>
                    </div>
                    <div class="bg">
                        <div class="testimonial-bg">
                            <img src="{{ asset('assets/img/vcard17/testimonial-bg.png') }}"
                                class="w-100 h-100 object-fit-cover" />
                        </div>
                        <div class="testimonial-slider mb-0">
                            @foreach ($vcard->testimonials as $testimonial)
                                <div class="d-flex align-items-end">
                                    <div class="card">
                                        <div class="card-img">
                                            <img src="{{ $testimonial->image_url }}"
                                                class="w-100 h-100 object-fit-cover" />
                                        </div>
                                        <div class="card-body text-center">
                                            <h4 class="text-primary fs-6">{{ ucwords($testimonial->name) }}</h4>
                                            <p
                                                class="text-dark-300  mb-0 {{ \Illuminate\Support\Str::length($testimonial->description) > 80 ? 'more' : '' }}">
                                                {!! $testimonial->description !!}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            {{-- blog --}}
            @if (checkFeature('blog') && $vcard->blogs->count())
                <div class="blog-section pt-60 pb-60">
                    <div class="c-language-img">
                        <img src="{{ asset('assets/img/vcard17/c++.png') }}" />
                    </div>
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-primary">{{ __('messages.feature.blog') }}</h2>
                    </div>
                    <div class="blog-slider pt-3 mt-3">
                        @foreach ($vcard->blogs as $blog)
                            <div class="">
                                <div class="blog-card blog-1 card">
                                    <div class="card-img">
                                        <div class="overlay">
                                            <a href="{{ route('vcard.show-blog', [$vcard->url_alias, $blog->id]) }}">
                                                <img src="{{ $blog->blog_icon }}" alt="profile"
                                                    class="h-100 w-100 blog-img" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-end">
                                        <h6 class="card-title text-blue fw-5 fs-20">{{ $blog->title }}</h6>
                                        <p class="mb-0 fw-5 fs-14 text-white">
                                            {!! $blog->description !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            {{-- buisness hours --}}
            @if ($vcard->businessHours->count())
                @php
                    $todayWeekName = strtolower(\Carbon\Carbon::now()->rawFormat('D'));
                @endphp
                <div class="business-hour-section pb-60 px-30">
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-primary">{{ __('messages.business.business_hours') }}</h2>
                    </div>
                    <div class="">
                        <div class="business-hour-card mt-40">
                            <div class="row">
                                @foreach ($businessDaysTime as $key => $dayTime)
                                    <div class="col-12 d-flex justify-content-between mb-2">
                                        <span
                                            class="me-2">{{ __('messages.business.' . \App\Models\BusinessHour::DAY_OF_WEEK[$key]) }}
                                            : </span>
                                        <span
                                            class="text-center">{{ $dayTime ?? __('messages.common.closed') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- make appointment --}}
            @if (checkFeature('appointments') && $vcard->appointmentHours->count())
                <div class="appointment-section pt-60 pb-60 px-30">
                    <div class="appointment-bg">
                        <img src="{{ asset('assets/img/vcard17/appointment-bg.png') }}" />
                    </div>
                    <div class="html-img">
                        <img src="{{ asset('assets/img/vcard17/html.png') }}" />
                    </div>
                    <div class="php-tag">
                        <img src="{{ asset('assets/img/vcard17/php.png') }}" />
                    </div>
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-primary">{{ __('messages.make_appointments') }}</h2>
                    </div>
                    <div class="appointment">
                        <div class="mb-20">
                            <label for="date"
                                class="appoint-date text-primary fs-20 fw-5 mb-2">{{ __('messages.date') }} :</label>
                            <div class="position-relative">
                                {{ Form::text('date', null, ['class' => 'date appoint-input form-control bg-white appointment-input', 'placeholder' => __('messages.form.pick_date'), 'id' => 'pickUpDate']) }}
                                <span class="calendar-icon">
                                    <img src="{{ asset('assets/img/vcard17/Vector.png') }}" />
                                </span>
                            </div>
                        </div>
                        <div class="">
                            <label class="text-primary fs-20 fw-5 mb-2">{{ __('messages.hour') }} :</label>
                            <div class="mb-20">
                                <div id="slotData" class="row">
                                </div>

                            </div>
                            <div class="text-center">
                                <button type="submit"
                                    class="appointmentAdd rounded-2 appoint-btn btn btn-primary w-100">
                                    {{ __('messages.make_appointments') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @include('vcardTemplates.appointment')
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
                <div class="contact-us-section px-30">
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-primary">{{ __('messages.contact_us.inquries') }}</h2>
                    </div>
                    <div class="contact-form">
                        <div class="contact-icon">
                            <img src="{{ asset('assets/img/vcard17/code.png') }}" />
                        </div>
                        <form action="" id="enquiryForm">
                            @csrf
                            <div class="row">
                                <div id="enquiryError" class="alert alert-danger d-none"></div>
                                <div class="col-12">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ __('messages.form.your_name') }}" />
                                </div>
                                <div class="col-12">
                                    <input type="tel" name="phone" class="form-control"
                                        placeholder="{{ __('messages.form.phone') }}" />
                                </div>
                                <div class="col-12">
                                    <input type="email" name="email" class="form-control"
                                        placeholder="{{ __('messages.form.your_email') }}" />
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control h-100" name="message" placeholder="{{ __('messages.form.type_message') }}"
                                        rows="3"></textarea>
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button class="btn send-btn rounded-2 btn-primary" type="submit">
                                        {{ __('messages.contact_us.send_message') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            {{-- cretae vcard --}}
            @if (!empty($userSetting['enable_affiliation']))
                <div class="create-vcard-section pt-60 mb-5">
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-primary">{{ __('messages.create_vcard') }}</h2>
                    </div>
                    <div class="px-30 pt-30 pb-30 bg-primary-light">
                        <div class="vcard-link-card card">
                            <div class="d-flex align-items-center justify-content-center">
                                <a target="_blank"
                                    href="{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}"
                                    class="text-primary link-text fw-5">{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}
                                    <i class="icon fa-solid fa-arrow-up-right-from-square ms-3"></i></a>
                            </div>
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
                <div class="add-to-contact-section pb-4 px-30">
                    <div class="text-center">
                        <a href="{{ route('add-contact', $vcard->id) }}"
                            class="vcard17-sticky-btn add-contact-btn  d-flex justify-content-center align-items-center rounded px-5 text-decoration-none py-1  justify-content-center"><i
                                class="fas fa-download fa-address-book"></i>
                            &nbsp;{{ __('messages.setting.add_contact') }}</a>
                    </div>
                </div>
            @endif
            {{-- made by --}}
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
            {{-- sticky btn --}}
            <div class="btn-section cursor-pointer">
                <div class="fixed-btn-section">
                    @if (empty($userSetting['hide_stickybar']))
                        <div class="bars-btn developer-bars-btn">
                            <img src="{{ asset('assets/img/vcard17/sticky.png') }}" />
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
                                            class="vcard17-sticky-btn vcard17-btn-group d-flex justify-content-center text-dark align-items-center rounded-0 text-decoration-none py-1 rounded-pill justify-content share-wp-btn">
                                            <i class="fa-solid fa-paper-plane"></i> </a>
                                    </div>
                                </div>
                            @endif
                            @if (empty($userSetting['hide_stickybar']))
                                <div
                                    class="{{ isset($userSetting['whatsapp_share']) && $userSetting['whatsapp_share'] == 1 ? 'vcard17-btn-group' : 'stickyIcon' }}">
                                    <button type="button"
                                        class="vcard17-btn-group vcard17-share vcard17-sticky-btn mb-3 px-2 py-1"><i
                                            class="fas fa-share-alt fs-4"></i></button>
                                    @if(!empty($vcard->enable_download_qr_code))
                                    <a type="button"
                                        class="vcard17-btn-group vcard17-sticky-btn d-flex justify-content-center text-white align-items-center  px-2 mb-3 py-2"
                                        id="qr-code-btn" download="qr_code.png"><i
                                            class="fa-solid fa-qrcode fs-4 text-dark"></i></a>
                                    @endif
                                    {{-- <a type="button"
                                        class="vcard17-btn-group vcard17-sticky-btn d-flex justify-content-center text-white align-items-center  px-2 mb-3 py-2 d-none"
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
    <div id="vcard17-shareModel" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="">
                    <div class="row align-items-center mt-3">
                        <div class="col-10 text-center">
                            <h5 class="modal-title" style="padding-left: 50px;">{{ __('messages.vcard.share_my_vcard') }}</h5>
                        </div>
                        <div class="col-2">
                            <button type="button" aria-label="Close"
                                class="btn btn-sm btn-icon btn-active-color-danger border-none p-1"
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
                                <p class="align-items-center text-dark video">
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
    let stripe = ''
    @if (!empty($setting) && !empty($setting->value))
        stripe = Stripe('{{ $setting->value }}');
    @endif
    $().ready(function() {
        $(".product-slider").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            centerMode: true,
            arrows: false,
            dots: true,
            speed: 300,
            centerPadding: "115px",
            infinite: true,
            autoplaySpeed: 5000,
            autoplay: true,
            responsive: [{
                    breakpoint: 575,
                    settings: {
                        centerPadding: "115px",
                        dots: true,
                    },
                },
                {
                    breakpoint: 480,
                    settings: {
                        centerPadding: "0",
                        dots: true,
                    },
                },
            ],
        });
        $(".blog-slider").slick({
            arrows: true,
            infinite: true,
            dots: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            prevArrow: '<button class="slide-arrow prev-arrow"><i class="fa-solid fa-chevron-left"></i></button>',
            nextArrow: '<button class="slide-arrow next-arrow"><i class="fa-solid fa-chevron-right"></i></button>',
            responsive: [{
                breakpoint: 575,
                settings: {
                    dots: true,
                    arrows: false,
                },
            }, ],
        });
        $(".gallery-slider").slick({
            arrows: true,
            infinite: true,
            dots: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            prevArrow: '<button class="slide-arrow prev-arrow"><i class="fa-solid fa-chevron-left"></i></button>',
            nextArrow: '<button class="slide-arrow next-arrow"><i class="fa-solid fa-chevron-right"></i></button>',
            responsive: [{
                breakpoint: 575,
                settings: {
                    dots: true,
                    arrows: false,
                },
            }, ],
        });
    });
</script>
<script>
    var rev = $(".testimonial-slider");
    rev
        .on("init", function(event, slick, currentSlide) {
            var cur = $(slick.$slides[slick.currentSlide]),
                next = cur.next(),
                prev = cur.prev();
            prev.addClass("slick-sprev");
            next.addClass("slick-snext");
            cur.removeClass("slick-snext").removeClass("slick-sprev");
            slick.$prev = prev;
            slick.$next = next;
        })
        .on("beforeChange", function(event, slick, currentSlide, nextSlide) {
            var cur = $(slick.$slides[nextSlide]);
            slick.$prev.removeClass("slick-sprev");
            slick.$next.removeClass("slick-snext");
            (next = cur.next()), (prev = cur.prev());
            prev.prev();
            prev.next();
            prev.addClass("slick-sprev");
            next.addClass("slick-snext");
            slick.$prev = prev;
            slick.$next = next;
            cur.removeClass("slick-next").removeClass("slick-sprev");
        });

    $(".testimonial-slider").slick({
        speed: 1000,
        arrows: false,
        dots: true,
        infinite: true,
        centerMode: true,
        slidesPerRow: 1,
        slidesToShow: 1,
        slidesToScroll: 1,
        centerPadding: "0",
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
    const qrCodeSeventeen = document.getElementById("qr-code-seventeen");
    const svg = qrCodeSeventeen.querySelector("svg");
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
