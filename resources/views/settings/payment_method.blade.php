@extends('settings.edit')
@section('section')
    <div class="card w-100">
        <div class="card-body d-flex">
            @include('settings.setting_menu')
            <div class="w-100">
            <div class="card-header px-0">
                <div class="d-flex align-items-center justify-content-center">
                    <h3 class="m-0">{{ __('messages.payment_method') }}
                    </h3>
                </div>
            </div>
            <div class="card-body border-top p-3">
                {{ Form::open(['route' => ['payment.method.update'], 'method' => 'post']) }}
                <div class="">
                    <div class="form-group mb-5 mt-10">
                        <label class="form-check form-switch form-check-custom ">
                            <input class="form-check-input" type="checkbox" value="{{ \App\Models\Plan::STRIPE }}"
                                name="payment_gateway[{{ \App\Models\Plan::STRIPE }}]"
                                {{ isset($selectedPaymentGateways['Stripe']) ? 'checked' : '' }} id="stripe_payment">
                            <span class="form-check-label fw-bold"
                                for="flexSwitchCheckDefault">{{ __('messages.setting.stripe') }}</span>&nbsp;&nbsp;
                        </label>
                    </div>
                    <div class="col-lg-10 row stripe-cred {{ !isset($selectedPaymentGateways['Stripe']) ? 'd-none' : '' }}">
                        <div class="form-group col-lg-6 mb-5">
                            {{ Form::label('stripe_key', __('messages.setting.stripe_key') . ':', ['class' => 'form-label mb-3']) }}
                            {{ Form::text('stripe_key', $setting['stripe_key'], ['class' => 'form-control  stripe-key ', 'placeholder' => __('messages.setting.stripe_key')]) }}
                        </div>
                        <div class="form-group col-lg-6 mb-5">
                            {{ Form::label('stripe_secret', __('messages.setting.stripe_secret') . ':', ['class' => 'form-label stripe-secret-label mb-3']) }}
                            {{ Form::text('stripe_secret', $setting['stripe_secret'], ['class' => 'form-control stripe-secret ', 'placeholder' => __('messages.setting.stripe_secret')]) }}
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="form-group mb-5 mt-10">
                        <label class="form-check form-switch form-check-custom ">
                            <input class="form-check-input" type="checkbox" value="{{ \App\Models\Plan::PAYPAL }}"
                                name="payment_gateway[{{ \App\Models\Plan::PAYPAL }}]" id="paypal_payment"
                                {{ isset($selectedPaymentGateways['Paypal']) ? 'checked' : '' }}>
                            <span class="form-check-label fw-bold"
                                for="flexSwitchCheckDefault">{{ __('messages.setting.paypal') }}</span>&nbsp;&nbsp;
                        </label>
                    </div>
                    <div
                        class="col-lg-10 row paypal-cred {{ !isset($selectedPaymentGateways['Paypal']) ? 'd-none' : '' }}">
                        <div class="form-group col-lg-6 mb-5">
                            {{ Form::label('paypal_client_id', __('messages.setting.paypal_client_id') . ':', ['class' => 'form-label paypal-client-id-label mb-3']) }}
                            {{ Form::text('paypal_client_id', $setting['paypal_client_id'], ['class' => 'form-control  paypal-client-id ', 'placeholder' => __('messages.setting.paypal_client_id')]) }}
                        </div>
                        <div class="form-group col-lg-6 mb-5">
                            {{ Form::label('paypal_secret', __('messages.setting.paypal_secret') . ':', ['class' => 'form-label paypal-secret-label mb-3']) }}
                            {{ Form::text('paypal_secret', $setting['paypal_secret'], ['class' => 'form-control paypal-secret ', 'placeholder' => __('messages.setting.paypal_secret')]) }}
                        </div>
                        <div class="form-group col-lg-4 mb-5">
                            {{ Form::label('paypal_mode', __('messages.setting.paypal_mode') . ':', ['class' => 'form-label paypal-secret-label mb-3']) }}
                            {{ Form::select('paypal_mode', $paypalMode, $setting['paypal_mode'], ['class' => 'form-control paypal-secret ', 'data-control' => 'select2', 'data-minimum-results-for-search' => 'Infinity']) }}
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="form-group mb-5 mt-10">
                        <label class="form-check form-switch form-check-custom ">
                            <input class="form-check-input" type="checkbox" value="{{ \App\Models\Plan::RAZORPAY }}"
                                name="payment_gateway[{{ \App\Models\Plan::RAZORPAY }}]" id="razorpay_payment"
                                {{ isset($selectedPaymentGateways['Razorpay']) ? 'checked' : '' }}>
                            <span class="form-check-label fw-bold"
                                for="razorpay">{{ __('messages.setting.razorpay') }}</span>&nbsp;&nbsp;
                        </label>
                    </div>
                    <div
                        class="col-lg-10 row razorpay-cred {{ !isset($selectedPaymentGateways['Razorpay']) ? 'd-none' : '' }}">
                        <div class="form-group col-lg-6 mb-5">
                            {{ Form::label('razorpay_key', __('messages.setting.razorpay_key') . ':', ['class' => 'form-label razorpay-key-label mb-3']) }}
                            {{ Form::text('razorpay_key', $setting['razorpay_key'], ['class' => 'form-control razorpay-key ', 'placeholder' => __('messages.setting.razorpay_key')]) }}
                        </div>
                        <div class="form-group col-lg-6 mb-5">
                            {{ Form::label('razorpay_secret', __('messages.setting.razorpay_secret') . ':', ['class' => 'form-label razorpay-secret-label mb-3']) }}
                            {{ Form::text('razorpay_secret', $setting['razorpay_secret'], ['class' => 'form-control razorpay-secret ', 'placeholder' => __('messages.setting.razorpay_secret')]) }}
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="form-group mb-5 mt-10">
                        <label class="form-check form-switch form-check-custom">
                            <input class="form-check-input" type="checkbox" value="{{ \App\Models\Plan::MANUALLY }}"
                                name="payment_gateway[{{ \App\Models\Plan::MANUALLY }}]"
                                {{ isset($selectedPaymentGateways['Manually']) ? 'checked' : '' }} id="manually_payment">
                            <span class="form-check-label fw-bold"
                                for="manually_payment">{{ __('messages.setting.manually') }}</span>&nbsp;&nbsp;
                        </label>
                    </div>
                </div>
            </div>
            <div>
                {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-3', 'data-turbo' => 'false']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
    </div>
@endsection
