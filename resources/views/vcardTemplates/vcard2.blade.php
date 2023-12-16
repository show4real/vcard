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

    <!-- Favicon -->
    <link rel="icon" href="{{ getFaviconUrl() }}" type="image/png">
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vcard2.css') }}">
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
        <div class="vcard-two main-content w-100 mx-auto overflow-hidden content-blur collapse show allSection">
            {{-- banner --}}
            <div class="vcard-two__banner w-100 position-relative">
                <img src="{{ $vcard->cover_url }}" class="img-fluid banner-image position-relative" alt="background" />
                {{-- shape img --}}
                <img src="{{ asset('assets/img/vcard2/shape1.png') }}" class="banner-shape position-absolute end-0"
                    alt="shape" />

                <div class="d-flex justify-content-end position-absolute top-0 end-0 me-3 custom-language">
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
            <div class="vcard-two__profile position-relative">
                <div class="avatar position-absolute top-0 translate-middle">
                    <img src="{{ $vcard->profile_url }}" alt="profile-img" class="rounded-circle" />
                </div>
            </div>
            {{-- profile details --}}
            <div class="vcard-two__profile-details py-3 px-3 p-5">
                <h4 class="vcard-two-heading">
                    {{ ucwords($vcard->first_name . ' ' . $vcard->last_name) }}
                </h4>
                <span class="profile-designation">{{ ucwords($vcard->occupation) }}</span><br><br>
                <span class="profile-designation">{{ ucwords($vcard->job_title) }}</span><br><br>
                <p><span class="profile-company">{{ ucwords($vcard->company) }}</span></p>
                <p><span class="profile-description d-flex pt-4">{!! $vcard->description !!}</span></p>
                @if (checkFeature('social_links') && getSocialLink($vcard))
                    <div class="social-icons d-flex flex-wrap justify-content-start pt-4 ps-3">
                        @foreach (getSocialLink($vcard) as $value)
                            {!! $value !!}
                        @endforeach
                    </div>
                @endif
            </div>
            {{-- event --}}
            <div class="vcard-two__event py-3 px-3 ms-3">
                @if ($vcard->email)
                    <div class="event-details d-flex">
                        <div class="event-image">
                            <img src="{{ asset('assets/img/vcard2/vcard2-email.png') }}" alt="email"
                                class="" />
                        </div>
                        <span><a href="mailto:{{ $vcard->email }}"
                                class="event-name text-center pt-3 mb-0 email-btn text-decoration-none">{{ $vcard->email }}</a></span>
                    </div>
                @endif
                @if ($vcard->alternative_email)
                    <div class="event-details d-flex">
                        <div class="event-image">
                            <img src="{{ asset('assets/img/vcard2/alter-image.png') }}" alt="email"
                                height="20" width="27" />
                        </div>
                        <span><a href="mailto:{{ $vcard->alternative_email }}"
                                class="event-name text-center pt-3 mb-0 email-btn text-decoration-none">{{ $vcard->alternative_email }}</a></span>
                    </div>
                @endif
                @if($vcard->dob)
                    <div class="event-details d-flex">
                        <div class="event-image">
                            <img src="{{ asset('assets/img/vcard2/vcard2-birthday.png') }}" alt="birthday"
                                class="" />
                        </div>
                        <span>{{ $vcard->dob }}</span>
                    </div>
                @endif
                @if ($vcard->phone)
                    <div class="event-details d-flex">
                        <div class="event-image">
                            <img src="{{ asset('assets/img/vcard2/vcard2-phone.png') }}" alt="phone"
                                class="" />
                        </div>
                        <span> <a href="tel:+{{ $vcard->region_code }}{{ $vcard->phone }}"
                                class="event-name text-center pt-3 mb-0 email-btn text-decoration-none">+{{ $vcard->region_code }}
                                {{ $vcard->phone }}</a></span>
                    </div>
                @endif
                @if ($vcard->alternative_phone)
                    <div class="event-details d-flex">
                        <div class="event-image">
                            <img src="{{ asset('assets/img/vcard2/old-typical-phonevcard2.png') }}" alt="phone"
                                class="" />
                        </div>
                        <span><a href="tel:+{{ $vcard->alternative_region_code }} {{ $vcard->alternative_phone }}"
                                class="event-name text-center pt-3 mb-0 email-btn text-decoration-none">+{{ $vcard->alternative_region_code }}
                                {{ $vcard->alternative_phone }}</a></span>
                    </div>
                @endif
                @if ($vcard->location)
                    <div class="event-details d-flex">
                        <div class="event-image">
                            <img src="{{ asset('assets/img/vcard2/vcard2-location.png') }}" alt="location"
                                class="" />
                        </div>
                        <span>{!! $vcard->location !!}</span>
                    </div>
                @endif
            </div>
            {{-- qr code --}}
            @if (isset($vcard['show_qr_code']) && $vcard['show_qr_code'] == 1)
                <div class="vcard-two__qr-code py-3 position-relative px-3">
                    {{-- shape img --}}
                    <img src="{{ asset('assets/img/vcard2/shape2.png') }}"
                        class="banner-shape-four position-absolute end-0 d-sm-block d-none" alt="shape" />
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="card qr-code-card p-3 border-0">
                                    <h4 class="vcard-two-heading  text-center pb-4">{{ __('messages.vcard.qr_code') }}
                                    </h4>
                                    <div class="row">
                                        <div class="col-sm-6 col-12 mb-sm-0 mb-4">
                                            <div class="qr-profile d-flex justify-content-center">
                                                <img src="{{ $vcard->profile_url }}" class="rounded-circle"
                                                    alt="qr-profile" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="qr-code-image d-flex justify-content-center" id="qr-code-two">
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
                    </div>
                </div>
            @endif
            {{-- our services --}}
            @if (checkFeature('services') && $vcard->services->count())
                <div class="vcard-two__service my-3 py-3 position-relative mb-10 mt-0">
                    {{-- shape img --}}
                    <img src="{{ asset('assets/img/vcard2/shape2.png') }}"
                        class="banner-shape-two position-absolute end-0" alt="shape" />
                    <img src="{{ asset('assets/img/vcard2/shape3.png') }}"
                        class="banner-shape-three position-absolute start-0" alt="shape" />
                    <h4 class="vcard-two-heading text-center pb-4">{{ __('messages.vcard.our_service') }}</h4>
                    <div class="container">
                        <div class="row g-6 justify-content-center">
                            @foreach ($vcard->services as $service)
                                <div class="col-sm-6">
                                    <div class="card service-card h-100">
                                        <a href="{{ $service->service_url ?? 'javascript:void(0)' }}"
                                            class="text-decoration-none {{ $service->service_url ? 'pe-auto' : 'pe-none' }}"
                                            target="{{ $service->service_url ? '_blank' : '' }}">
                                            <img src="{{ $service->service_icon }}"
                                                class="card-img-top service-new-image" alt="{{ $service->name }}">
                                        </a>
                                        <div class="card-body pt-3  p-0">
                                            <h5 class="card-title">{{ ucwords($service->name) }}</h5>
                                            <p
                                                class="card-text text-gray-600 {{ \Illuminate\Support\Str::length($service->description) > 80 ? 'more' : '' }}">
                                                {!! $service->description !!}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- gallary --}}
            @if (checkFeature('gallery') && $vcard->gallery->count())
                <div class="vcard-two__gallery mt-3 py-3 position-relative px-3 mb-10 mt-0">
                    <h4 class="vcard-two-heading text-center pb-4">{{ __('messages.plan.gallery') }}</h4>
                    <div class="container">
                        <div class="row g-3 gallery-slider">
                            @foreach ($vcard->gallery as $file)
                                @php
                                    $infoPath = pathinfo(public_path($file->gallery_image));
                                    $extension = $infoPath['extension'];
                                @endphp
                                <div class="col-6 p-2">
                                    <div
                                        class="card gallery-card mx-auto p-3 border-0 w-100 h-100 gallery-vcard-block">
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
                                            <div class="d-flex align-items-center video-container">
                                                <video width="100%" controls>
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
                                                class="w-100"  height="225">
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
                <div class="vcard-two__product mt-3 py-3 position-relative px-3 mb-10 mt-0">
                    <h4 class="vcard-two-heading text-center pb-4">{{ __('messages.plan.products') }}</h4>
                    <div class="text-end me-5 my-3">
                        <a class="fs-4 mb-0 text-decoration-underline" href="{{ route('showProducts',['id'=>$vcard->id,'alias'=>$vcard->url_alias]) }}">{{__('messages.analytics.view_more')}}</a>
                    </div>
                    <div class="container">
                        <div class="row g-3 product-slider">
                            @foreach ($vcardProducts as $product)
                                <div class="w-100 p-2">
                                    <a @if ($product->product_url) href="{{ $product->product_url }}" @endif
                                        target="_blank" class="text-decoration-none fs-6">
                                        <div class="card product-card p-3 border-0 w-100 h-100">
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
                                                        class="text-black product-price-{{ $product->id }}">{{ $product->currency->currency_icon }}{{ number_format($product->price, 2) }}</span>
                                                @elseif($product->price)
                                                    <span class="text-black product-price-{{ $product->id }}">{{ getUserCurrencyIcon($vcard->user->id) .' '. $product->price }}</span>
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
                <div class="vcard-two__testimonial mt-3 py-3 position-relative px-3 mb-10 mt-0">
                    <h4 class="vcard-two-heading text-center pb-4">{{ __('messages.plan.testimonials') }}</h4>
                    <div class="container">

                        <div class="row g-3 testimonial-slider">
                            @foreach ($vcard->testimonials as $testimonial)
                                <div class="col-12 p-2">
                                    <div
                                        class="card testimonial-card flex-sm-row flex-column-reverse p-3 border-0 align-items-center w-100">
                                        <div class="me-sm-3">
                                            <p
                                                class="review-message mb-2 text-sm-start text-center {{ \Illuminate\Support\Str::length($testimonial->description) > 100 ? 'more' : '' }}">
                                                {!! $testimonial->description !!}
                                            </p>
                                            <div
                                                class="user-details d-flex justify-content-sm-start justify-content-center">
                                                <span class="user-name">{{ ucwords($testimonial->name) }}</span>
                                                <span class="user-designation"></span>
                                            </div>
                                        </div>
                                        <div class="testimonial-profile mb-sm-0 mb-3 ms-sm-auto ">
                                            <img src="{{ $testimonial->image_url }}" alt="profile"
                                                class="rounded-circle" />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            {{-- blog- --}}
            @if (checkFeature('blog') && $vcard->blogs->count())
                <div class="vcard-two__blog py-3  mt-0">
                    <h4 class="vcard-two-heading text-center pb-4">{{ __('messages.feature.blog') }}</h4>
                    <div class="container">
                        <div class="row g-4 mb-0 blog-slider overflow-hidden">
                            @foreach ($vcard->blogs as $blog)
                                <div class="col-6 mb-2">
                                    <div class="card blog-card p-4 border-0 w-100 h-100 blog-block">
                                        <div class="blog-image">
                                            <a href="{{ route('vcard.show-blog', [$vcard->url_alias, $blog->id]) }}">
                                                <img src="{{ $blog->blog_icon }}" alt="profile" class="w-100" />
                                            </a>
                                        </div>
                                        <div class="blog-details mt-3">
                                            <a href="{{ route('vcard.show-blog', [$vcard->url_alias, $blog->id]) }}"
                                                class="text-decoration-none">
                                                <h4 class="text-sm-start text-center text-black p-3 mb-0">
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
                <div class="vcard-two__business-hour py-4 position-relative mb-10 mt-0">
                    <h4 class="vcard-two-heading text-center pb-4">{{ __('messages.business.business_hours') }}</h4>
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
                <div class="vcard-two__appointment mb-10 mt-0">
                    <h4 class="vcard-two-heading text-center pb-4">{{ __('messages.make_appointments') }}</h4>
                    <div class="container">
                        <div class="appointment p-4">
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
                                class="appointmentAdd appoint-btn rounded text-white mt-4 d-block mx-auto ">{{ __('messages.make_appointments') }}
                            </button>
                            @include('vcardTemplates.appointment')
                        </div>
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
            <div class="vcard-two__contact py-4 pt-0 position-relative mb-10 mt-0">
                <img src="{{ asset('assets/img/vcard2/shape6.png') }}"
                    class="position-absolute start-0 bottom-0 d-sm-block d-none" alt="shape" />
                @if ($currentSubs && $currentSubs->plan->planFeature->enquiry_form && $vcard->enable_enquiry_form)
                    <h4 class="vcard-two-heading text-center pb-4">{{ __('messages.contact_us.inquries') }}</h4>
                    <form id="enquiryForm">
                        @csrf
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <form id="enquiryForm">
                                        @csrf
                                        <div class="contact-form px-sm-5">
                                            <div id="enquiryError" class="alert alert-danger d-none"></div>
                                            <div class="mb-3">
                                                <input type="text" name="name" class="form-control"
                                                    id="name"
                                                    placeholder="{{ __('messages.form.your_name') }}">
                                            </div>
                                            <div class="mb-3">
                                                <input type="email" name="email" class="form-control"
                                                    id="email"
                                                    placeholder="{{ __('messages.form.your_email') }}">
                                            </div>
                                            <div class="mb-3">
                                                <input type="tel" name="phone" class="form-control"
                                                    id="phone"
                                                    placeholder="{{ __('messages.form.enter_phone') }}">
                                            </div>
                                            <div class="mb-3">
                                                <textarea class="form-control" name="message" placeholder="{{ __('messages.form.type_message') }}" id="message"
                                                    rows="5"></textarea>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit"
                                                    class="contact-btn text-white mt-4 rounded d-block ms-sm-auto">{{ __('messages.contact_us.send_message') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
            @if (!empty($userSetting['enable_affiliation']))
                <div class="container mb-10">
                    <h4 class="vcard-two-heading text-center mb-5 align-items-center">
                        {{ __('messages.create_vcard') }}</h4>
                    <div class="bg-white p-4 text-center rounded mb-20">
                        <a href="{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}"
                            class="d-flex p-4 justify-content-center border border-2 align-items-center text-decoration-none link-text font-primary"
                            target="_blank">{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}<i
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
                            class="vcard2-sticky-btn bars-btn d-flex justify-content-center text-white me-5 align-items-center rounded-0 px-5 mb-3 text-decoration-none py-1 rounded-pill justify-content-center">
                            <img src="{{ asset('assets/img/vcard2/sticky.png') }}" />
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
                                            class="vcard2-sticky-btn d-flex justify-content-center text-white align-items-center rounded-0 text-decoration-none py-1 rounded-pill justify-content share-wp-btn">
                                            <i class="fa-solid fa-paper-plane"></i> </a>
                                    </div>
                                </div>
                            @endif
                            @if (empty($userSetting['hide_stickybar']))
                                <div
                                    class="{{ isset($userSetting['whatsapp_share']) && $userSetting['whatsapp_share'] == 1 ? 'vcard2-btn-group' : 'stickyIcon' }}">
                                    <button type="button"
                                        class="vcard2-share sticky-btn vcard-btn-group vcard2-sticky-btn mb-3 rounded-0 px-2 py-1"><i
                                            class="fas fa-share-alt fs-1"></i></button>
                                    @if(!empty($vcard->enable_download_qr_code))
                                    <a type="button"
                                        class=" vcard2-sticky-btn  vcard-btn-group d-flex justify-content-center text-danger align-items-center text-decoration-none sticky-btn  px-2 mb-3 py-2"
                                        id="qr-code-btn" download="qr_code.png"><i
                                            class="fa-solid fa-qrcode fs-4"></i></a>
                                    @endif
                                    {{-- <a type="button"
                                        class=" vcard2-sticky-btn  vcard-btn-group d-flex justify-content-center text-danger align-items-center text-decoration-none sticky-btn  px-2 mb-3 py-2 d-none"
                                        id="videobtn"><i class="fa-solid fa-video fs-1" style="color: #eceeed;"></i></a> --}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="w-100 d-flex justify-content-center sticky-vcard-div">
                    @if (!empty($userSetting['enable_contact']))
                        <a href="{{ route('add-contact', $vcard->id) }}"
                            class="vcard2-sticky-btn add-contact-btn d-flex justify-content-center text-white  ms-0 align-items-center rounded px-5 text-decoration-none py-1 justify-content-center"><i
                                class="fas fa-download fa-address-book fs-4"></i>
                            &nbsp;{{ __('messages.setting.add_contact') }}</a>
                    @endif
                </div>
            </div>

            {{-- share modal code --}}
            <div id="vcard2-shareModel" class="modal fade" role="dialog">
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
            let stripe = '';
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
            });
        </script>
        <script>
            $('.product-slider').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 2,
                autoplay: true,
                slidesToScroll: 1,
                arrows: true,
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
                }, ],
            });
        </script>
        <script>
            $('.gallery-slider').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 2,
                autoplay: true,
                slidesToScroll: 1,
                arrows: true,
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
                }, ],
            });

            $('.blog-slider').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                autoplay: false,
                slidesToScroll: 1,
                arrows: true,
                prevArrow: '<button class="slide-arrow-blog blog-prev-arrow"></button>',
                nextArrow: '<button class="slide-arrow-blog blog-next-arrow"></button>',
            });
        </script>
        <script>
            let isEdit = false;
            let password = "{{ isset(checkFeature('advanced')->password) && !empty($vcard->password) }}";
            let passwordUrl = "{{ route('vcard.password', $vcard->id) }}";
            let enquiryUrl = "{{ route('enquiry.store', ['vcard' => $vcard->id, 'alias' => $vcard->url_alias]) }}";
            let appointmentUrl = "{{ route('appointment.store', ['vcard' => $vcard->id, 'alias' => $vcard->url_alias]) }}";
            let slotUrl = "{{ route('appointment-session-time', $vcard->url_alias) }}";
            let appUrl = "{{ config('app.url') }}";
            let paypalUrl = "{{ route('paypal.init') }}";
            let vcardId = {{ $vcard->id }};
            let vcardAlias = "{{ $vcard->url_alias }}";
            let languageChange = "{{ url('language') }}";
            let lang = "{{ checkLanguageSession($vcard->url_alias) }}";
        </script>
        <script>
            const qrCodeTwo = document.getElementById("qr-code-two");
            const svg = qrCodeTwo.querySelector("svg");
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
