<!DOCTYPE html>
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
    <link rel="stylesheet" href="{{ asset('assets/css/vcard13.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
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
        <div class="main-content mx-auto w-100 overflow-hidden">
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
            {{-- profile --}}
            <div class="profile-section px-30">
                <div class="card d-flex flex-sm-row-reverse align-items-sm-end align-items-center justify-content-end">
                    <div class="card-img d-flex justify-content-center align-items-center">
                        <img src="{{ $vcard->profile_url }}" class="img-fluid banner-image" alt="banner" />
                    </div>
                    <div class="card-body pb-0 px-0">
                        <div class="profile-name">
                            <h2 class="text-primary mb-2 fs-28">
                                {{ ucwords($vcard->first_name . ' ' . $vcard->last_name) }}</h2>
                            <p class="fs-18 text-black mb-0 fw-5">{{ ucwords($vcard->occupation) }}</p>
                            <p class="fs-18 text-black mb-0 fw-5">{{ ucwords($vcard->job_title) }}</p>
                            <p class="fs-18 text-black mb-0 fw-5">{{ ucwords($vcard->company) }}</p>
                        </div>
                    </div>
                </div>
                <div class="text-primary">
                <p class="profile-desc mb-40 mt-5">
                    {!! $vcard->description !!}
                </p>
             </div>
            </div>
            {{-- social icons --}}
            @if (checkFeature('social_links') && isset($vcard->socialLink) && getSocialLink($vcard))
                <div class="social-media d-flex justify-content-center bg-primary-light mb-40">
                    <div class="social-icons d-flex flex-wrap justify-content-center mt-3 w-100">
                        @foreach (getSocialLink($vcard) as $value)
                            {!! $value !!}
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- events --}}
            <div class="contact-section">
                <div class="px-3 mx-1">
                    <div class="row">
                        @if ($vcard->email)
                            <div class="col-sm-6 mb-40">
                                <div class="contact-box d-flex align-items-center">
                                    <div class="contact-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard13/email.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-white mb-0 fs-14">{{ __('messages.vcard.email_address') }}</p>
                                        <a href="mailto:{{ $vcard->email }}"
                                            class="text-white">{{ $vcard->email }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($vcard->alternative_email)
                            <div class="col-sm-6 mb-40">
                                <div class="contact-box d-flex align-items-center">
                                    <div class="contact-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard13/email.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-white mb-0 fs-14">
                                            {{ __('messages.vcard.alter_email_address') }}</p>
                                        <a href="mailto:{{ $vcard->alternative_email }}"
                                            class="text-white">{{ $vcard->alternative_email }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($vcard->phone)
                            <div class="col-sm-6 mb-40">
                                <div class="contact-box d-flex align-items-center">
                                    <div class="contact-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard13/phone.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-white mb-0 fs-14">{{ __('messages.vcard.mobile_number') }}</p>
                                        <a href="tel:+{{ $vcard->region_code }}{{ $vcard->phone }}"
                                            class="text-white">+{{ $vcard->region_code }}
                                            {{ $vcard->phone }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($vcard->alternative_phone)
                            <div class="col-sm-6 mb-40">
                                <div class="contact-box d-flex align-items-center">
                                    <div class="contact-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard13/phone.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-white mb-0 fs-14">
                                            {{ __('messages.vcard.alter_mobile_number') }}</p>
                                        <a href="tel:+{{ $vcard->alternative_region_code }}{{ $vcard->alternative_phone }}"
                                            class="text-white">+{{ $vcard->alternative_region_code }}
                                            {{ $vcard->alternative_phone }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($vcard->dob)
                            <div class="col-sm-6 mb-sm-0 mb-40">
                                <div class="contact-box d-flex align-items-center">
                                    <div class="contact-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard13/dob-icon.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-white mb-0 fs-14"> {{ __('messages.vcard.dob') }}</p>
                                        <p class="mb-0 text-white">{{ $vcard->dob }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($vcard->location)
                            <div class="col-sm-6">
                                <div class="contact-box d-flex align-items-center">
                                    <div class="contact-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard13/location.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-white mb-0 fs-14">{{ __('messages.setting.address') }}</p>
                                        <p class="text-white mb-0 fs-12">{!! ucwords($vcard->location) !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <br>
            {{-- qr code --}}
            @if (isset($vcard['show_qr_code']) && $vcard['show_qr_code'] == 1)
                <div class="qr-code-section mt-3">
                    <div class="section-heading pb-40">
                        <h2 class="text-primary text-center mb-0 fw-bold">{{ __('messages.vcard.qr_code') }}</h2>
                    </div>
                    <div class="px-30">
                        <div class="qr-code mt-3 mx-auto d-flex flex-column align-items-center position-relative">
                            <div class="qr-profile-img">
                                <img src="{{ $vcard->profile_url }}" class="w-100 h-100 object-fit-cover" />
                            </div>
                            <div class="qr-code-img text-center" id="qr-code-thirteen">
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

            {{-- Services --}}
            @if (checkFeature('services') && $vcard->services->count())
                <div class="our-services-section px-30 mt-4">
                    <div class="section-heading text-center pb-sm-3">
                        <h2 class="text-primary text-center fw-bold mb-3">{{ __('messages.vcard.our_service') }}</h2>
                    </div>

                    <div class="services">
                        <div class="row">
                            @foreach ($vcard->services as $service)
                                <div class="col-sm-6 mb-40">
                                    <div class="service-card h-100">
                                        <a href="{{ $service->service_url ?? 'javascript:void(0)' }}"
                                        class="text-decoration-none"
                                        target="{{ $service->service_url ? '_blank' : '' }}">
                                        <div
                                            class="card-img mb-20 mx-auto d-flex justify-content-center align-items-center">
                                            <img src="{{ $service->service_icon }}" class="web-design"
                                                alt="{{ $service->name }}" />
                                                </div>
                                                </a>
                                        <div class="card-body text-center p-0 mt-4">
                                            <h3 class="card-title fs-18 text-white">{{ ucwords($service->name) }}</h3>
                                            <p
                                                class="mb-0 fs-14 fw-light text-white {{ \Illuminate\Support\Str::length($service->description) > 80 ? 'more' : '' }}">
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
                <div class="vcard-thirteen__gallery gallery-section pt-40 px-sm-0">
                    <h2 class="text-primary text-center mb-3 pb-3 fw-bold">{{ __('messages.plan.gallery') }}</h2>
                    <div class="gallery-slider ps-sm-3 pe-sm-0 px-3">
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
                                        <iframe id="video"
                                            src="https://www.youtube.com/embed/{{ YoutubeID($file->link) }}"
                                            class="w-100" height="315">
                                        </iframe>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif


            {{-- products --}}
            @if (checkFeature('products') && $vcard->products->count())
                <div class="product-section pt-40">
                    <div class="section-heading text-center pb-3  px-30">
                        <h2 class="text-primary mb-0 fw-bold">{{ __('messages.plan.products') }}</h2>
                    </div>
                    <div class="text-end mb-3 me-3 ">
                        <a class="fs-5 mb-0 text-decoration-underline" href="{{ route('showProducts',['id'=>$vcard->id,'alias'=>$vcard->url_alias]) }}">{{__('messages.analytics.view_more')}}</a>
                        </div>
                    <div class="">
                        <div class="product-slider">
                            @foreach ($vcardProducts as $product)
                                <div class="">
                                    <a @if ($product->product_url) href="{{ $product->product_url }}" @endif
                                    target="_blank" class="text-decoration-none fs-6">
                                    <div class="product-card card">
                                        <div class="product-img card-img">
                                            <img src="{{ $product->product_icon }}" class="img-fluid h-100" />
                                        </div>
                                        <div class="product-desc card-body">
                                            <div class="product-title">
                                                <h3 class="text-black fs-18 fw-5">{{ $product->name }}</h3>
                                            </div>
                                            <p class="fs-12 text-gray-100 mb-0 fw-5">
                                                {{ $product->description }}
                                            </p>
                                            <div class="product-amount text-primary text-center fw-5">
                                                @if ($product->currency_id && $product->price)
                                                    <span
                                                        class="product-amount text-primary text-center fw-5">{{ $product->currency->currency_icon }}{{ number_format($product->price, 2) }}</span>
                                                @elseif($product->price)
                                                    <span
                                                        class="product-amount text-primary text-center fw-5">{{ getUserCurrencyIcon($vcard->user->id) .' '. $product->price }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            {{-- testimonials --}}
            @if (checkFeature('testimonials') && $vcard->testimonials->count())
                <div class="testimonial-section pt-40 pb-40">
                    <div class="section-heading mb-3 pb-3">
                        <h2 class="text-primary text-center mb-0 fw-bold">{{ __('messages.plan.testimonials') }}</h2>
                    </div>

                    <div class="testimonial-slider">
                        @foreach ($vcard->testimonials as $testimonial)
                            <div class="px-sm-4 px-3">
                                <div
                                    class="testimonial-card d-sm-flex justify-content-sm-start justify-content-center align-items-center">
                                    <div class="profile-img">
                                        <img src="{{ $testimonial->image_url }} "
                                            class="img-fluid h-100 object-fit-cover" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div
                                            class="quote-left-img quote-img d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('assets/img/vcard13/quote-left.png') }}"
                                                class="img-fluid h-100 object-fit-cover" />
                                        </div>
                                        <div
                                            class="quote-right-img quote-img d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('assets/img/vcard13/quote-right.png') }}"
                                                class="img-fluid h-100 object-fit-cover" />
                                        </div>
                                        <div class="text-sm-start text-center">
                                            <p
                                                class="desc text-white mb-0  {{ \Illuminate\Support\Str::length($testimonial->description) > 80 ? 'more' : '' }}">
                                                {!! $testimonial->description !!}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            {{-- blog --}}
            @if (checkFeature('blog') && $vcard->blogs->count())
                <div class="blog-section pb-40">
                    <div class="section-heading text-center mb-3 pb-3 px-4">
                        <h2 class="text-primary mb-0 fw-bold">{{ __('messages.feature.blog') }}</h2>
                    </div>
                    <div class="blog-slider px-4">
                        @foreach ($vcard->blogs as $blog)
                            <div class="">
                                <div class="blog-card mb-sm-0 mb-5 d-flex flex-sm-row-reverse flex-column-reverse">
                                    <div class="card-img">
                                        <a href="{{ route('vcard.show-blog', [$vcard->url_alias, $blog->id]) }}">
                                            <img src="{{ $blog->blog_icon }}" alt="profile"
                                                class="w-100 h-100 object-fit-cover mx-auto" />
                                        </a>
                                    </div>
                                    <div class="card-body text-sm-start text-center p-0 pe-sm-4">
                                        <h2 class="fs-20 text-primary"> {{ $blog->title }}</h2>
                                        <p class="text-gray-100 blog-desc fs-12 fw-5">
                                            {!! $blog->description !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- buissness hours --}}
            @if ($vcard->businessHours->count())
                @php
                    $todayWeekName = strtolower(\Carbon\Carbon::now()->rawFormat('D'));
                @endphp
                <div class="bussiness-hour-section pt-40 pb-40">
                    <div class="section-heading text-center">
                        <h2 class="text-primary mb-0 fw-bold">{{ __('messages.business.business_hours') }}</h2>
                    </div>
                    <div class="px-30">
                        <div class="bussiness-hour-card">
                            @foreach ($businessDaysTime as $key => $dayTime)
                                <div class="mb-10 d-flex align-items-center justify-content-between">
                                    <span
                                        class="me-2">{{ __('messages.business.' . \App\Models\BusinessHour::DAY_OF_WEEK[$key]) }}</span>
                                    <span>{{ $dayTime ?? __("messages.common.closed") }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            {{-- make apoointment --}}
            @if (checkFeature('appointments') && $vcard->appointmentHours->count())
                <div class="appointment-section py-3 px-sm-4 px-3 mt-2 position-relative mb-10 mt-0">
                    <h4 class="heading-left heading-line position-relative text-center mb-5">
                        {{ __('messages.make_appointments') }}</h4>
                    <div class="container">
                        <div class="appointment-card p-3">
                            <div class="row d-flex align-items-center justify-content-center mb-3">
                                <div class="col-md-2">
                                    <label for="date" class="appoint-date mb-2">{{ __('messages.date') }}</label>
                                </div>
                                <div class="col-md-10">
                                    {{ Form::text('date', null, ['class' => 'date appoint-input', 'placeholder' => __('messages.form.pick_date'), 'id' => 'pickUpDate']) }}
                                </div>
                            </div>
                            <div class="row d-flex align-items-center justify-content-center mb-md-3">
                                <div class="col-md-2">
                                    <label for="text" class="appoint-date mb-2">{{ __('messages.hour') }}</label>
                                </div>
                                <div class="col-md-10">
                                    <div id="slotData" class="row">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center w-100">
                                <button type="submit" class="btn vcard-13-btn w-50 w-sm-100 appointmentAdd p-2">
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
            <div class="contact-us-section pt-30 pb-30 px-sm-4 px-3 bg-primary-light">
                <div class="section-heading text-center mb-3 pb-3">
                    <h2 class="text-primary text-center fw-bold mb-0">{{ __('messages.contact_us.inquries') }}</h2>
                </div>
                <div class="contact-form px-sm-4 px-3">
                    <form id="enquiryForm">
                        @csrf
                        <div class="row m-sm-0">
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
                            <div class="col-12 text-center mt-4 w-100">
                                <button class="btn vcard-13-btn p-2 w-50 w-sm-100" type="submit">
                                    {{ __('messages.contact_us.send_message') }}
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            {{-- create vcard --}}
            @if (!empty($userSetting['enable_affiliation']))
                <div class="create-vcard-section pt-40 pb-40 px-30">
                    <div class="section-heading text-center mb-3 pb-3">
                        <h2 class="text-primary fw-bold mb-0">{{ __('messages.create_vcard') }}</h2>
                    </div>
                    <div class="px-sm-4">
                        <div class="vcard-link-card card link-text">
                            <div class="d-flex align-items-center justify-content-center">
                                <a href="{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}"
                                    class="text-primary link-text fw-5"
                                    target="_blank">{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}
                                    <i class="icon fa-solid fa-arrow-up-right-from-square ms-3 text-primary"></i></a>
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
            <div class="w-100 d-flex justify-content-center sticky-vcard-div">
                @if (!empty($userSetting['enable_contact']))
                    <a href="{{ route('add-contact', $vcard->id) }}"
                        class="vcard13-sticky-btn add-contact-btn  d-flex justify-content-center ms-0 text-white align-items-center rounded px-5 text-decoration-none py-1  justify-content-center"><i
                            class="fas fa-download fa-address-book"></i>
                        &nbsp;{{ __('messages.setting.add_contact') }}</a>
                @endif
            </div>
            {{-- sticky btn --}}
            <div class="btn-section cursor-pointer">
                <div class="fixed-btn-section">
                    @if (empty($userSetting['hide_stickybar']))
                        <div class="bars-btn hospital-bars-btn">
                            <img src="{{ asset('assets/img/vcard13/sticky.png') }}" />
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
                                            class="vcard13-sticky-btn vcard13-btn-group d-flex justify-content-center align-items-center text-light rounded-0 text-decoration-none py-1 rounded-pill justify-content share-wp-btn">
                                            <i class="fa-solid fa-paper-plane"></i> </a>
                                    </div>
                                </div>
                            @endif
                            @if (empty($userSetting['hide_stickybar']))
                                <div
                                    class="{{ isset($userSetting['whatsapp_share']) && $userSetting['whatsapp_share'] == 1 ? 'vcard13-btn-group' : 'stickyIcon' }}">
                                    <button type="button"
                                        class="social-btn hospital-sub-btn mb-3 px-2 py-1 wp-btn vcard13-share"><i
                                            class="fas fa-share-alt fs-4"></i></button>
                                    @if(!empty($vcard->enable_download_qr_code))
                                    <a type="button"
                                        class="social-btn hospital-sub-btn d-flex justify-content-center text-white align-items-center  px-2 mb-3 py-2"
                                        id="qr-code-btn" download="qr_code.png"><i
                                            class="fa-solid fa-qrcode fs-4 text-dark"></i></a>
                                    @endif
                                    {{-- <a type="button"
                                        class="social-btn hospital-sub-btn d-flex justify-content-center text-white align-items-center  px-2 mb-3 py-2 d-none"
                                        id="videobtn"><i class="fa-solid fa-video fs-4"
                                            style="color: #eceeed;"></i></a> --}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
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
                                <small class="text-white">{{ __('messages.made_by') }}
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
        </div>
    </div>

    {{-- share modal code --}}
    <div id="vcard13-shareModel" class="modal fade" role="dialog">
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
    @include('vcardTemplates.template.templates')
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript" src="{{ asset('assets/js/front-third-party.js') }}"></script>
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
        $(document).ready(function() {
            $(".gallery-slider").slick({
                arrows: false,
                infinite: true,
                dots: false,
                slidesToShow: 1,
                autoplay: true,
                centerMode: true,

                responsive: [{
                    breakpoint: 575,
                    settings: {
                        infinite: true,
                        arrows: false,
                        dots: true,
                    },
                }, ],
            });
            $(".product-slider").slick({
                arrows: false,
                infinite: true,
                dots: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                centerMode: true,
                centerPadding: "75px",
                responsive: [{
                        breakpoint: 768,
                        settings: {
                            centerPadding: "58px",
                        },
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            centerPadding: "0",
                            infinite: true,
                        },
                    },
                ],
            });
            $(".testimonial-slider").slick({
                arrows: false,
                infinite: true,
                dots: false,
                slidesToShow: 1,
                autoplay: true,
            });

            $(".blog-slider").slick({
                arrows: true,
                infinite: true,
                dots: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                prevArrow: '<button class="slide-arrow prev-arrow"><i class="fa-solid fa-arrow-left"></i></button>',
                nextArrow: '<button class="slide-arrow next-arrow"><i class="fa-solid fa-arrow-right"></i></button>',
                responsive: [{
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 1,
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
        const qrCodeThirteen = document.getElementById("qr-code-thirteen");
        const svg = qrCodeThirteen.querySelector("svg");
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
