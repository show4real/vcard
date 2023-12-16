@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset(getAppLogo()) }}" class="logo" width="120px" height="50px" style="object-fit: cover"
                alt="{{ getAppName() }}">
        @endcomponent
    @endslot
    <div>
        <h2>Hello </h2>
        <p>You have received an invite from {{ $input['username'] }}.</p>
        Please click on below link to get register.
        <br>
        <a href="{{ $input['referralUrl'] }}">{{ $input['referralUrl'] }}</a>
        <p></p>
        <p>Thanks & Regards,</p>
        <p>{{ getAppName() }}</p>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent
