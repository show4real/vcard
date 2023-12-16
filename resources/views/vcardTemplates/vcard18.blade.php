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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vcard18.css') }}">
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
        <div class="main-content mx-auto w-100 overflow-hidden">
            <div class="banner-section">
                <div class="banner-img">
                    <img src="{{ $vcard->cover_url }}" class="w-100 h-100 object-fit-cover" alt="">
                </div>

                <img src="{{ asset('assets/img/vcard18/curve-shape.png') }}" class="curve-img w-100" />
                <div class="d-flex justify-content-end position-absolute top-0 end-0 me-3 z-index-9 ">
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
                <div class="overlay"></div>
            </div>

            <div class="profile-section pb-40 px-30">
                <div class="card flex-sm-row-reverse align-items-center pt-sm-0 pt-4">
                    <div class="card-img d-flex justify-content-center align-items-center">
                        <img src="{{ $vcard->profile_url }}" class="w-100 h-100 rounded-circle object-fit-cover mb-5" />
                    </div>
                    <div class="card-body pt-sm-5 px-0">
                        <div class="profile-name">
                            <h2 class="fs-24 text-black mb-2">
                                {{ ucwords($vcard->first_name . ' ' . $vcard->last_name) }}</h2>
                            <p class="fs-6 text-gray-200 mb-0">{{ ucwords($vcard->occupation) }}</p>
                            <p class="fs-6 text-gray-200 mb-0">{{ ucwords($vcard->job_title) }}</p>
                            <p class="fs-6 text-gray-200 mb-0">{{ ucwords($vcard->company) }}</p>
                        </div>
                    </div>
                </div>
                <div class="social-media pt-4">
                    @if (checkFeature('social_links') && isset($vcard->socialLink) && getSocialLink($vcard))
                        <div class="social-media d-flex justify-content-center bg-primary-light">
                            <div
                                class="social-icons d-flex flex-wrap justify-content-center mt-3 h-100 w-100 object-fit-cover fs-14">
                                @foreach (getSocialLink($vcard) as $value)
                                    {!! $value !!}
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="desc px-30 pb-20">
                <p class="text-gray-200 fs-14 mb-0 text-center">
                    {!! $vcard->description !!}
                </p>
            </div>
            <div class="contact-section position-relative px-40 pt-40">
                <div class="contact-bg">
                    <img src="{{ asset('assets/img/vcard18/earth.png') }}" />
                </div>
                <div class="section-heading text-start mb-40 overflow-hidden">
                    <h2 class="mb-0 d-inline-block">{{ __('messages.contact_us.contact') }}</h2>
                </div>
                <div class="position-relative">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact-box d-flex align-items-center">
                                @if ($vcard->email)
                                    <div class="contact-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard18/email-icon.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-black mb-0 fw-5 ">
                                            {{ __('messages.vcard.email_address') }}</p>
                                        <a href="mailto:{{ $vcard->email }}"
                                            class=" text-gray-200">{{ $vcard->email }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mt-md-0 mt-40">
                            <div class="contact-box d-flex">
                                @if ($vcard->alternative_email)
                                    <div class="contact-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard18/email-icon.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-black mb-0 fw-5">
                                            {{ __('messages.vcard.alter_email_address') }}</p>
                                        <a href="mailto:{{ $vcard->alternative_email }}"
                                            class="text-gray-200">{{ $vcard->alternative_email }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($vcard->phone)
                            <div class="col-md-6  mt-40">
                                <div class="contact-box d-flex align-items-center">
                                    <div class="contact-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard18/phone-icon.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-black mb-0 fw-5 fs-14">
                                            {{ __('messages.vcard.mobile_number') }}</p>
                                        <a href="tel:+{{ $vcard->region_code }}{{ $vcard->phone }}"
                                            class="fs-14 text-gray-200">+{{ $vcard->region_code }}{{ $vcard->phone }}</a>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6 mt-40">
                                @if ($vcard->alternative_phone)
                                    <div class="contact-box d-flex">
                                        <div class="contact-icon d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('assets/img/vcard18/phone-icon.png') }}" />
                                        </div>
                                        <div class="contact-desc">
                                            <p class="text-black mb-0 fw-5 fs-14">
                                                {{ __('messages.vcard.alter_mobile_number') }}</p>
                                            <a href="tel:+{{ $vcard->alternative_region_code }}{{ $vcard->alternative_phone }}"
                                                class="fs-14 text-gray-200">+{{ $vcard->alternative_region_code }}{{ $vcard->alternative_phone }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                        @if ($vcard->dob)
                            <div class="col-md-6 mt-40">
                                <div class="contact-box d-flex align-items-center">
                                    <div class="contact-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard18/dob-icon.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-black mb-0 fw-5 fs-14">{{ __('messages.vcard.dob') }}</p>
                                        <p class="mb-0 text-gray-200 fs-14">
                                            {{ $vcard->dob }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($vcard->location)
                            <div class="col-md-6 mt-40">
                                <div class="contact-box d-flex">
                                    <div class="contact-icon d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard18/location.png') }}" />
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-black mb-0 fw-5 fs-14">{{ __('messages.setting.address') }}</p>
                                        <p class="text-gray-200 fs-14 mb-0">{!! ucwords($vcard->location) !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- qrcode --}}
            @if (isset($vcard['show_qr_code']) && $vcard['show_qr_code'] == 1)
                <div class="qr-code-section pt-60 px-40">
                    <div class="section-heading text-end mb-40">
                        <h2 class="inline-block mb-0"> {{ __('messages.vcard.qr_code') }}</h2>
                    </div>
                    <div class="d-inline-block w-100 mx-auto mt-40">
                        <div class="qr-code mx-auto position-relative">
                            <div class="qr-profile-img">
                                <img src="{{ $vcard->profile_url }}" class="w-100 h-100 object-fit-cover" />
                            </div>
                            <div class="qr-code-img mx-auto" id="qr-code-eighteen">
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
            <div class="our-services-section px-40 pt-60">
                <div class="amount-bg">
                    <img src="{{ asset('assets/img/vcard18/amount.png') }}" />
                </div>
                <div class="bag-bg">
                    <img src="{{ asset('assets/img/vcard18/bag.png') }}" />
                </div>
                <div class="dollar-bg">
                    <img src="{{ asset('assets/img/vcard18/dollar.png') }}" />
                </div>

                {{-- service --}}
                @if (checkFeature('services') && $vcard->services->count())
                    <div class="section-heading text-end mt-45 mb-40 overflow-hidden">
                        <h2 class="mb-0 d-inline-block"> {{ __('messages.vcard.our_service') }}</h2>
                    </div>
                    <div class="services">
                        <div class="row">
                            @foreach ($vcard->services as $service)
                                <div class="col-12 mb-3">
                                    <div class="service-card card d-flex flex-row align-items-center">
                                        <div class="card-img me-4">
                                            <a href="{{ $service->service_url ?? 'javascript:void(0)' }}"
                                                class="text-decoration-none"
                                                target="{{ $service->service_url ? '_blank' : '' }}">
                                                <img src="{{ $service->service_icon }}" alt="{{ $service->name }}"
                                                    class="w-100 h-100 object-fit-cover" />
                                            </a>
                                        </div>
                                        <div class="card-body p-0">
                                            <h3 class="card-title fs-6 fw-5">{{ ucwords($service->name) }}
                                            </h3>
                                            <p
                                                class="mb-0 fs-14 text-gray-200  {{ \Illuminate\Support\Str::length($service->description) > 80 ? 'more' : '' }}">
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
                <div class="gallery-section pt-60 px-40">
                    <div class="bag-bg">
                        <img src="{{ asset('assets/img/vcard18/bag.png') }}" />
                    </div>
                    <div class="section-heading text-end overflow-hidden">
                        <h2 class="mb-0 d-inline-block">{{ __('messages.plan.gallery') }}</h2>
                    </div>
                    <div class="gallery-slider pt-3 mt-3">
                        @foreach ($vcard->gallery as $file)
                            @php
                                $infoPath = pathinfo(public_path($file->gallery_image));
                                $extension = $infoPath['extension'];
                            @endphp
                            <div>
                                <div class="gallery-img">
                                    @if ($file->type == App\Models\Gallery::TYPE_IMAGE)
                                        <a href="{{ $file->gallery_image }}" data-lightbox="gallery-images"><img
                                                src="{{ $file->gallery_image }}" alt="profile"
                                                class="w-100 h-100" /></a>
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
                                        <video width="100%" height="100%" controls>
                                            <source src="{{ $file->gallery_image }}">
                                        </video>
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
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <iframe id="video" src="//www.youtube.com/embed/Q1NKMPhP8PY" class="w-100"
                                    height="315">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- product --}}
            @if (checkFeature('products') && $vcard->products->count())
                <div class="product-section bg-gray pt-60 px-40">
                    <div class="product-bg">
                        <img src="{{ asset('assets/img/vcard18/product-bg.png') }}" />
                    </div>
                    <div class="text-end">
                        <a class="fs-5 text-decoration-underline mb-0" href="{{ route('showProducts',['id'=>$vcard->id,'alias'=>$vcard->url_alias]) }}">{{__('messages.analytics.view_more')}}</a>
                    </div>
                    <div class="section-heading text-start overflow-hidden mb-40 mx-3">
                        <h2 class="mb-0 d-inline-block">{{ __('messages.plan.products') }}</h2>
                    </div>
                    <div class="product-slider pb-0">
                        @foreach ($vcard->products as $product)
                            <div class="">
                                <a @if ($product->product_url) href="{{ $product->product_url }}" @endif
                                    target="_blank" class="text-decoration-none fs-6">
                                    <div class="product-card card mb-3">
                                        <div class="product-img card-img">
                                            <img src="{{ $product->product_icon }}"
                                                class="w-100 h-100 object-fit-cover" />
                                        </div>
                                </a>
                                <div class="product-desc card-body">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="product-title text-black fs-6 fw-5">
                                            {{ $product->name }}</h3>
                                        @if ($product->currency_id && $product->price)
                                            <span
                                                class="product-amount text-primary fw-5 fs-6">{{ $product->currency->currency_icon }}{{ number_format($product->price, 2) }}</span>
                                        @elseif($product->price)
                                            <span
                                                class="product-amount text-primary fw-5 fs-6">{{ getUserCurrencyIcon($vcard->user->id) . ' ' . $product->price }}</span>
                                        @endif
                                    </div>
                                    <p class="fs-14 text-gray-200 mb-0">{{ $product->description }}</p>
                                </div>
                            </div>
                    </div>
            @endforeach
        </div>
    </div>
    @endif
    {{-- testimonial --}}
    @if (checkFeature('testimonials') && $vcard->testimonials->count())
        <div class="testimonial-section pt-60 px-sm-4 px-1">
            <div class="section-heading text-start mb-40 overflow-hidden px-3">
                <h2 class="mb-0 d-inline-block"> {{ __('messages.plan.testimonials') }}</h2>
            </div>
            <div class="testimonial-slider">
                @foreach ($vcard->testimonials as $testimonial)
                    <div class="px-3">
                        <div class="testimonial-card">
                            <div class="card-img mb-3">
                                <img src="{{ $testimonial->image_url }}" />
                            </div>
                            <div class="card-body text-center p-0">
                                <h3 class="fs-20 fw-6 text-black mb-0">
                                    {{ ucwords($testimonial->name) }}</h3>
                                <div class="d-flex testimonial-desc">
                                    <div class="quote-img quote-left-img">
                                        <img src="{{ asset('assets/img/vcard18/quote-left-img.png') }}" />
                                    </div>
                                    <p
                                        class="card-desc fs-6 fw-4 text-gray-200 mb-0 text-center {{ \Illuminate\Support\Str::length($testimonial->description) > 80 ? 'more' : '' }}">
                                        {!! $testimonial->description !!}
                                    </p>
                                    <div class="quote-img quote-right-img align-self-end">
                                        <img src="{{ asset('assets/img/vcard18/quote-right-img.png') }}" />
                                    </div>
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
        <div class="blog-section pt-60 px-sm-4 px-1">
            <div class="blog-bg">
                <img src="{{ asset('assets/img/vcard18/product-bg.png') }}" />
            </div>
            <div class="section-heading text-end overflow-hidden px-3 mb-40">
                <h2 class="mb-0 d-inline-block">{{ __('messages.feature.blog') }}</h2>
            </div>
            <div class="blog-slider mb-0">
                @foreach ($vcard->blogs as $blog)
                    <div class="px-3">
                        <div class="blog-card d-flex flex-sm-row flex-column">
                            <div class="card-img">
                                <a href="{{ route('vcard.show-blog', [$vcard->url_alias, $blog->id]) }}">
                                    <img src="{{ $blog->blog_icon }}"
                                        class="img-fluid h-100 w-100 object-fit-cover mx-auto"></a></p>
                            </div>
                            <div class="card-body text-sm-start text-center bg-white pt-sm-3 pt-4">
                                <h2 class="fs-6 fw-5 text-black mb-1 blog-head">{{ $blog->title }}</h2>
                                <p class="text-gray-200 blog-desc fw-normal mb-0 fs-14">
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
        <div class="business-hour-section pt-60 px-40">
            <div class="time-bg-1">
                <img src="{{ asset('assets/img/vcard18/time.png') }}" />
            </div>
            <div class="time-bg-2">
                <img src="{{ asset('assets/img/vcard18/time.png') }}" />
            </div>
            <div class="section-heading text-start overflow-hidden mb-40">
                <h2 class="mb-0 d-inline-block"> {{ __('messages.business.business_hours') }}</h2>
            </div>
            <div class="business-hours mt-3">
                @foreach ($businessDaysTime as $key => $dayTime)
                    <div class="mb-10 d-flex justify-content-between">
                        <span>{{ __('messages.business.' . \App\Models\BusinessHour::DAY_OF_WEEK[$key]) . ':' }}</span>
                        <span>{{ $dayTime ?? __('messages.common.closed') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    {{-- make appointmnet --}}
    @if (checkFeature('appointments') && $vcard->appointmentHours->count())
        <div class="appointment-section pt-60 px-40">
            <div class="appointment-bg">
                <img src="{{ asset('assets/img/vcard18/appointment-bg.png') }}" />
            </div>
            <div class="section-heading text-start overflow-hidden mb-40">
                <h2 class="mb-0 d-inline-block"> {{ __('messages.make_appointments') }}</h2>
            </div>
            <div class="appointment">
                <div class="row mb-3 pb-2">
                    <div class="col-sm-2">
                        <label for="date"
                            class="appoint-date fs-18 fw-5 mt-sm-2 mb-sm-0 mb-2">{{ __('messages.date') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="position-relative">
                            {{ Form::text('date', null, ['class' => 'form-control appointment-input date appoint-input', 'placeholder' => __('messages.form.pick_date'), 'id' => 'pickUpDate']) }}
                            <span class="calendar-icon">
                                <img src=" {{ asset('assets/img/vcard18/calendar.png') }}" />
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <label class="fs-18 fw-5 mt-sm-2 mb-sm-0 mb-2">{{ __('messages.hour') }}</label>
                    </div>
                    <div class="col-md-10">
                        <div id="slotData" class="row">
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-sm-10 mt-3">
                        <button type="submit" class=" appointmentAdd btn btn-primary w-100 rounded-2">
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
    <div class="contact-us-section pt-60">
        <div class="section-heading text-end overflow-hidden mb-40">
            @if ($currentSubs && $currentSubs->plan->planFeature->enquiry_form && $vcard->enable_enquiry_form)
                <h2 class="mb-0"> {{ __('messages.contact_us.inquries') }}</h2>
        </div>
        <div class="contact-form">
            <form action="" id="enquiryForm">
                @csrf
                <div class="row">
                    <div id="enquiryError" class="alert alert-danger d-none"></div>
                    <div class="col-sm-6 pe-sm-2">
                        <div class="mb-3">
                            <label class="fs-14 mb-1">{{ __('messages.form.your_name') }}<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <img src=" {{ asset('assets/img/vcard18/icon-1.png') }}" />
                                </span>
                                <input type="text" name="name" class="form-control"
                                    placeholder="{{ __('messages.form.your_name') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 ps-sm-2">
                        <div class="mb-3">
                            <label class="fs-14 mb-1">{{ __('messages.form.phone') }}</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <img src=" {{ asset('assets/img/vcard18/icon-2.png') }}" />
                                </span>
                                <input type="tel" name="phone" class="form-control"
                                    placeholder="{{ __('messages.form.phone') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="fs-14 mb-1">{{ __('messages.form.your_email') }}<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <img src=" {{ asset('assets/img/vcard18/icon-3.png') }}" />
                                </span>
                                <input type="email" name="email" class="form-control"
                                    placeholder="{{ __('messages.form.your_email') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="fs-14 mb-1">{{ __('messages.contact_us.message') }}<span class="text-danger">*</span></label>
                            <div class="input-group h-100">
                                <textarea class="form-control h-100 ps-3" name="message" placeholder="{{ __('messages.form.type_message') }}"
                                    rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-3 mb-40">
                        <button class="send-btn btn btn-primary  rounded-2" type="submit">
                            {{ __('messages.contact_us.send_message') }}
                        </button>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
    {{-- create vcard --}}
    @if (!empty($userSetting['enable_affiliation']))
        <div class="create-vcard-section pt-60 pb-10 mb-40 px-30">
            <div class="section-heading text-start overflow-hidden mb-40 ">
                <h2 class="mb-0 d-inline-block"> {{ __('messages.create_vcard') }}</h2>
            </div>
            <div class="vcard-link-card card mx-sm-4">
                <div class="d-flex justify-content-center align-items-center">
                    <a href="{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}"
                        target="_blank"
                        class="text-black link-text">{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}
                        <i class="icon fa-solid fa-arrow-up-right-from-square ms-3 text-primary"></i></a>
                </div>
            </div>
        </div>
    @endif
    {{-- add to contact --}}
    <div class="add-to-contact-section">
        @if (!empty($userSetting['enable_contact']))
            <div class="d-flex align-items-center justify-content-center">
                <a href="{{ route('add-contact', $vcard->id) }}"
                    class="btn btn-primary add-contact-btn  rounded-2"><i class="fas fa-download fa-address-book"></i>
                    &nbsp;{{ __('messages.setting.add_contact') }}</a>
            </div>
        @endif
    </div>
    {{-- sticky button --}}
    <div class="btn-section cursor-pointer">
        <div class="fixed-btn-section">
            @if (empty($userSetting['hide_stickybar']))
                <div class="bars-btn corporate-bars-btn">
                    <img src="{{ asset('assets/img/vcard18/sticky.png') }}" />
                </div>
            @endif
            <div class="sub-btn d-none">
                <div class="sub-btn-div">
                    @if (isset($userSetting['whatsapp_share']) && $userSetting['whatsapp_share'] == 1)
                        <div class="icon-search-container mb-3" data-ic-class="search-trigger">
                            <div class="wp-btn">
                                <i class="fab text-light  fa-whatsapp fa-2x" id="wpIcon"></i>
                            </div>
                            <input type="number" class="search-input" id="wpNumber" data-ic-class="search-input"
                                placeholder="{{ __('messages.setting.wp_number') }}" />
                            <div class="share-wp-btn-div">
                                <a href="javascript:void(0)"
                                    class="vcard18-sticky-btn vcard18-btn-group d-flex justify-content-center align-items-center rounded-0 text-decoration-none py-1 rounded-pill justify-content share-wp-btn">
                                    <i class="fa-solid fa-paper-plane text-primary"></i> </a>
                            </div>
                        </div>
                    @endif
                    @if (empty($userSetting['hide_stickybar']))
                        <div
                            class="{{ isset($userSetting['whatsapp_share']) && $userSetting['whatsapp_share'] == 1 ? 'vcard18-btn-group' : 'stickyIcon' }}">
                            <button type="button"
                                class="vcard18-btn-group vcard18-share  vcard18-sticky-btn mb-3  px-2 py-1"><i
                                    class="fas fa-share-alt fs-4"></i></button>
                            @if(!empty($vcard->enable_download_qr_code))
                            <a type="button"
                                class="vcard18-btn-group vcard18-sticky-btn  d-flex justify-content-center text-decoration-none text-primary align-items-center  px-2 mb-3 py-2"
                                id="qr-code-btn" download="qr_code.png"><i class="fa-solid fa-qrcode fs-4"></i></a>
                            @endif
                                {{-- <a type="button"
                                class="vcard18-btn-group vcard18-sticky-btn  d-flex justify-content-center text-decoration-none text-primary align-items-center  px-2 mb-3 py-2 d-none"
                                    id="videobtn"><i class="fa-solid fa-video fs-4"
                                style="color: #eceeed;"></i></a> --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="bg-img">
        <img src="{{ asset('assets/img/vcard18/bg-img.png') }}" />
    </div>
    <div class="container">
        <div class="d-flex  flex-column justify-content-center mt-5 mb-sm-5">
            @if ($vcard->location_url && isset($url[5]))
                <div class="m-2 mb-10 mt-0">
                    <iframe width="100%" height="300px"
                        src='https://maps.google.de/maps?q={{ $url[5] }}/&output=embed' frameborder="0"
                        scrolling="no" marginheight="0" marginwidth="0" style="border-radius: 10px;"></iframe>
                </div>
            @endif
        </div>
    </div>
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
                    <small class="text-white">{{ __('messages.made_by') }}
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
    </div>
    </div>
    {{-- share modal code --}}
    <div id="vcard18-shareModel" class="modal fade" role="dialog">
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
</body>
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
    $().ready(function() {
        $(".gallery-slider").slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            speed: 300,
            infinite: true,
            autoplaySpeed: 5000,
            autoplay: true,
            responsive: [{
                    breakpoint: 500,

                    settings: {
                        dots: true,
                        slidesToShow: 2,
                    },
                },
                {
                    breakpoint: 375,

                    settings: {
                        dots: true,
                        slidesToShow: 1,
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
            arrows: false,
            infinite: true,
            dots: true,
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
                    arrows: false,
                    dots: true,
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
    const qrCodeEighteen = document.getElementById("qr-code-eighteen");
    const svg = qrCodeEighteen.querySelector("svg");
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
