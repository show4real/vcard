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
    <link rel="stylesheet" href="{{ asset('assets/css/vcard19.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-vcard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lightbox.css') }}">
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
                </div>
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
            <div class="profile-section px-40">
                <div class="profile-bg-img">
                    <img src="{{ asset('assets/img/vcard19/profile-bg-img.png') }}" />
                </div>
                <div class="card d-flex flex-sm-row  align-items-center">
                    <div class="card-img me-sm-4">
                        <img src="{{ $vcard->profile_url }}" class="w-100 h-100 object-fit-cover" />
                    </div>
                    <div class="card-body">
                        <div class="profile-name">
                            <h2 class="text-primary mb-0 fs-28">
                                {{ ucwords($vcard->first_name . ' ' . $vcard->last_name) }}</h2>
                            <p class="fs-20 text-black mb-0 fw-5">{{ ucwords($vcard->occupation) }}</p>
                            <p class="fs-20 text-black mb-0 fw-5">{{ ucwords($vcard->job_title) }}</p>
                            <p class="fs-20 text-black mb-0 fw-5">{{ ucwords($vcard->company) }}</p>
                        </div>
                    </div>
                </div>
                <p class="text-gray-100 profile-desc mb-40 fs-14 text-center">
                    {!! $vcard->description !!}
                </p>
            </div>
            {{-- profile details --}}
            <div class="contact-section pb-40">
                <div class="px-3 mx-1">
                    <div class="row">
                        <div class="col-sm-6 mb-40">
                            @if ($vcard->email)
                                <div class="contact-box d-flex align-items-start">
                                    <div class="contact-icon mt-2 d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/vcard19/email-icon.png') }}" alt="">
                                    </div>
                                    <div class="contact-desc">
                                        <a href="mailto:{{ $vcard->email }}"
                                            class="text-primary fs-12 fw-5">{{ $vcard->email }}</a>
                                        <p class="mb-0 fs-12 text-black">{{ __('messages.vcard.email_address') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-6 mb-40">
                            @if ($vcard->alternative_email)
                                <div class="contact-box d-flex align-items-start">
                                    <div class="contact-icon mt-2 d-flex justify-content-center align-items-start">
                                        <img src="{{ asset('assets/img/vcard19/email-icon.png') }}" alt="">
                                    </div>
                                    <div class="contact-desc">
                                        <a href="mailto:{{ $vcard->alternative_email }}"
                                            class="text-primary fs-12 fw-5">{{ $vcard->alternative_email }}</a>
                                        <p class="mb-0 fs-12 text-black">
                                            {{ __('messages.vcard.alter_email_address') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-6 mb-40">
                            @if ($vcard->phone)
                                <div class="contact-box d-flex align-items-start">
                                    <div class="contact-icon mt-2 d-flex justify-content-center align-items-start">
                                        <img src="{{ asset('assets/img/vcard19/phone-icon.png') }}" alt="">
                                    </div>
                                    <div class="contact-desc">
                                        <a href="tel:+{{ $vcard->region_code }}{{ $vcard->phone }}"
                                            class="text-primary fs-14 fw-5">+{{ $vcard->region_code }}{{ $vcard->phone }}</a>
                                        <p class="text-black fs-12 mb-0">{{ __('messages.vcard.mobile_number') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-6 mb-40">
                            @if ($vcard->alternative_phone)
                                <div class="contact-box d-flex align-items-start">
                                    <div class="contact-icon mt-2 d-flex justify-content-center align-items-start">
                                        <img src="{{ asset('assets/img/vcard19/phone-icon.png') }}" alt="">
                                    </div>
                                    <div class="contact-desc">
                                        <a href="tel:+{{ $vcard->alternative_region_code }}{{ $vcard->alternative_phone }}"
                                            class="text-primary fs-14 fw-5">+{{ $vcard->alternative_region_code }}{{ $vcard->alternative_phone }}</a>
                                        <p class="text-black fs-12 mb-0">
                                            {{ __('messages.vcard.alter_mobile_number') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-6 mb-sm-0 mb-40">
                            @if ($vcard->dob)
                                <div class="contact-box d-flex align-items-start">
                                    <div class="contact-icon mt-2 d-flex justify-content-center align-items-start">
                                        <img src="{{ asset('assets/img/vcard19/dob-icon.png') }}" alt="">
                                    </div>
                                    <div class="contact-desc">
                                        <p class="mb-0 text-primary fs-14 fw-5">
                                            {{ $vcard->dob }}</p>
                                        <p class="text-black mb-0 fs-12">{{ __('messages.vcard.dob') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            @if ($vcard->location)
                                <div class="contact-box d-flex align-items-start">
                                    <div class="contact-icon mt-2 d-flex justify-content-center align-items-start">
                                        <img src="{{ asset('assets/img/vcard19/vector.png') }}" alt="">
                                    </div>
                                    <div class="contact-desc">
                                        <p class="text-primary mb-0 fs-14 fw-5">{!! ucwords($vcard->location) !!}</p>
                                        <p class="text-black fs-12 mb-0">{{ __('messages.setting.address') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{-- social media --}}
            <div class="social-media d-flex justify-content-center">
                <div class="social-bg-img1">
                    <img src="{{ asset('assets/img/vcard19/social-bg-img1.png') }}" />
                </div>
                <div class="social-bg-img2">
                    <img src="{{ asset('assets/img/vcard19/social-bg-img2.png') }}" />
                </div>
                <div class="flex-1 d-flex justify-content-center">
                    @if (checkFeature('social_links') && getSocialLink($vcard))
                        <div class="social-media  d-flex  flex-wrap justify-content-center mb-40">
                            <div class="social-icons d-flex justify-content-center align-items-center flex-wrap">
                                @foreach (getSocialLink($vcard) as $value)
                                    {!! $value !!}
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            {{-- gallery --}}
            @if (checkFeature('gallery') && $vcard->gallery->count())
                <div class="gallery-section pt-30 px-40">
                    <div class="gallery-bg-img">
                        <img src="{{ asset('assets/img/vcard19/gallery-bg-img.png') }}" />
                    </div>
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-black text-center mb-2">{{ __('messages.plan.gallery') }}</h2>
                    </div>
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
                                                class="w-100 h-100 d-block" /></a>
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
                                                style="height:155px;" class="audio-image">
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
                <div class="product-section pt-60 px-40">
                    <div class="product-bg-img1">
                        <img src="{{ asset('assets/img/vcard19/product-bg-img1.png') }}" />
                    </div>
                    <div class="product-bg-img2">
                        <img src="{{ asset('assets/img/vcard19/product-bg-img2.png') }}" />
                    </div>
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-black mb-2">{{ __('messages.plan.products') }}</h2>
                        <div class="text-start me-5">
                            <a class="fs-6 text-decoration-underline mb-0" href="{{ route('showProducts',['id'=>$vcard->id,'alias'=>$vcard->url_alias]) }}">{{__('messages.analytics.view_more')}}</a>
                        </div>
                    </div>
                    <div class="">
                        <div class="product-slider">
                            @foreach ($vcard->products as $product)
                                <div class="">
                                    <div class="product-card card">
                                        <div class="product-img card-img">
                                            <a @if ($product->product_url) href="{{ $product->product_url }}" @endif
                                                target="_blank" class="text-decoration-none">
                                                <img src="{{ $product->product_icon }}"
                                                    class="w-100 h-100 object-fit-cover" />
                                            </a>
                                        </div>
                                        <div class="product-desc card-body pt-2">
                                            <h3 class="text-black fs-18 fw-5">{{ $product->name }}</h3>
                                            <p class="fs-14 text-gray-100 mb-3">
                                                {{ $product->description }}
                                            </p>
                                            <div class="text-center fs-20 fw-5 py-1">
                                                @if ($product->currency_id && $product->price)
                                                    <span
                                                        class="product-amount">{{ $product->currency->currency_icon }}{{ number_format($product->price, 2) }}</span>
                                                @elseif($product->price)
                                                    <span
                                                        class="product-amount">{{ getUserCurrencyIcon($vcard->user->id) . ' ' . $product->price }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            {{-- our service --}}
            @if (checkFeature('services') && $vcard->services->count())
                <div class="our-services-section px-40 pt-60">
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-black text-center mb-2">{{ __('messages.vcard.our_service') }}</h2>
                    </div>
                    <div class="services">
                        <div class="row">
                            @foreach ($vcard->services as $service)
                                <div class="col-sm-6 mb-sm-0 mb-40 mt-4">
                                    <div class="service-card h-100">
                                        <div class="card-img mx-auto d-flex justify-content-center align-items-center">
                                            <a href="{{ $service->service_url ?? 'javascript:void(0)' }}"
                                                class="text-decoration-none"
                                                target="{{ $service->service_url ? '_blank' : '' }}">
                                                <img src="{{ $service->service_icon }}"
                                                    class="w-100" />
                                            </a>
                                        </div>
                                        <div class="card-body text-center p-0 pt-2">
                                            <h3 class="card-title fs-18 text-black">{{ ucwords($service->name) }}</h3>
                                            <p
                                                class="mb-0 fs-14 text-gray-100 text-center  {{ \Illuminate\Support\Str::length($service->description) > 80 ? 'more' : '' }}">
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
            {{-- make appointment --}}
            @if (checkFeature('appointments') && $vcard->appointmentHours->count())
                <div class="appointment-section pt-60 px-40">
                    <div class="appointment-bg-img1">
                        <img src="{{ asset('assets/img/vcard19/appointment-bg-img1.png') }}" />
                    </div>
                    <div class="appointment-bg-img2">
                        <img src="{{ asset('assets/img/vcard19/appointment-bg-img2.png') }}" />
                    </div>
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-black mb-2">{{ __('messages.make_appointments') }}</h2>
                    </div>
                    <div class="appointment px-2">
                        <div class="mb-20">
                            <label for="date"
                                class="appoint-date text-black fs-6 fw-5 mb-1">{{ __('messages.date') }}</label>
                            <div class="row">
                                <div class="col-12 px-2">
                                    <div class="position-relative">
                                        {{ Form::text('date', null, ['class' => 'date appoint-input form-control appointment-input', 'placeholder' => __('messages.form.pick_date'), 'id' => 'pickUpDate']) }}
                                        <span class="calendar-icon"><svg width="20" height="20"
                                                viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6.25 9.375V10.625C6.25 10.9705 5.97047 11.25 5.625 11.25H4.375C4.02953 11.25 3.75 10.9705 3.75 10.625V9.375C3.75 9.02953 4.02953 8.75 4.375 8.75H5.625C5.97047 8.75 6.25 9.02953 6.25 9.375ZM5.625 13.75H4.375C4.02953 13.75 3.75 14.0295 3.75 14.375V15.625C3.75 15.9705 4.02953 16.25 4.375 16.25H5.625C5.97047 16.25 6.25 15.9705 6.25 15.625V14.375C6.25 14.0295 5.97047 13.75 5.625 13.75ZM10.625 8.75H9.375C9.02953 8.75 8.75 9.02953 8.75 9.375V10.625C8.75 10.9705 9.02953 11.25 9.375 11.25H10.625C10.9705 11.25 11.25 10.9705 11.25 10.625V9.375C11.25 9.02953 10.9705 8.75 10.625 8.75ZM10.625 13.75H9.375C9.02953 13.75 8.75 14.0295 8.75 14.375V15.625C8.75 15.9705 9.02953 16.25 9.375 16.25H10.625C10.9705 16.25 11.25 15.9705 11.25 15.625V14.375C11.25 14.0295 10.9705 13.75 10.625 13.75ZM15.625 8.75H14.375C14.0295 8.75 13.75 9.02953 13.75 9.375V10.625C13.75 10.9705 14.0295 11.25 14.375 11.25H15.625C15.9705 11.25 16.25 10.9705 16.25 10.625V9.375C16.25 9.02953 15.9705 8.75 15.625 8.75ZM15.625 13.75H14.375C14.0295 13.75 13.75 14.0295 13.75 14.375V15.625C13.75 15.9705 14.0295 16.25 14.375 16.25H15.625C15.9705 16.25 16.25 15.9705 16.25 15.625V14.375C16.25 14.0295 15.9705 13.75 15.625 13.75ZM4.375 3.75H5.625C5.97047 3.75 6.25 3.47047 6.25 3.125V0.625C6.25 0.279531 5.97047 0 5.625 0H4.375C4.02953 0 3.75 0.279531 3.75 0.625V3.125C3.75 3.47047 4.02953 3.75 4.375 3.75ZM20 5V17.5C20 18.8806 18.8806 20 17.5 20H2.5C1.11937 20 0 18.8806 0 17.5V5C0 3.61937 1.11937 2.5 2.5 2.5H3.125V3.125C3.125 3.81348 3.6859 4.375 4.375 4.375H5.625C6.3141 4.375 6.875 3.81348 6.875 3.125V2.5H13.125V3.125C13.125 3.81348 13.6865 4.375 14.375 4.375H15.625C16.3135 4.375 16.875 3.81348 16.875 3.125V2.5H17.5C18.8806 2.5 20 3.61937 20 5ZM18.75 7.5C18.75 6.81152 18.1897 6.25 17.5 6.25H2.5C1.8109 6.25 1.25 6.81152 1.25 7.5V17.5C1.25 18.1897 1.8109 18.75 2.5 18.75H17.5C18.1897 18.75 18.75 18.1897 18.75 17.5V7.5ZM14.375 3.75H15.625C15.9705 3.75 16.25 3.47047 16.25 3.125V0.625C16.25 0.279531 15.9705 0 15.625 0H14.375C14.0295 0 13.75 0.279531 13.75 0.625V3.125C13.75 3.47047 14.0295 3.75 14.375 3.75Z"
                                                    fill="#753422" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <label class="text-black fs-6 fw-5 mb-1">{{ __('messages.hour') }}</label>
                            <div class="mb-40">
                                <div class="row d-flex">
                                    <div class="col d-flex">
                                        <div id="slotData" class="row">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="appointmentAdd btn btn-primary w-100 rounded-2">
                                        {{ __('messages.make_appointments') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        @include('vcardTemplates.appointment')
                    </div>
            @endif
            {{-- blog --}}
            @if (checkFeature('blog') && $vcard->blogs->count())
                <div class="blog-section pt-60 px-40">
                    <div class="blog-bg-img1">
                        <img src="{{ asset('assets/img/vcard19/blog-bg-img1.png') }}" />
                    </div>
                    <div class="blog-bg-img2">
                        <img src="{{ asset('assets/img/vcard19/blog-bg-img1.png') }}" />
                    </div>
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-black mb-2">{{ __('messages.feature.blog') }}</h2>
                    </div>
                    <div class="blog-slider">
                        @foreach ($vcard->blogs as $blog)
                            <div class="">
                                <div class="blog-card card">
                                    <div class="card-img">
                                        <a href="{{ route('vcard.show-blog', [$vcard->url_alias, $blog->id]) }}">
                                            <div class="img-border">
                                                <div class="mask">
                                                    <img src="{{ $blog->blog_icon }}"
                                                        class="w-100 h-100 object-fit-cover" />
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <h2 class="fs-20 text-primary">{{ $blog->title }}</h2>
                                        <p class="text-gray-100 blog-desc fs-6 fw-5">
                                            {!! $blog->description !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            {{-- testimonial --}}
            @if (checkFeature('testimonials') && $vcard->testimonials->count())
                <div class="testimonial-section pt-60 px-40">
                    <div class="testimonial-bg-img">
                        <img src="{{ asset('assets/img/vcard19/testimonial-bg-img.png') }}" />
                    </div>
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-black text-center mb-2"> {{ __('messages.plan.testimonials') }}</h2>
                    </div>
                    <div class="testimonial-slider">
                        @foreach ($vcard->testimonials as $testimonial)
                            <div class="">
                                <div class="testimonial-card card">
                                    <div class="testimonial-profile-img">
                                        <img src="{{ $testimonial->image_url }}"
                                            class="w-100 h-100 object-fit-cover" />
                                    </div>
                                    <div class="card-body p-0 pt-3">
                                        <div
                                            class="quote-left-img quote-img d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('assets/img/vcard19/quote-left.png') }}"
                                                class="img-fluid h-100 object-fit-cover" />
                                        </div>
                                        <div
                                            class="quote-right-img quote-img d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('assets/img/vcard19/quote-right.png') }}"
                                                class="img-fluid h-100 object-fit-cover" />
                                        </div>
                                        <div class="text-center">
                                            <p
                                                class="desc text-black mb-3 {{ \Illuminate\Support\Str::length($testimonial->description) > 80 ? 'more' : '' }}">
                                                {!! $testimonial->description !!}
                                            </p>
                                            <h3 class="text-primary fs-20 mb-0">{{ ucwords($testimonial->name) }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- qrcode --}}
            @if (isset($vcard['show_qr_code']) && $vcard['show_qr_code'] == 1)
                <div class="qr-code-section pt-60 px-40">
                    <div class="qr-bg-img">
                        <img src="{{ asset('assets/img/vcard19/qr-bg-img.png') }}" />
                    </div>
                    <div class="section-heading mb-40 text-center">
                        <h2 class="text-black mb-2">{{ __('messages.vcard.qr_code') }}</h2>
                    </div>
                    <div class="qr-code d-flex justify-content-center flex-wrap mb-40">
                        <div class="qr-profile-img">
                            <img src="{{ $vcard->profile_url }}" class="w-100 h-100 object-fit-cover rounded-3" />
                        </div>
                        <div class="qr-code-img" id="qr-code-nineteen">
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
            @endif
            {{-- buisness hours --}}
            @if ($vcard->businessHours->count())
                @php
                    $todayWeekName = strtolower(\Carbon\Carbon::now()->rawFormat('D'));
                @endphp
                <div class="business-hour-section pt-60 px-40">
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-black mb-2"> {{ __('messages.business.business_hours') }}</h2>
                    </div>
                    <div class="business-hours mt-3">
                        @foreach ($businessDaysTime as $key => $dayTime)
                            <div class="mb-10 d-flex justify-content-between">
                                <span>{{ __('messages.business.' . \App\Models\BusinessHour::DAY_OF_WEEK[$key]) }}</span>
                                <span>{{ $dayTime ?? __('messages.common.closed') }}</span>
                            </div>
                        @endforeach
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
            <div class="contact-us-section pt-60 px-30">
                <div class="contact-bg-img">
                    <img src="{{ asset('assets/img/vcard19/contact-bg-img.png') }}" />
                </div>
                @if ($currentSubs && $currentSubs->plan->planFeature->enquiry_form && $vcard->enable_enquiry_form)
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-black text-center mb-2">{{ __('messages.contact_us.inquries') }}</h2>
                    </div>
                    <div class="contact-form">
                        <form action="" id="enquiryForm">
                            @csrf
                            <div class="row">
                                <div id="enquiryError" class="alert alert-danger d-none"></div>
                                <div class="col-lg-6 bg-white">
                                    <input type="text" class="form-control" name="name"
                                        placeholder="{{ __('messages.form.your_name') }}" />
                                </div>
                                <div class="col-lg-6 bg-white">
                                    <input type="email" class="form-control" name="email"
                                        placeholder="{{ __('messages.form.your_email') }}" />
                                </div>
                                <div class="col-12 bg-white">
                                    <input type="tel" class="form-control" name="phone"
                                        placeholder="{{ __('messages.form.phone') }}" />
                                </div>

                                <div class="col-12 mb-40 bg-white">
                                    <textarea class="form-control h-100" name="message" placeholder="{{ __('messages.form.type_message') }}"
                                        rows="3"></textarea>
                                </div>
                                <div class="col-12 text-center">
                                    <button class="save-btn btn btn-primary rounded-2" type="submit">
                                        {{ __('messages.contact_us.send_message') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
            {{-- create vcard --}}
            @if (!empty($userSetting['enable_affiliation']))
                <div class="create-vcard-section pt-60 mb-5 px-40">
                    <div class="section-heading text-center mb-40">
                        <h2 class="text-black mb-2">{{ __('messages.create_vcard') }}</h2>
                    </div>
                    <div class="px-sm-3 mb-3">
                        <div class="vcard-link-card card">
                            <div class="d-flex align-items-center justify-content-center">
                                <a href="{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}"
                                    target="_blank"
                                    class="text-primary link-text fw-5">{{ route('register', ['referral-code' => $vcard->user->affiliate_code]) }}
                                    <i class="icon fa-solid fa-arrow-up-right-from-square ms-3 text-primary"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- add contact --}}
            <div class="add-to-contact-section ">
                @if (!empty($userSetting['enable_contact']))
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="{{ route('add-contact', $vcard->id) }}"
                            class="add-contact-btn btn-primary  rounded-2 d-flex align-items-center justify-content-center"><i
                                class="fas fa-download fa-address-book"></i>
                            &nbsp;{{ __('messages.setting.add_contact') }}</a>
                    </div>
                @endif
            </div>
            <div class="bg-img">
                <img src="{{ asset('assets/img/vcard19/bg-img.png') }}" />
            </div>
            {{-- sticky btn --}}
            <div class="btn-section cursor-pointer">
                <div class="fixed-btn-section">
                    @if (empty($userSetting['hide_stickybar']))
                        <div class="bars-btn fashion-bars-btn">
                            <img src="{{ asset('assets/img/vcard19/sticky.png') }}" />
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
                                            class="vcard19-sticky-btn vcard19-btn-group d-flex justify-content-center align-items-center text-primary rounded-0 text-decoration-none py-1 rounded-pill justify-content share-wp-btn">
                                            <i class="fa-solid fa-paper-plane"></i> </a>
                                    </div>
                                </div>
                            @endif
                            @if (empty($userSetting['hide_stickybar']))
                                <div
                                    class="{{ isset($userSetting['whatsapp_share']) && $userSetting['whatsapp_share'] == 1 ? 'vcard19-btn-group' : 'stickyIcon' }}">
                                    <button type="button"
                                        class="vcard19-btn-group vcard19-share  vcard19-sticky-btn mb-3  px-2 py-1"><i
                                            class="fas fa-share-alt fs-4"></i></button>
                                    @if(!empty($vcard->enable_download_qr_code))
                                    <a type="button"
                                        class="vcard19-btn-group vcard19-sticky-btn  d-flex justify-content-center text-primary align-items-center  px-2 mb-3 py-2"
                                        id="qr-code-btn" download="qr_code.png"><i
                                            class="fa-solid fa-qrcode fs-4"></i></a>
                                    @endif
                                    {{-- <a type="button"
                                class="vcard19-btn-group vcard19-sticky-btn  d-flex justify-content-center text-primary align-items-center  px-2 mb-3 py-2 d-none"
                                    id="videobtn"><i class="fa-solid fa-video fs-4"
                                style="color: #eceeed;"></i></a> --}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="d-flex  flex-column justify-content-center mt-5 mb-sm-5">
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
    {{-- share modal code --}}
    <div id="vcard19-shareModel" class="modal fade" role="dialog">
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
            arrows: false,
            infinite: true,
            dots: true,
            slidesToShow: 1,
            autoplay: true,
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
                    dots: true,
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
            autoplay: true,
            responsive: [{
                breakpoint: 575,
                settings: {
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
    const qrCodeNineteen = document.getElementById("qr-code-nineteen");
    const svg = qrCodeNineteen.querySelector("svg");
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
