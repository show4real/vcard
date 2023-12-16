<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
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
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vcard9.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slider/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slider/css/slick-theme.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-vcard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lightbox.css') }}">


    {{-- google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Roboto&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ getFaviconUrl() }}" type="image/png">
    @if (checkFeature('custom-fonts') && $vcard->font_family)
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family={{ $vcard->font_family }}">
    @endif

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
        <div class="vcard-nine main-content w-100 mx-auto overflow-hidden content-blur collapse show allSection">
            {{-- banner --}}
            <div class="vcard-nine__banner w-100 position-relative">
                <img src="{{ $vcard->cover_url }}" class="img-fluid banner-image" alt="banner" />
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
                                    <ul class="dropdown-menu start-0 top-dropdown lang-hover-list top-100">
                                        @foreach (getAllLanguageWithFullData() as $language)
                                            <li
                                                class="{{ getLanguageIsoCode($vcard->default_language) == $language->iso_code ? 'active' : '' }}">
                                                <a href="javascript:void(0)" id="languageName"
                                                    data-name="{{ $language->iso_code }}">
                                                    @if (array_key_exists($language->iso_code, \App\Models\User::FLAG))
                                                        @foreach (\App\Models\User::FLAG as $imageKey => $imageValue)
                                                            @if ($imageKey == $language->iso_code)
                                                                <img src="{{ asset($imageValue) }}" class="me-1" />
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
            {{-- profile --}}
            <div class="vcard-nine__profile position-relative">
                <div class="avatar position-absolute top-0 start-50 translate-middle">
                    <img src="{{ $vcard->profile_url }}" alt="profile-img" class="rounded-circle" />
                </div>
            </div>
            {{-- profile details --}}
            <div class="vcard-nine__profile-details py-3 px-sm-3 px-2">
                <h4 class="profile-name text-center mb-1">{{ ucwords($vcard->first_name . ' ' . $vcard->last_name) }}
                </h4>
                <span class="profile-designation text-center d-block p-2">{{ ucwords($vcard->occupation) }}</span>
                <span class="profile-designation text-center d-block">{{ ucwords($vcard->job_title) }}</span>
                <p><span class="profile-company d-block text-center p-1">{{ ucwords($vcard->company) }}</span></p>
                <div class="d-flex align-items-center mb-5">
                    <span class="pt-5 profile-description fs-6">{!! $vcard->description !!}</span>
                </div>
                @if (checkFeature('social_links') && getSocialLink($vcard))
                    <div class="social-icons d-flex justify-content-center pt-sm-5 pt-4 flex-wrap mx-auto">
                        @foreach (getSocialLink($vcard) as $value)
                            <span class="rounded-circle d-flex justify-content-center align-items-center m-sm-3 m-1">
                                {!! $value !!}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
            {{-- event --}}
            <div class="vcard-nine__event py-3 px-3 mt-2 position-relative">
                <div class="container">
                    <div class="row g-3">
                        @if ($vcard->email)
                            <div class="col-sm-6 col-12">
                                <div
                                    class="card event-card px-3 py-4 h-100 border-0 flex-sm-row flex-column align-items-center">
                                    <span class="event-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard9/vcard9-email.png') }}" alt="email" />
                                    </span>
                                    <div class="event-detail ms-sm-3 mt-sm-0 mt-4">
                                        <h6 class="text-sm-start text-center">{{ __('messages.vcard.email_address') }}
                                        </h6>
                                        <h5><a href="mailto:{{ $vcard->email }}"
                                                class="event-name text-sm-start text-center mb-0 text-decoration-none">{{ $vcard->email }}</a>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($vcard->alternative_email)
                            <div class="col-sm-6 col-12">
                                <div
                                    class="card event-card px-3 py-4 h-100 border-0 flex-sm-row flex-column align-items-center">
                                    <span class="event-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard9/vcard9-alternate-email.png') }}"
                                            alt="email" height="26" width="32" />
                                    </span>
                                    <div class="event-detail ms-sm-3 mt-sm-0 mt-4">
                                        <h6 class="text-sm-start text-center">
                                            {{ __('messages.vcard.alter_email_address') }}</h6>
                                        <h5><a href="mailto:{{ $vcard->alternative_email }}"
                                                class="event-name text-sm-start text-center mb-0 text-decoration-none">{{ $vcard->alternative_email }}</a>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($vcard->phone)
                            <div class="col-sm-6 col-12">
                                <div
                                    class="card event-card px-3 py-4 h-100 border-0 flex-sm-row flex-column align-items-center">
                                    <span class="event-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard9/vcard9-phone.png') }}"
                                            alt="phone" />
                                    </span>
                                    <div class="event-detail ms-sm-3 mt-sm-0 mt-4">
                                        <h6 class="text-sm-start text-center">{{ __('messages.vcard.mobile_number') }}
                                        </h6>
                                        <h5><a href="tel:+{{ $vcard->region_code }}{{ $vcard->phone }}"
                                                class="event-name text-center mb-0 text-decoration-none">+{{ $vcard->region_code }}
                                                {{ $vcard->phone }}</a>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($vcard->alternative_phone)
                            <div class="col-sm-6 col-12">
                                <div
                                    class="card event-card px-3 py-4 h-100 border-0 flex-sm-row flex-column align-items-center">
                                    <span class="event-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard9/alter-phone.png') }}" alt="phone" />
                                    </span>
                                    <div class="event-detail ms-sm-3 mt-sm-0 mt-4">
                                        <h6 class="text-sm-start text-center">
                                            {{ __('messages.vcard.alter_mobile_number') }}</h6>
                                        <h5>
                                            <a href="tel:+{{ $vcard->alternative_region_code }} {{ $vcard->alternative_phone }}"
                                                class="event-name text-center mb-0 text-decoration-none">+{{ $vcard->alternative_region_code }}
                                                {{ $vcard->alternative_phone }}</a>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($vcard->dob)
                            <div class="col-sm-6 col-12">
                                <div
                                    class="card event-card px-3 py-4 h-100 border-0 flex-sm-row flex-column align-items-center">
                                    <span class="event-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard9/vcard9-birthday.png') }}"
                                            alt="birthday" />
                                    </span>
                                    <div class="event-detail ms-sm-3 mt-sm-0 mt-4">
                                        <h6 class="text-sm-start text-center">{{ __('messages.vcard.dob') }}</h6>
                                        <h5 class="event-name text-center mb-0">{{ $vcard->dob }}</h5>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($vcard->location)
                            <div class="col-sm-6 col-12">
                                <div
                                    class="card event-card px-3 py-4 h-100 border-0 flex-sm-row flex-column align-items-center">
                                    <span class="event-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard9/vcard9-location.png') }}"
                                            alt="location" />
                                    </span>
                                    <div class="event-detail ms-sm-3 mt-sm-0 mt-4">
                                        <h6 class="text-sm-start text-center">{{ __('messages.setting.address') }}
                                        </h6>
                                        <h5 class="event-name text-center mb-0">{!! ucwords($vcard->location) !!}</h5>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- qr code --}}
            @if (isset($vcard['show_qr_code']) && $vcard['show_qr_code'] == 1)
                <div class="vcard-nine__qr-code py-4 px-3 position-relative px-sm-3 mb-10 mt-0">
                    <h4 class="heading-left position-relative text-center">{{ __('messages.vcard.qr_code') }}</h4>
                    <div class="container mt-5">
                        <div
                            class="card qr-code-card flex-sm-row flex-column justify-content-center align-items-center mt-3 border-0">
                            <div class="mx-2">
                                <div class="qr-profile mb-3 d-flex justify-content-center">
                                    <img src="{{ $vcard->profile_url }}" alt="qr profile"
                                        class="mx-auto d-block rounded-circle" />
                                </div>

                            </div>
                            <div class="qr-code-scanner mx-md-4 mx-2 p-4 bg-white" id="qr-code-nine">
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
            {{-- our services --}}
            @if (checkFeature('services') && $vcard->services->count())
                <div class="vcard-nine__service py-4 px-3 position-relative px-sm-3 mb-10 mt-0">
                    <h4 class="heading-right heading-line position-relative text-center mb-5">
                        {{ __('messages.vcard.our_service') }}</h4>
                    <div class="container mt-5">
                        <div class="row g-6 justify-content-center">
                            @foreach ($vcard->services as $service)
                                <div class="col-sm-6">
                                    <div class="card service-card">
                                        <a href="{{ $service->service_url ?? 'javascript:void(0)' }}"
                                            class="text-decoration-none {{ $service->service_url ? 'pe-auto' : 'pe-none' }}"
                                            target="{{ $service->service_url ? '_blank' : '' }}">
                                            <img src="{{ $service->service_icon }}"
                                                class="card-img-top service-new-image" alt="{{ $service->name }}">
                                        </a>
                                        <div class="card-body py-4">
                                            <h5 class="card-title">{{ ucwords($service->name) }}</h5>
                                            <p
                                                class="card-text {{ \Illuminate\Support\Str::length($service->description) > 80 ? 'more' : '' }}">
                                                {!! $service->description !!}</p>
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
                <div class="vcard-nine__gallery py-4 px-3 position-relative px-sm-3 mb-10 mt-0">
                    <h4 class="heading-right heading-line position-relative text-center">
                        {{ __('messages.plan.gallery') }}</h4>
                    <div class="container">
                        <div class="row g-3 gallery-slider mt-4">
                            @foreach ($vcard->gallery as $file)
                                @php
                                    $infoPath = pathinfo(public_path($file->gallery_image));
                                    $extension = $infoPath['extension'];
                                @endphp

                                <div class="col-6">
                                    <div class="card gallery-card p-1 border-0 w-100">
                                        <div class="gallery-profile">
                                            @if ($file->type == App\Models\Gallery::TYPE_IMAGE)
                                                <a href="{{ $file->gallery_image }}"
                                                    data-lightbox="gallery-images"><img
                                                        src="{{ $file->gallery_image }}" alt="profile"
                                                        class="w-100" /></a>
                                            @elseif($file->type == App\Models\Gallery::TYPE_FILE)
                                                <a id="file_url" href="{{ $file->gallery_image }}"
                                                    class="gallery-link gallery-file-link" target="_blank">
                                                    <div class="gallery-item"
                                                        @if ($extension == 'pdf') style="background-image: url({{ asset('assets/images/pdf-icon.png') }})"> @endif
                                                        @if ($extension == 'xls') style="background-image: url({{ asset('assets/images/xls.png') }})"> @endif
                                                        @if ($extension == 'csv') style="background-image: url({{ asset('assets/images/csv-file.png') }})"> @endif
                                                        @if ($extension == 'xlsx') style="background-image: url({{ asset('assets/images/xlsx.png') }})"> @endif
                                                        </div>
                                                </a>
                                            @elseif($file->type == App\Models\Gallery::TYPE_VIDEO)
                                             <div class="video-container d-flex align-items-center">
                                                <video width="100%"  controls>
                                                    <source src="{{ $file->gallery_image }}">
                                                </video>
                                                </div>
                                            @elseif($file->type == App\Models\Gallery::TYPE_AUDIO)
                                                <div class="audio-container">
                                                    <img src="{{ asset('assets/img/music.jpeg') }}" alt="Album Cover" class="audio-image" height="173">
                                                    <audio controls src="{{ $file->gallery_image }}" class="mt-2">
                                                        Your browser does not support the <code>audio</code> element.
                                                    </audio>
                                                </div>
                                            @else
                                                <iframe src="https://www.youtube.com/embed/{{ YoutubeID($file->link) }}"
                                                    class="w-100 h-100">
                                                </iframe>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- products --}}
            @if (checkFeature('products') && $vcard->products->count())
                <div class="vcard-nine__product py-4 px-3 position-relative px-sm-3 mb-10 mt-0">
                    <h4 class="heading-left heading-line position-relative text-center">
                        {{ __('messages.plan.products') }}</h4>
                    <div class="text-end me-5">
                        <a class="fs-4 mb-0"
                            href="{{ route('showProducts', ['id' => $vcard->id, 'alias' => $vcard->url_alias]) }}">{{__('messages.analytics.view_more')}}</a>
                    </div>
                    <div class="container">
                        <div class="row g-3 product-slider mt-0">
                            @foreach ($vcardProducts as $product)
                                <div class="col-6 h-100">
                                    <a @if ($product->product_url) href="{{ $product->product_url }}" @endif
                                        target="_blank" class="text-decoration-none fs-6">
                                        <div class="card product-card p-3 border-0 w-100 product-block h-100">
                                            <div class="product-profile">
                                                <img src="{{ $product->product_icon }}" alt="profile"
                                                    class="w-100" height="208px" />
                                            </div>
                                            <div class="product-details mt-3">
                                                <h4>{{ $product->name }}</h4>
                                                <p class="mb-2">
                                                    {{ $product->description }}
                                                </p>
                                                @if ($product->currency_id && $product->price)
                                                    <span
                                                        class="text-black">{{ $product->currency->currency_icon }}{{ number_format($product->price, 2) }}</span>
                                                @elseif($product->price)
                                                    <span class="text-black">{{ getUserCurrencyIcon($vcard->user->id) .' '. $product->price }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            {{-- testimonial --}}
            @if (checkFeature('testimonials') && $vcard->testimonials->count())
                <div class="vcard-nine__testimonial py-4 px-3 position-relative px-sm-3 mb-10 mt-0">
                    <h4 class="heading-right heading-line position-relative text-center">
                        {{ __('messages.plan.testimonials') }}</h4>
                    <div class="container">
                        <div class="row g-3 testimonial-slider mt-4">
                            @foreach ($vcard->testimonials as $testimonial)
                                <div class="col-12 h-100">
                                    <div class="card testimonial-card p-3 border-0 w-100 h-100">
                                        <div
                                            class="testimonial-user d-flex flex-sm-row flex-column align-items-center justify-content-sm-start justify-content-center">
                                            <img src="{{ $testimonial->image_url }}" alt="profile"
                                                class="rounded-circle" />
                                            <div class="user-details d-flex flex-column ms-sm-3 mt-sm-0 mt-2">
                                                <span
                                                    class="user-name text-sm-start text-center">{{ ucwords($testimonial->name) }}</span>
                                                <span class="user-designation text-sm-start text-center"></span>
                                            </div>
                                        </div>
                                        <p
                                            class="review-message mb-2 text-sm-start text-center mt-2 h-100 {{ \Illuminate\Support\Str::length($testimonial->description) > 80 ? 'more' : '' }}">
                                            {{ $testimonial->description }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            {{-- blog --}}
            @if (checkFeature('blog') && $vcard->blogs->count())
                <div class="vcard-nine__blog position-relative py-3 mb-10 mt-0">
                    <h4 class="heading-left heading-line position-relative text-center">
                        {{ __('messages.feature.blog') }}</h4>
                    <div class="container pt-4 px-4">
                        <div class="row g-4 blog-slider overflow-hidden">
                            @foreach ($vcard->blogs as $blog)
                                <div class="col-6 mb-2">
                                    <div class="card blog-card p-2 border-0 w-100 h-100">
                                        <div class="blog-image">
                                            <a href="{{ route('vcard.show-blog', [$vcard->url_alias, $blog->id]) }}">
                                                <img src="{{ $blog->blog_icon }}" alt="profile" class="w-100" />
                                            </a>
                                        </div>
                                        <div class="blog-details p-3">
                                            <a href="{{ route('vcard.show-blog', [$vcard->url_alias, $blog->id]) }}"
                                                class="text-decoration-none">
                                                <h4 class="text-sm-start text-center title-color p-3 mb-0 text-black">
                                                    {{ $blog->title }}</h4>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            {{-- business hour --}}
            @if ($vcard->businessHours->count())
                @php
                    $todayWeekName = strtolower(\Carbon\Carbon::now()->rawFormat('D'));
                @endphp
                <div class="vcard-nine__timing py-4 position-relative px-sm-3 mb-10 mt-0">
                    <h4 class="heading-right position-relative text-center mb-5">
                        {{ __('messages.business.business_hours') }}</h4>
                    <div class="container">
                        <div class="row g-3">
                            @foreach ($businessDaysTime as $key => $dayTime)
                                <div class="col-12">
                                    <div
                                        class="card business-card flex-row
                                        {{ \App\Models\BusinessHour::DAY_OF_WEEK[$key] == $todayWeekName ? 'business-card-today' : '' }}">
                                        <div class="calendar-icon p-4">
                                            <i class="fa-solid fa-calendar-days fs-1 text-white"></i>
                                        </div>
                                        <div class="ms-sm-2 ms-3">
                                            <div class="text-muted ms-sm-5 business-hour-day-text">
                                                {{ __('messages.business.' . \App\Models\BusinessHour::DAY_OF_WEEK[$key]) }}
                                            </div>
                                            <div class="ms-sm-5 fw-bold mt-3 fs-4 business-hour-time-text">
                                                {{ $dayTime ?? __('messages.common.closed') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Appointment --}}
            @if (checkFeature('appointments') && $vcard->appointmentHours->count())
                <div class="vcard-nine__appointment py-3 px-sm-4 px-3 mt-2 position-relative mb-10 mt-0">
                    <h4 class="heading-left heading-line position-relative text-center mb-5">
                        {{ __('messages.make_appointments') }}</h4>
                    <div class="container">
                        <div class="appointment-card p-3">
                            <div class="row d-flex align-items-center justify-content-center mb-3">
                                <div class="col-md-2">
                                    <label for="date"
                                        class="appoint-date mb-2">{{ __('messages.date') }}</label>
                                </div>
                                <div class="col-md-10">
                                    {{ Form::text('date', null, ['class' => 'date appoint-input', 'placeholder' => __('messages.form.pick_date'), 'id' => 'pickUpDate']) }}
                                </div>
                            </div>
                            <div class="row d-flex align-items-center justify-content-center mb-md-3">
                                <div class="col-md-2">
                                    <label for="text"
                                        class="appoint-date mb-2">{{ __('messages.hour') }}</label>
                                </div>
                                <div class="col-md-10">
                                    <div id="slotData" class="row">
                                    </div>
                                </div>
                            </div>


                            <button type="button"
                                class="appointmentAdd appoint-btn text-white mt-4 d-block mx-auto ">{{ __('messages.make_appointments') }}
                            </button>
                        </div>
                    </div>
                </div>
                @include('vcardTemplates.appointment')
            @endif


            <div class="vcard-nine__contact  px-3 position-relative px-sm-3 mb-10 mt-0">
                {{-- contact us --}}
                @php
                    $currentSubs = $vcard
                        ->subscriptions()
                        ->where('status', \App\Models\Subscription::ACTIVE)
                        ->latest()
                        ->first();
                @endphp
                @if ($currentSubs && $currentSubs->plan->planFeature->enquiry_form && $vcard->enable_enquiry_form)
                    <h4 class="heading-right position-relative text-center">
                        {{ __('messages.contact_us.inquries') }}</h4>
                    <div class="container mt-5">
                        <div class="row mt-4">
                            <div class="col-12 px-0">
                                <form id="enquiryForm">
                                    @csrf
                                    <div class="contact-form px-sm-2">
                                        <div id="enquiryError" class="alert alert-danger d-none"></div>
                                        <div class="mb-3">
                                            <input type="text" name="name" class="form-control" id="name"
                                                placeholder="{{ __('messages.form.your_name') }}">
                                        </div>
                                        <div class="mb-3">
                                            <input type="email" name="email" class="form-control" id="email"
                                                placeholder="{{ __('messages.form.your_email') }}">
                                        </div>
                                        <div class="mb-3">
                                            <input type="tel" name="phone" class="form-control" id="mobile"
                                                placeholder="{{ __('messages.form.phone') }}">
                                        </div>
                                        <div class="mb-3">
                                            <textarea class="form-control" name="message" placeholder="{{ __('messages.form.type_message') }}" id="message"
                                                rows="5"></textarea>
                                        </div>
                                        <button type="submit"
                                            class="contact-btn text-white mt-4 d-block mx-auto">{{ __('messages.contact_us.send_message') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if (!empty($userSetting['enable_affiliation']))
                <div class="container mb-10">
                    <h4 class="heading-right position-relative text-center">
                        {{ __('messages.create_vcard') }}</h4>
                    <div class="copy-link bg-white p-4 text-center rounded mt-7 mb-20">
                        <a href="{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}"
                            target="_blank"
                            class="d-flex justify-content-center align-items-center text-decoration-none link-text font-primary">{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}<i
                                class="fa-solid fa-arrow-up-right-from-square ms-3"></i></a>
                    </div>
                </div>
            @endif
            @if ($vcard->location_url && isset($url[5]))
                <div class="m-2 mb-10 mt-0">
                    <iframe width="100%" height="300px"
                        src='https://maps.google.de/maps?q={{ $url[5] }}/&output=embed' frameborder="0"
                        scrolling="no" marginheight="0" marginwidth="0" style="border-radius: 10px;"></iframe>
                </div>
            @endif
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
                                <small>{{ __('messages.made_by') }} {{ $setting['app_name'] }}</small>
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
                            <small>{{ __('messages.made_by') }} {{ $setting['app_name'] }}</small>
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
            <div class="w-100 d-flex justify-content-center  position-fixed" style="top:50%; left:0; z-index: 9999;">
                <div class="vcard-bars-btn position-relative">
                    @if (empty($userSetting['hide_stickybar']))
                        <a href="javascript:void(0)"
                            class="vcard9-sticky-btn  bars-btn d-flex justify-content-center text-white me-5 align-items-center rounded-0 px-5 mb-3 text-decoration-none py-1 rounded-pill justify-content-center">
                            <img src="{{ asset('assets/img/vcard9/sticky.png') }}" />
                        </a>
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
                                            class="vcard9-sticky-btn d-flex justify-content-center align-items-center text-light     rounded-0 text-decoration-none py-1 rounded-pill justify-content share-wp-btn">
                                            <i class="fa-solid fa-paper-plane"></i> </a>
                                    </div>
                                </div>
                            @endif
                            @if (empty($userSetting['hide_stickybar']))
                                <div
                                    class="{{ isset($userSetting['whatsapp_share']) && $userSetting['whatsapp_share'] == 1 ? 'vcard9-btn-group' : 'stickyIcon' }}">
                                    <button type="button"
                                        class="vcard9-btn-group vcard9-share vcard9-sticky-btn mb-3   px-2 py-1"><i
                                            class="fas fa-share-alt fs-1"></i></button>
                                    @if(!empty($vcard->enable_download_qr_code))
                                    <a type="button"
                                        class=" vcard9-btn-group vcard9-sticky-btn text-decoration-none text-white d-flex justify-content-center align-items-center px-2 mb-3 py-2"
                                        id="qr-code-btn" download="qr_code.png"><i
                                            class="fa-solid fa-qrcode fs-1"></i></a>
                                    @endif
                                    {{-- <a type="button"
                                        class="vcard9-btn-group text-decoration-none d-flex justify-content-center align-items-center px-2 mb-3 py-2 d-none"
                                        id="videobtn"><i class="fa-solid fa-video fs-1"
                                            style="color: #eceeed;"></i></a> --}}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="w-100 d-flex justify-content-center sticky-vcard-div">
                        @if (!empty($userSetting['enable_contact']))
                            <a href="{{ route('add-contact', $vcard->id) }}"
                                class="vcard9-sticky-btn add-contact-btn  d-flex justify-content-center ms-0 text-white align-items-center rounded px-5 text-decoration-none py-1  justify-content-center"><i
                                    class="fas fa-download fa-address-book fs-4"></i>
                                &nbsp;{{ __('messages.setting.add_contact') }}</a>
                        @endif
                    </div>
                    {{-- share modal code --}}
                    <div id="vcard9-shareModel" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="">
                    <div class="row align-items-center mt-3">
                        <div class="col-10 text-center">
                            <h5 class="modal-title" style="padding-left: 50px;">{{ __('messages.vcard.share_my_vcard') }}</h5>
                        </div>
                        <div class="col-2 p-0">
                            <button type="button" aria-label="Close"
                                class="p-3 btn btn-sm btn-icon btn-active-color-danger border-none"
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
                @include('vcardTemplates.template.templates')
                <script src="https://js.stripe.com/v3/"></script>
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
                    $('.testimonial-slider').slick({
                        dots: true,
                        infinite: true,
                        arrows: true,
                        autoplay: true,
                        speed: 300,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        prevArrow: '<button class="slide-arrow prev-arrow"></button>',
                        nextArrow: '<button class="slide-arrow next-arrow"></button>',
                    })
                </script>
                <script>
                    $('.product-slider').slick({
                        dots: true,
                        infinite: true,
                        arrows: true,
                        speed: 300,
                        slidesToShow: 2,
                        autoplay: true,
                        slidesToScroll: 1,
                        prevArrow: '<button class="slide-arrow prev-arrow"></button>',
                        nextArrow: '<button class="slide-arrow next-arrow"></button>',
                        responsive: [{
                            breakpoint: 575,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                infinite: true,
                                dots: true
                            }
                        }]
                    });
                </script>
                <script>
                    $('.gallery-slider').slick({
                        dots: true,
                        infinite: true,
                        arrows: true,
                        speed: 300,
                        slidesToShow: 2,
                        autoplay: true,
                        slidesToScroll: 1,
                        prevArrow: '<button class="slide-arrow prev-arrow"></button>',
                        nextArrow: '<button class="slide-arrow next-arrow"></button>',
                        responsive: [{
                            breakpoint: 575,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                infinite: true,
                                dots: true,
                            },
                        }]
                    });

                    $('.blog-slider').slick({
                        dots: true,
                        infinite: true,
                        arrows: true,
                        speed: 300,
                        slidesToShow: 1,
                        autoplay: true,
                        slidesToScroll: 1,
                        prevArrow: '<button class="slide-arrow-blog prev-arrow"></button>',
                        nextArrow: '<button class="slide-arrow-blog next-arrow"></button>',
                    })
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
                    let paypalUrl = "{{ route('paypal.init') }}"
                    let languageChange = "{{ url('language') }}";
                    let lang = "{{ checkLanguageSession($vcard->url_alias) }}";
                </script>
                <script>
                    const qrCodeNine = document.getElementById("qr-code-nine");
                    const svg = qrCodeNine.querySelector("svg");
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

</body>

</html>
